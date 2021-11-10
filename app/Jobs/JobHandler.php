<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Symfony\Component\Process\Process;

abstract class JobHandler implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    const CONNECTION = 'database';

    /**
     * 別プロセスで即時実行
     *
     * @param string $queue
     * @param integer $timeout
     * @return void
     */
    private static function processStart(string $queue, int $timeout = 12000)
    {
        //別プロセスを建ててartisan queue:work非同期実行
        $artisan = base_path() . DIRECTORY_SEPARATOR . 'artisan';

        $command = 'queue:work';

        $timeout = '--timeout=' . $timeout;

        $connection = static::CONNECTION;

        $env_option = '--env=' . app('config')->get('app.env');

        $once_option = '--once';

        $queue_option = '--queue=' . $queue;

        $exec_command = "{$artisan} {$command} {$timeout} {$connection} {$env_option} {$once_option} {$queue_option} > /dev/null &";

        exec($exec_command, $output, $code);

        $result = Process::$exitCodes[$code];

        if (0 !== $code) {
            \Log::error('exec async process command=' . $exec_command . ' error=' . $result);
        } else {
            \Log::debug('exec async proccess command=' . $exec_command . ' result=' . $result);
        }
    }

    /**
     * ジョブをキューに登録して実行処理 
     * @param string $queue //キュー名
     * @param ...$args //ジョブのコンストラクタ引数
     */
    public static function dispatchToHandle(string $queue, ...$args)
    {
        //$queue以降の引数を受け取り、ジョブを初期化しディスパッチ
        self::pendingDispatch($queue, ...$args);

        //別プロセスを建ててartisan queue:work非同期実行
        self::processStart($queue);
    }


    /**
     * 指定のキューが完了したかどうか
     *
     * @param string $queue キュー名
     * @return boolean
     */
    public static function isQueueFinished(string $queue)
    {
        $connection = self::CONNECTION;

        $jobs_table = config("queue.connections.$connection.table");

        $the_queue_exists = \DB::table($jobs_table)->where('queue', $queue)->exists();

        return !$the_queue_exists;
    }

    /**
     *  非同期ディスパッチ（ジョブをキューに登録）
     * @param string $queue //キュー名
     * @param ...$args //ジョブのコンストラクタ引数
     */
    protected static function pendingDispatch($queue, ...$args)
    {
        $connection = self::CONNECTION;

        //ジョブ初期化
        $pending_dispatch = self::dispatch(...$args);

        //jobsテーブルのqueueにjobを格納
        $dispatch = $pending_dispatch->onConnection($connection)->onQueue($queue);

        return $dispatch;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            \Log::info(
                '非同期JOB 実行 class=' . get_class($this)
                    . ' queue=' . $this->queue
            );

            $this->process();
        } catch (\Exception $e) {
            // エクセプションは握りつぶす（失敗処理に入るとレコードが削除されないため）

            // job 削除
            $this->delete();

            report($e);
        }
    }

    /**
     * 実際の処理
     */
    abstract protected function process();
}

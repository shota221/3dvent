<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Bus\PendingDispatch;
use Illuminate\Support\Facades\Artisan;

abstract class JobHandler implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    const CONNECTION = 'database';

    /**
     * ジョブをキューに登録して実行処理 
     * @param string $queue //キュー名
     * @param ...$args //ジョブのコンストラクタ引数
     */
    public static function dispatchToHandle(string $queue, ...$args)
    {
        //$queue以降の引数を受け取り、ジョブを初期化しディスパッチ
        self::pendingDispatch($queue, ...$args);

        //artisanでhandle実行
        Artisan::call(
            'queue:work',
            [
                '--once'      => true,
                '--queue'     => $queue,
                '--env'       => config('app.env')
            ]
        );
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

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

abstract class CreateSearchDataCsv implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    const CONNECTION = 'database';

    public $search_values;

    /**
     * ジョブをキューに登録して実行処理
     */
    public static function dispatchToHandle(string $queue, array $search_values)
    {
        self::pendingDispatch($queue, $search_values);

        \Log::debug($queue);
        \Log::debug(config('app.env'));
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
     * @param mixed $job
     * @return void
     */
    protected static function pendingDispatch(string $queue, array $search_values)
    {
        
        \Log::debug("ok");

        $connection = self::CONNECTION;

        $job = new static($search_values);

        //実行キューの名称等を指定できるラッパー
        $pending_dispatch = new PendingDispatch($job);

        //jobsテーブルのqueueにつめてjobを格納
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
                'CSV作成非同期JOB 実行 class=' . get_class($this)
                    . ' queue=' . $this->queue
                    . ' params='  . var_export($this->search_values, true)
            );

            $this->create($this->queue, $this->search_values);
        } catch (\Exception $e) {
            // エクセプションは握りつぶす（失敗処理に入るとレコードが削除されないため）

            // job 削除
            $this->delete();

            report($e);
        }
    }

    /**
     * CSV作成
     */
    abstract protected function create(string $queue, array $search_values);
}

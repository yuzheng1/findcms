<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\Queue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class Demo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle($user)
    {
        //
        sleep(2);
        $id = $user->id;
        $str = 'id为：'.$id.'时间为:'.date('Y-m-d H:i:s');
        echo $str;
        file_put_contents(public_path('/demo.txt'),$str.PHP_EOL,FILE_APPEND);
    }
}

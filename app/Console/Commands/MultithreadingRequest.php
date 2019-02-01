<?php

namespace App\Console\Commands;

use GuzzleHttp\Client;
use Illuminate\Console\Command;
use App\Models\Area;

class MultithreadingRequest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:multithreading-request';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
//        $this->info("hello");
//        $arguments = $this->arguments(); //获取所有参数的值
//        $options = $this->options(); //获取所有选项的值
//        $userId = $this->argument('user'); //获取指定参数的值
//        $queueName = $this->option('queue'); //获取指定选项的值
//        $name = $this->ask("What your name");  //输入+提示
//        $password = $this->secret("What your password");  //不可见的输入+提示
//        if($this->confirm("please confirm password[y|N]")){  //确认信息
//            $this->info("Login successfull");
//        } else {
//            $this->info("Login faile");
//        }
//        自动完成
//        anticipate 方法可用于为可能的选项提供自动完成功能，用户仍然可以选择答案，而不管这些选择：
//        $name = $this->anticipate('What is your name?', ['Taylor', 'Dayle']);
//        给用户提供选择
//        如果你需要给用户预定义的选择，可以使用 choice 方法。用户选择答案的索引，但是返回给你的是答案的值。如果用户什么都没选的话你可以设置默认返回的值：
//        $name = $this->choice('What is your name?', ['Taylor', 'Dayle'], 0);
        /**编写输出**/
//        $this->info($name);
//        $this->line($name);
//        $this->comment($name);
//        $this->question($name);
//        $this->error($name);
        /**编写输出**/
//        表格布局
//        table 方法使输出多行/列格式的数据变得简单，只需要将头和行传递给该方法，宽度和高度将基于给定数据自动计算：
//        $headers = ["ID", "NAME"];
//        $model = new Area;
//        $area = $model->newQuery()->limit(3)->get(['id', 'name'])->toArray();
//        $this->table($headers, $area);
//        进度条
//        $area = Area::all();
//        $bar = $this->output->createProgressBar(count($area));
//        $bar->start();
//        foreach($area as $val){
//            usleep(200);
//            $bar->advance();
//        }
//        $bar->finish();
//        $this->info('task finished!');
    }
}

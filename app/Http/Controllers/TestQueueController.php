<?php
/**
 * Created by PhpStorm.
 * User: YZ
 * Date: 2018/11/22
 * Time: 16:35
 */

namespace App\Http\Controllers;


use App\Jobs\Demo;

class TestQueueController
{
    public function sendQueue(){
        $user = new \stdClass();
        $user->id = rand(1,100);
        dispatch(new Demo($user))->onQueue('demo');
    }
}
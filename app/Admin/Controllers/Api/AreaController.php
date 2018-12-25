<?php
namespace App\Admin\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AreaController extends Controller{

    public function index(Request $request)
    {
        $this->validate($request, [
            "q" => "numeric"
        ],[
            "q.numeric" => "错误的参数类型"
        ]);
        $id = $request->get("q");
        if($id){

        }
    }
}
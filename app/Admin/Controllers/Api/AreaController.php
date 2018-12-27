<?php
namespace App\Admin\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Area;

class AreaController extends Controller{

    public function index(Request $request, Area $area)
    {
        $this->validate($request, [
            "q" => "numeric"
        ],[
            "q.numeric" => "错误的参数类型"
        ]);
        $id = $request->get("q", 0);
        $res = $area->getAreaList($id);
        $result[0] = [
            "id" => 0,
            "text" => '请选择'
        ];
        if($id && count($res)>0) {
            $res = $res->toArray();
            foreach($res as $key => $val){
                $result[$key+1] = $val;
            }
        }
        return $result;
    }
}
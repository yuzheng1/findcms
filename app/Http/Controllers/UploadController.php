<?php
/**
 * Created by PhpStorm.
 * User: YZ
 * Date: 2018/11/19
 * Time: 16:56
 */

namespace App\Http\Controllers;



use Illuminate\Http\Request;

class UploadController
{
    public function uploadPage(){
        return view('upload.file');
    }

    public function fileUpload(Request $request){
        if($request->hasFile('picture')){
            $pic = $request->file('picture');
            if(!$pic->isValid()){
                abort(400,'无效的文件');
            }
            //获取扩展名
            $ext = $pic->getClientOriginalExtension();
            //获取文件名
            $fileName = $pic->getClientOriginalName();
            //保存文件名
            $newFileName = md5($fileName.time().rand(1,10000)).'.'.$ext;
            //保存路径
            $path = '/storage/images/'.$newFileName;

        }
    }
}
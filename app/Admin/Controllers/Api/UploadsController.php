<?php
namespace App\Admin\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UploadsController extends Controller{

    public function index(Request $request)
    {
        file_put_contents(__DIR__."/../../../log.txt", json_encode($request));
    }
}
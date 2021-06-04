<?php

namespace App\Http\Controllers\Manual;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ManualController extends Controller
{
    // TODO 表示テスト用　後で消す
    public function test() {
        return view('/Manual/test');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class ChatSupportController extends Controller
{
    public function index()
    {
        $chat_support_url = config('nextcloud.host') . config('nextcloud.chat_path');
        return redirect($chat_support_url);
    }
}

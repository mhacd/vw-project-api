<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chat;

class BotController extends Controller
{
    public function handler(Request $request)
    {
        $update = $request->json()->all();
        $message = $update["message"];
        $chat_id = $message["chat"]["id"];
        $text = $message["text"];
        $apiToken = "5801149316:AAERaOwFQy7_bf2SlOj8zEeAOe5fLiMwcAQ";
        if ($text == '/start') {
            $id = $this->storeChat($chat_id);
            file_get_contents("https://api.telegram.org/bot$apiToken/sendMessage?" . http_build_query(['chat_id' => "1093391597", 'text' => isset($id) ? $chat_id   : 'no']));
        }
    }
    public function storeChat($chat)
    {
        // create new chat
        $model = new Chat;
        // assign "chat" value
        $model->chat = $chat;
        // save record
        $model->save();
        // return the new record with the response
        return $model->id;
    }
}

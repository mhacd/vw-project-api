<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use App\Models\Chat;
use Error;

class ReportController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // create new report
        $model = new Report;
        // assign values
        $model->ip = isset($request->ip) ? $request->ip : 'unknown';
        $model->country = isset($request->country) ? $request->country : 'unknown';
        $model->city = isset($request->city) ? $request->city : 'unknown';
        $model->os = isset($request->os) ? $request->os : 'unknown';
        $model->device = isset($request->device) ? $request->device : 'unknown';
        // save record
        $model->save();
        // ---
        // send this report for all registered chats
        $chats = Chat::all();
        // ---
        return $this->sandMassages($model, $chats);
    }






    /**
     * send request for telegram server to send massages for each user in chats list
     * @param \Illuminate\Http\Report $report
     * @param array $chats
     * @return boolean $message
     */
    public function sandMassages(Report $report, $chats)
    {
        $apiToken = "5801149316:AAERaOwFQy7_bf2SlOj8zEeAOe5fLiMwcAQ";
        try {
            $msg = "🚩 new Visit\n" .
                "IP: $report->ip\n" .
                "Country: $report->country\n" .
                "City: $report->city\n" .
                "Operating System: $report->os\n" .
                "Device: $report->device\n";
            " $report->created_at\n";
            foreach ($chats as $chat) {
                file_get_contents("https://api.telegram.org/bot$apiToken/sendMessage?" . http_build_query(['chat_id' => $chat->chat, 'text' => $msg]));
            }
        } catch (Error $e) {
            return $e;
        }

        return ['massage' => 'success'];
    }
}

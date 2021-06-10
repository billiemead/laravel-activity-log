<?php

namespace Billiemead\LaravelActivityLog\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoginListener
{
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function handle(Login $event)
    {
        if (!config('activity-log.log_events.on_login', false)
            || !config('activity-log.activated', true)) return;

        $user = $event->user;
        $dateTime = date('Y-m-d H:i:s');

        $data = [
            'ip'         => $this->request->ip(),
            'user_agent' => $this->request->userAgent()
        ];

        DB::table('logs')->insert([
            'user_id'    => $user->id,
            'log_date'   => $dateTime,
            'table_name' => '',
            'log_type'   => 'login',
            'data'       => json_encode($data)
        ]);
    }
}
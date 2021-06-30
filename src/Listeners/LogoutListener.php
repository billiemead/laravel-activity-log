<?php

namespace Billiemead\LaravelActivityLog\Listeners;

use Carbon\Carbon;
use Illuminate\Auth\Events\Logout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LogoutListener
{
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function handle(Logout $event)
    {
        if (!config('activity-log.log_events.on_logout', false)
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
            'log_type'   => 'logout',
            'data'       => json_encode($data)
        ]);

        $punch = $user->time->last();
        $punch->clock_out = Carbon::now();

        $punch->secs = Self::calcSecs($punch->clock_out, $punch->clock_in);

        $punch->update();

        DB::table('users')
        ->where('id', $user->id)
        ->update(['clocked_in' => '0']);
    }

    public static function calcSecs($out, $in)
    {

        $secs = Self::cbnToUnix($out) - Self::cbnToUnix($in);

        return $secs;

    }

    public static function cbnToUnix($carbon)
    {
        $date = Carbon::parse($carbon)->format('U');
        return $date;
    }
}
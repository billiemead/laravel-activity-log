<?php

namespace Haruncpi\LaravelUserActivity\Controllers;

use App\Http\Controllers\Controller;
use App\User;
use Haruncpi\LaravelUserActivity\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class StatsController extends Controller
{
    /**
     * Create a new user controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->middleware('auth');

        parent::__construct($request);
    }

    /**
     * Show the manage users page.
     *
     * @return \Illuminate\Http\Response
     */
    public function getLogs()
    {
        $logs = Log::paginate(10);
        $logs->setPath('');
        return view('admin.statsIndex', ['logs' => $logs]);
    }

}
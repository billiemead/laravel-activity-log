<?php
Route::group([
    'namespace' => '\Billiemead\LaravelActivityLog\Controllers',
    'middleware' => config('user-activity.middleware')
    ], function () {
    Route::get(config('user-activity.route_path'), 'ActivityController@getIndex');
    Route::post(config('user-activity.route_path'), 'ActivityController@handlePostRequest');
});
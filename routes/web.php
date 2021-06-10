<?php
Route::group([
    'namespace' => '\Billiemead\LaravelActivityLog\Controllers',
    'middleware' => config('activity-log.middleware')
    ], function () {
    Route::get(config('activity-log.route_path'), 'ActivityController@getIndex');
    Route::post(config('activity-log.route_path'), 'ActivityController@handlePostRequest');
});
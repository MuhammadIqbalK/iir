<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

route::group([
    'prefix' => 'api',
], function () {
    route::get('/test', function () {
        $users = DB::select('SELECT * FROM users');
        return $users;
    });
});

Route::get('/{any?}', function () {
    return view('application');
})->where('any', '.*');


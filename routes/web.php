<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

// API routes are handled in routes/api.php

Route::get('/{any?}', function () {
    return view('application');
})->where('any', '^(?!api).*$');


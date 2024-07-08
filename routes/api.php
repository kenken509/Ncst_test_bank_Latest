<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BackUpController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');




Route::controller(BackUpController::class)->group(function(){
    Route::get('/download-database-backup', 'download')->name('backup.download');
}) ; 
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/membres-ca', [UserController::class, 'getMembresCA'])->name('api.membres.ca');

require __DIR__.'/auth.php';

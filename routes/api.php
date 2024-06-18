<?php

use App\Http\Controllers\API\AuthAPIController;
use App\Http\Controllers\API\OrderAPIController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//Authenticate
Route::post('/gettoken', [AuthAPIController::class, 'gettoken']);

//Orders
Route::post('/orders/create', [OrderAPIController::class, 'createOrder']);

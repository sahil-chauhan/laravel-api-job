<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\InvitationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'auth'],function () {      
    Route::post('/login',[AuthController::class,'login'])->name('api.login');              
    Route::post('/register/{token}',[AuthController::class,'registerViaEmail'])->name('api.mail.register');       
    Route::post('/activate/account',[AuthController::class,'activateAccount'])->name('api.mail.activateAccount');   
});

Route::post('invitations', [InvitationController::class,'store'])->middleware('auth:sanctum');
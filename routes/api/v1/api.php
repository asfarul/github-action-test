<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\UsersController;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// User authentication
Route::prefix('/user')->group(function () {
    Route::post('/authenticate', [UsersController::class, 'authenticate']);
});

// User request (API Token needed)
Route::group([
    'prefix' => '/user',
    'middleware' => ['auth:mobile', 'throttle:60,1'],
], function () {
    Route::get('/', [UsersController::class, 'details']); // device check
    Route::post('/devicecheck', [UsersController::class, 'deviceCheck']); // device check
    Route::post('/update', [UsersController::class, 'update']) // device check
    // Route::get('/details', [UserController::class, 'userDetails']); // user details
});

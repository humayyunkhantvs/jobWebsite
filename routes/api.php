<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::get('example', function () {
//     // Call a helper function directly
//     $user_id = Auth::user()->id;

//     // Do something with the result
//     return $user_id;
// });

// Route::get('jobs/{hash}', [ApiController::class, 'show'])->name('jobs-listings')->middleware('cors');

// Route::get('jobs/{hash}', [ApiController::class, 'showJobs'])->name('jobs-listings')->middleware('cors');
Route::get('jobs', [ApiController::class, 'showJobs'])->name('jobs-listings')->middleware('cors');
// Route::get('jobs/{hash}/{job}', [ApiController::class, 'index'])->name('job-details')->middleware('cors');
Route::get('jobs/{hash}/{job}', [ApiController::class, 'index'])->name('job-details')->middleware('cors');
// Route::get('details/{hash}/{job}', [ApiController::class, 'jobs_detail'])->name('details-job')->middleware('cors');
Route::get('details/{job}', [ApiController::class, 'jobs_detail'])->name('details-job')->middleware('cors');
Route::post('apply_job', [UserController::class, 'apply_job'])->name('apply_job')->middleware('cors');
Route::get('key_api/{token}', [UserController::class, 'apply_job'])->name('auth_key');

<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\JobsController;
use App\Http\Controllers\TeamsController;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Resend\Laravel\Facades\Resend;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('test', function () {
//     Resend::emails()->send([
//         'from' => 'onboarding@resend.dev',
//         'to' => 'mamir.tvs@gmail.com',
//         'subject' => 'hello world',
//         'text' => 'it works!',
//     ]);
// });

// Route::get('example', function () {
//     // Call a helper function directly
//     $response = get_ghl_customfields();

//     // Do something with the result
//     return $response = json_decode($response, true);
// });

Route::get('/', [AdminController::class, 'login'])->name('login');
Route::get('login', [AdminController::class, 'login'])->name('login');
Route::get('signup', [AdminController::class, 'signup'])->name('signup');

Route::post('signup_process', [AdminController::class, 'signup_process'])->name('signup_process');
Route::post('login_process', [AdminController::class, 'login_process'])->name('login_process');



Route::middleware(['verified', 'auth'])->group(function () {

    Route::middleware(['checkrole:superadmin,admin'])->group(function () {
        //category route
        Route::controller(JobsController::class)->group(function () {
            Route::get('categories', 'categories')->name('categories');
            Route::post('add_category', 'add_category')->name('add_category');
            Route::get('category_edit/{id}', 'category_edit')->name('category_edit');
            Route::post('update_category', 'update_category')->name('update_category');
            Route::get('category_delete/{id}', 'category_delete')->name('category_delete');
            Route::post('category_datatable', 'category_datatable')->name('category_datatable');
        });
    });

    Route::middleware(['checkrole:superadmin,admin'])->group(function () {
        Route::controller(JobsController::class)->group(function () {
            Route::get('jobs', 'index')->name('jobs');
            Route::get('get-users-by-admin/{adminId}', 'getUsersByAdmin')->name('getUsersByAdmin');
            Route::post('add_job', 'add_jobs')->name('add_job');
            Route::post('job_datatable', 'job_datatable')->name('job_datatable');
            Route::get('job_edit/{id}', 'job_edit')->name('job_edit');
            Route::post('update_job', 'update_job')->name('update_job');
            Route::get('job_delete/{id}', 'job_delete')->name('job_delete');
            Route::get('template', 'get_template')->name('template');
            Route::get('key_api/{token}',  'auth_key')->name('auth_key');
            Route::post('add_template', 'add_template')->name('add_template');
            Route::get('template_edit/{id}', 'template_edit')->name('template_edit');
            Route::post('update_template', 'update_template')->name('update_template');
            Route::get('template_delete/{id}', 'template_delete')->name('template_delete');
            Route::post('template_datatable', 'template_datatable')->name('template_datatable');
            Route::get('settings', 'settings')->name('settings');
            Route::post('add_settings', 'add_settings')->name('add_settings');
            Route::post('setting_datatable', 'setting_datatable')->name('setting_datatable');
            Route::get('setting_edit/{id}', 'setting_edit')->name('setting_edit');
            Route::post('update_setting', 'update_setting')->name('update_setting');
            Route::get('setting_delete/{id}', 'setting_delete')->name('setting_delete');
            Route::get('applicants', 'get_applicants')->name('applicants');
            Route::get('get_applicant_details/{id}', 'get_applicant_details')->name('get_applicant_details');
            Route::post('applicants_datatable', 'applicants_datatable')->name('applicants_datatable');
            Route::get('get_ghl_customfields', 'get_ghl_customfields')->name('get_ghl_customfields');
            Route::post('set_ghl_customfield_keys', 'set_ghl_customfield_keys')->name('set_ghl_customfield_keys');
            Route::post('set_customfield_keys', 'set_customfield_keys')->name('set_customfield_keys');
            // Route::get('download','download')->name('download');
        });

        Route::controller(TeamsController::class)->group(function () {
            Route::get('teams', 'index')->name('teams');
            Route::post('add_team', 'add_team')->name('add_team');
            Route::post('team_datatable', 'team_datatable')->name('team_datatable');
            Route::get('team_edit/{id}', 'team_edit')->name('team_edit');
            Route::post('update_team', 'update_team')->name('update_team');
            Route::get('team_delete/{id}', 'team_delete')->name('team_delete');
        });

        Route::controller(UserController::class)->group(function () {
        });
    });

    Route::middleware(['checkrole:user'])->group(function () {
        Route::controller(ApiController::class)->group(function () {

            // Route::get('job/{hash}/{id}', 'index')->name('job-details');

        });
        
    });
});


Route::middleware(['verified', 'auth'])->group(function () {
    Route::get('dashboard', [AdminController::class, 'admindashboard'])->name('admindashboard');
    Route::get('update_profile', [AdminController::class, 'update_profile'])->name('update_profile');
    Route::post('update_user', [AdminController::class, 'update_user'])->name('update_user');
});
//user routes start
// Route::post('apply_job', [UserController::class, 'apply_job'])->name('apply_job');


Route::get('logout', [AdminController::class, 'logout'])->name('logout');
//verify e,mail routes start

Route::post('forgot_password_reset', [TeamsController::class, 'forgot_password_email'])->name('password.email_reset');


Route::get('password/reset/{token}', [TeamsController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [TeamsController::class, 'reset'])->name('user.password.update');

Route::get('/email/verify', function () {
    return view('admin.pages.verify-email');
})->middleware('auth')->name('verification.notice');



// Email verification routes
Route::get('email/verify/{id}/{hash}', [TeamsController::class, 'verifyEmail'])
    ->middleware(['signed', 'throttle:5,1'])
    ->name('verification.verify');


Route::get('/email/verify/resend', [AdminController::class, 'resend'])
    ->middleware(['auth'])
    ->name('verification.resend');




Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return redirect()->route('login')->with('message', 'Verification link sent!');
})->middleware(['throttle:6,1'])->name('verification.send');

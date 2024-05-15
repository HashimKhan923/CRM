<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('admin.index');
});


//common routes start

Route::post('/login', '\App\Http\Controllers\AuthController@login');
Route::post('/forgetPassword', '\App\Http\Controllers\AuthController@forgetpassword');
Route::post('/checktoken', '\App\Http\Controllers\AuthController@token_check');
Route::post('/resetPassword', '\App\Http\Controllers\AuthController@reset_password');
Route::get('/logout/{id}', 'App\Http\Controllers\AuthController@logout');


// common routes ends

/// admin Register
Route::post('/admin/register', 'App\Http\Controllers\Admin\AuthController@register');


    
        /////////////////////////////////// Admin Routes \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

       Route::get('/admin/profile/view/{id}', 'App\Http\Controllers\Admin\AuthController@profile_view');
       Route::post('/admin/profile', 'App\Http\Controllers\Admin\AuthController@profile_update');
       Route::get('/logout', 'App\Http\Controllers\AuthController@logout');
       Route::get('/admin/profile/check', 'App\Http\Controllers\Admin\AuthController@usercheck'); 
       Route::get('/admin/dashboard','App\Http\Controllers\Admin\DashboardController@index');



                                       /// Shift \\\

        Route::group(['prefix' => '/admin/shift/'], function() {
            Route::controller(App\Http\Controllers\Admin\ShiftController::class)->group(function () {
                Route::get('show','index');
                Route::post('create','create');
                Route::post('update','update');
                Route::get('delete/{id}','delete');
                Route::get('status/{id}','changeStatus');
            });
        });


                                               /// Department \\\

            Route::group(['prefix' => '/admin/department/'], function() {
            Route::controller(App\Http\Controllers\Admin\DepartmentController::class)->group(function () {
                Route::get('show','index');
                Route::post('create','create');
                Route::post('update','update');
                Route::get('delete/{id}','delete');
                Route::get('status/{id}','changeStatus');
            });
        });

                                                       /// Roles \\\

            Route::group(['prefix' => '/admin/role/'], function() {
            Route::controller(App\Http\Controllers\Admin\RoleController::class)->group(function () {
                Route::get('show','index');
                Route::post('create','create');
                Route::post('update','update');
                Route::get('delete/{id}','delete');
                Route::get('status/{id}','changeStatus');
            });
        });

                                        /// Attendence \\\

            Route::group(['prefix' => '/admin/attendence/'], function() {
            Route::controller(App\Http\Controllers\Admin\AttendenceController::class)->group(function () {
                Route::get('show','index');
                Route::post('search','search');
            });
        });   
        
        
                                         /// Users \\\

        Route::group(['prefix' => '/admin/users/'], function() {
            Route::controller(App\Http\Controllers\Admin\UserController::class)->group(function () {
                Route::get('show','index')->name('admin.users.show');
                Route::get('create_form','create_form')->name('admin.users.create.form');
                Route::post('create','create')->name('admin.users.create');
                Route::get('update_form/{id}','update_form')->name('admin.users.update.form');
                Route::post('update','update')->name('admin.users.update');
                Route::get('status/{id}','changeStatus');
                Route::get('delete/{id}','delete')->name('admin.users.delete');
            });
        });




            /////////////////////////////////// User Routes \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\


                                /// Home \\\

                Route::group(['prefix' => 'home/'], function() {
                Route::controller(App\Http\Controllers\User\HomeController::class)->group(function () {
                    Route::get('show/{id}','index');
                });
            });




                                         /// Attendence \\\

            Route::group(['prefix' => 'attendence/'], function() {
            Route::controller(App\Http\Controllers\User\AttendenceController::class)->group(function () {
                Route::get('show/{id}','index');
                Route::post('search','search');
                Route::get('time_in/{id}','time_in');
                Route::get('time_out/{id}','time_out');
            });
        });



            /// Break \\\

            Route::group(['prefix' => 'break/'], function() {
            Route::controller(App\Http\Controllers\User\BreakController::class)->group(function () {
                Route::get('show/{id}','index');
                Route::post('break_in','break_in');
                Route::get('break_out/{id}','break_out');
            });
        }); 




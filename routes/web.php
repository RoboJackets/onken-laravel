<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('nova/logout', function () {
    return redirect('logout');
})->name('nova.logout');

Route::group(['middleware' => 'auth.cas.force'], function () {
    Route::get('/', function () {
        if (request()->user()->can('access-nova')) {
            return redirect('/nova');
        } else {
            abort(403);
        }
    });
});

Route::get('login', function () {
    return redirect()->intended();
})->name('login')->middleware('auth.cas.force');

Route::get('logout', function () {
    Session::flush();
    cas()->logout(config('app.url'));
})->name('logout');

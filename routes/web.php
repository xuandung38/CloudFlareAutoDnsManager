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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::prefix('account')->group(function () {
    Route::get('', 'AccountAPIController@index')->name('account');
    Route::get('add', 'AccountAPIController@create')->name('account.add');
    Route::post('add', 'AccountAPIController@store');
    Route::get('edit/{id}', 'AccountAPIController@edit')->name('account.edit');
    Route::post('edit/{id}', 'AccountAPIController@update');
    Route::get('delete/{id}', 'AccountAPIController@destroy')->name('account.delete');
});
Route::prefix('domain')->group(function () {
    Route::get('', 'DomainController@index')->name('domain');
    Route::get('show/{id}', 'DomainController@show')->name('domain.show');
    Route::get('add', 'DomainController@create')->name('domain.add');
    Route::post('add', 'DomainController@store');
    Route::get('edit/{id}', 'DomainController@edit')->name('domain.edit');
    Route::post('edit/{id}', 'DomainController@update');
    Route::get('delete/{id}', 'DomainController@destroy')->name('domain.delete');
    Route::get('addrecord', 'DomainController@addRecord')->name('domain.addrecord');
});

Route::prefix('record')->group(function () {
    Route::get('', 'RecordController@index')->name('record');
    Route::get('add', 'RecordController@create')->name('record.add');
    Route::post('add', 'RecordController@store');
    Route::get('delete/{id}', 'RecordController@destroy')->name('record.delete');
    Route::get('updateDns/{id}', 'RecordController@edit')->name('record.updatedns');
    Route::get('fetchDns/{id}', 'RecordController@fetchNewInfo')->name('record.fetchdns');
});


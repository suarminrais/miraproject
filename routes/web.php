<?php


Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('invoice', function(){
    return view('invoice');
});

Route::get('{path}',"HomeController@index")->where( 'path', '[A-Za-z]+' );

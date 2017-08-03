<?php

// DEFINE HOME ROUTE
Route::get('/', function() {
    return view('welcome');
});

Route::get('testapi',['as'=>'submit.request',function(){
    return view('api-form');
}]);

Route::get('logs', ['middleware'=>'auth','uses'=>'\Rap2hpoutre\LaravelLogViewer\LogViewerController@index']);

Route::any('canal/checkaccount',['middleware'=>'auth','uses'=>'\App\Http\Controllers\CheckaccountController@checkaccount']);

Route::any('canal/resubscription',['middleware'=>'auth','uses'=>'\App\Http\Controllers\ResubscriptionController@resubscription']);

Route::any('canal/regularization',['middleware'=>'auth','uses'=>'\App\Http\Controllers\ResubscriptionController@regularization']);

Route::any('canal/renewaloffers',['middleware'=>'auth','uses'=>'\App\Http\Controllers\VerifyRenewalOffersController@renewaloffer']);

Route::get('protected', ['middleware' => ['auth', 'admin'], function() {
    return "this page requires that you be logged in and an Admin";
}]);

	
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');



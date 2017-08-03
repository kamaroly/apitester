<?php

// DEFINE HOME ROUTE
Route::get('/', function() {
    return view('welcome');
});

Route::get('testapi',['middleware'=>'admin','as'=>'submit.request',function(){
    return view('api-form');
}]);

Route::get('logs', ['middleware'=>'admin','uses'=>'\Rap2hpoutre\LaravelLogViewer\LogViewerController@index']);

Route::any('canal/checkaccount',['middleware'=>'admin','uses'=>'\App\Http\Controllers\CheckaccountController@checkaccount']);

Route::any('canal/resubscription',['middleware'=>'admin','uses'=>'\App\Http\Controllers\ResubscriptionController@resubscription']);

Route::any('canal/regularization',['middleware'=>'admin','uses'=>'\App\Http\Controllers\ResubscriptionController@regularization']);

Route::any('canal/renewaloffers',['middleware'=>'admin','uses'=>'\App\Http\Controllers\VerifyRenewalOffersController@renewaloffer']);

	
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');



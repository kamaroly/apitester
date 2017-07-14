<?php


use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ServerException;
use App\Http\Middleware\VerifyCsrfToken;
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
Route::get('testapi',['as'=>'submit.request',function(){
    return view('api-form');
}]);

Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

Route::any('canal/checkaccount','\App\Http\Controllers\CheckaccountController@checkaccount');

Route::any('canal/resubscription','\App\Http\Controllers\ResubscriptionController@resubscription');

Route::any('canal/regularization','\App\Http\Controllers\ResubscriptionController@regularization');

Route::any('canal/renewaloffers','\App\Http\Controllers\VerifyRenewalOffersController@renewaloffer');

	
	
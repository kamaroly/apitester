<?php

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ServerException;

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

Route::get('testapi', function() {
    return view('api-form');
});	

Route::post('submit/request',['as'=>'submit.request',function(){

	// Get submitted REQUESTS
	$request = request()->get('request');
	$url     = request()->get('url');

		// USE GUZZLE TO CALL API
	    $requestData['body']					= $request;
		$requestData['timeout']					= 2;
		$requestData['headers']['Content-Type']	= 'application/xml';

		try
		{
			$client			= new Client();
			$apiResponse	= $client->request('POST',$url,$requestData);	
		}
		catch(\Exception $ex){
			$apiResponse = $ex->getMessage();
		}
	
	return view('api-form',compact('apiResponse')); 
}]);
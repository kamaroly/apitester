<?php 
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ServerException;

Class CanalApiController extends Controller{

	/**
	 * Method in charge of calling canal
	 * @param  array $request contains request to send to Canal
	 * @return string 
	 */
	public function call($requestString)	
  {
	  $requestData['body']= $requestString;

		$requestData['timeout']					= 2;
		$requestData['headers']['Content-Type']	= 'application/xml';
	 try
		{

			$url 			= 'http://172.30.70.10/D1/ws/CGW/mobile/MpaymentService';
			$client			= new Client();
			$canalResponse	= $client->request('POST',$url,$requestData);
			$canalResponse    = trim($canalResponse->getBody()->getContents());

		}
		catch(ClientException $ex){			
			$canalResponse    = trim($ex->getBody()->getContents());
		}
		catch(RequestException $ex)
		{
			$canalResponse    = trim($ex->getMessage());

		}
		catch(ServerException $ex)
		{
			$canalResponse    = trim($ex->getBody()->getContents());
		}
		catch(\Exception $ex){
			$canalResponse = $ex->getMessage();
		}

		return $canalResponse;
	}


}

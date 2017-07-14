<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ServerException;

class CheckaccountController extends Controller
{
    public function checkaccount(){

    	$request = json_decode(request()->getContent());

	// 2. PREPARE REQUEST to send to Canal
	$canalRequest = '<soap:Envelope
	xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
	xmlns:xsd="http://www.w3.org/2001/XMLSchema"
	xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
	<soap:Header/>
	<soap:Body
		xmlns:ns1="MpaymentService">
		<ns1:checkAccount soap:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/">
			<sInXmlData xsi:type="xsd:string">
				<![CDATA[<CheckAccount
				xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"><numCard>12345678901234</numCard><accountRef></accountRef><currency>RWF</currency><operatorName>TIGO</operatorName><country>146</country><eTopupDistributorId></eTopupDistributorId><typeOperation>1</typeOperation></CheckAccount>]]>
			</sInXmlData>
		</ns1:checkAccount>
	</soap:Body>
</soap:Envelope>';


	// 3. SEND REQUEST TO CANAL AND GET RESPONSE
	
		// USE GUZZLE TO CALL API
	    $requestData['body']					= $canalRequest;
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
			$canalResponse    = trim($ex->getResponse());

		}
		catch(ServerException $ex)
		{
			$canalResponse    = trim($ex->getBody()->getContents());
		}
		catch(\Exception $ex){
			$canalResponse = $ex->getMessage();
		}

	    // 4. ANALYSE CANAL RESPONSE
	    $code = '400';
	    $status = 'ERROR';
	    if (strpos($canalResponse, 'SUCCESS') !== FALSE) {
		    $code = '200';
		    $status = 'OK';
	    }
	



	// 5. Based on Canal response build havanao response and respond to havanao
	$response = [
				'transactionid' 	=> $request->transactionid,
				'reference_number' 	=> $request->reference_number,
				'code' 				=> $code,
				'status' 			=> $status,
				'account_balance' 	=> $request->amount,
				'customer' 			=> $request->customer,
				'description' 		=> $canalResponse,
				'payment_reference' => $request->transactionid
				];


  return $response;


    }
}

<?php

Route::post('submit/request',['as'=>'submit.request',function(){

	// Get submitted REQUESTS
	$request = request()->get('request');
	$url     = request()->get('url');
	$request = json_decode($request);
	
	$sampleRequest = '<soap:Envelope
	xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
	xmlns:xsd="http://www.w3.org/2001/XMLSchema"
	xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
	<soap:Header/>
	<soap:Body
		xmlns:ns1="MpaymentService">
		<ns1:registerAutomaticRenewal soap:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/">
			<sInXmlData xsi:type="xsd:string">
				<![CDATA[<RegisterAutomaticRenewal
				
				xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"><idBase>Wcgamobile</idBase><numSubscriber>'.$request->reference_number.'</numSubscriber><numContract>1</numContract><accountRef></accountRef><amount>'.$request->amount.'</amount><currency>RWF</currency><eTopupTransactionId></eTopupTransactionId><operatorName>TIGO</operatorName><country>146</country><eTopupDistributorId></eTopupDistributorId><tokenId>a6e7db462f920b3a960cacad537dfea67928847a11</tokenId></RegisterAutomaticRenewal>]]>
			</sInXmlData>
		</ns1:registerAutomaticRenewal>
	</soap:Body>
</soap:Envelope>';


		// USE GUZZLE TO CALL API
	    $requestData['body']					= $request;
		$requestData['timeout']					= 2;
		$requestData['headers']['Content-Type']	= 'application/xml';

		try
		{
			$client			= new Client();
			$apiResponse	= $client->request('POST',$url,$requestData);
			$apiResponse    = trim($apiResponse->getBody()->getContents());
		}
		catch(ClientException $ex){			
			$apiResponse    = trim($ex->getBody()->getContents());
		}
		catch(RequestException $ex)
		{
			$apiResponse    = trim($ex->getBody()->getContents());
		}
		catch(ServerException $ex)
		{
			$apiResponse    = trim($ex->getBody()->getContents());
		}
		catch(\Exception $ex){
			$apiResponse = $ex->getMessage();
		}
	
	return view('api-form',compact('apiResponse')); 
}]);




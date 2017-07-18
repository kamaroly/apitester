<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Http\Controllers\CanalApiController;

class ResubscriptionController extends CanalApiController
{
	/**
	 * method to do subscriptions
	 * @return 
	 */
    public function resubscription(){

    // WE are capturing information submitted
    // in Json request.
    $requestPayLoad = request()->getContent(); // This is raw json

    // Decode json so that we can access submitted values
    $request = json_decode($requestPayLoad);

	$checkAccountRequest = '<soap:Envelope
	xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
	xmlns:xsd="http://www.w3.org/2001/XMLSchema"
	xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
	<soap:Header/>
	<soap:Body
		xmlns:ns1="MpaymentService">
		<ns1:checkAccount soap:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/">
			<sInXmlData xsi:type="xsd:string">
				<![CDATA[<CheckAccount
				xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
				  <numCard>'.$request->reference_number.'</numCard>
				  <accountRef></accountRef><currency>RWF</currency><operatorName>TIGO</operatorName><country>146</country><eTopupDistributorId></eTopupDistributorId><typeOperation>1</typeOperation></CheckAccount>]]>
			</sInXmlData>
		</ns1:checkAccount>
	</soap:Body>
</soap:Envelope>';

	// 2. PREPARE REQUEST to send to Canal
	$canalRequest = '<soap:Envelope
	xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
	xmlns:xsd="http://www.w3.org/2001/XMLSchema"
	xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
	<soap:Header/>
	<soap:Body
		xmlns:ns1="MpaymentService">
		<ns1:registerAutomaticRenewal soap:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/">
			<sInXmlData xsi:type="xsd:string">
				<![CDATA[<RegisterAutomaticRenewal
				xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"><idBase>Wcgamobile</idBase><numSubscriber>'.$request->reference_number.'</numSubscriber><numContract>1</numContract><accountRef></accountRef><amount>'.$request->amount.'</amount><currency>RWF</currency>
				    <eTopupTransactionId></eTopupTransactionId>
				    <operatorName>TIGO</operatorName><country>146</country>
				    <eTopupDistributorId></eTopupDistributorId>
				    <tokenId>CANAL_TOKEN</tokenId>
				   </RegisterAutomaticRenewal>]]>
			</sInXmlData>
		</ns1:registerAutomaticRenewal>
	</soap:Body>
</soap:Envelope>';


		// Check account
		$accountResponse = $this->call($checkAccountRequest);
	    	$accountResponse = htmlspecialchars_decode($accountResponse);

		// Make sure all HTML entities are well decode
		Log::info($accountResponse);
		 // 4. ANALYSE CANAL RESPONSE

		if (strpos($accountResponse, '<returnCode>0</returnCode>') === FALSE) {
    		// We could not find the return code of 0 which is successful
    		// return failed response to havanao from here
		$code= '400';
		$status= 'error';
		$message ='Unknown error';
 		
		// If the response comes from canal then we will extract message from 
		// Canal error
		if (strpos($accountResponse, 'errorLabel>') !== FALSE)
		{ 
			// This is a canal response extract message
			preg_match_all('/errorLabel>(.*?)\/errorLabel>/s', $accountResponse, $messages);
			$message = $messages[1];
		}

		$checkAccountResponse = [
					'code' 		=> $code,
					'status' 	=> $status,
					'message'   => $message
					];


        	return response($checkAccountResponse, 400)
        		->header('Content-Type', 'application/json');						
		}
		

		// Extract token from account response				
		preg_match_all('/<tokenId>(.*?)<\/tokenId>/s', $accountResponse, $tokens);

		// use correct token from check accounts
		$canalRequest = str_replace('CANAL_TOKEN', $tokens[1][0], $canalRequest);

		// We have check account now send resubscriptiont
		$subscriptionResponse = $this->call($canalRequest);
        
	    	// Make sure all HTML entities are well decoded
	       $subscriptionResponse = htmlspecialchars_decode($subscriptionResponse); 
               Log::info($subscriptionResponse);

	    // 4. ANALYSE CANAL RESPONSE
	    $code = '200';
	    $status = 'OK';
	    $message = 'Thank you for paying '.$request->amount.' to Canal';
	    if (strpos($subscriptionResponse, '<returnCode>0</returnCode>') === FALSE) {
		    $code = '400';
		    $status = 'ERROR';
		    $message = 'ERROR occured while doing transaction';
		 // If the response comes from canal then we will extract message from 
		// Canal error
		if (strpos($subscriptionResponse, '<errorLabel>') !== FALSE)
		{ 
			// This is a canal response extract message
			preg_match_all('/<errorLabel>(.*?)<\/errorLabel>/s', $subscriptionResponse, $messages);
			$message = $messages[1];
		}
	    }
	    
	// 5. Based on Canal response build havanao response and respond to havanao	
	$response = [
				'transactionid' 	=> $request->transactionid,
				'reference_number' 	=> $request->reference_number,
				'code' 				=> $code,
				'status' 			=> $status,
				'account_balance' 	=> $request->amount,
				'customer' 			=> $request->customer,
				'description' 		=> $message,
				'payment_reference' => $request->transactionid
				];


        return response($response, 200)
        		->header('Content-Type', 'application/json');	
    }
}

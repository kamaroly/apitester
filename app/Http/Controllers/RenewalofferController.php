<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Offer;
use Illuminate\Http\Request;
use App\Http\Controllers\CanalApiController ;

class RenewalofferController extends CanalApiController
{
    /*
    method to do resubscription with different offer
     */
    
    public function differentoffer(){


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
				xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"><numCard>'.$request->reference_number.'</numCard><accountRef></accountRef><currency>RWF</currency><operatorName>TIGO</operatorName><country>146</country><eTopupDistributorId></eTopupDistributorId><typeOperation>3</typeOperation></CheckAccount>]]>
			</sInXmlData>
		</ns1:checkAccount>
	</soap:Body>
</soap:Envelope>';

	
	
	$offer = Offer::where('amount',$request->amount)->first();
	// if we cannot find the code mapped with passed amount,
	// fail this request from here don't bother calling 
	// canal api because we don't have offer code
	if (empty($offer)) {
			$response = [
				'transactionid' 	=> $request->transactionid,
				'reference_number' 	=> $request->reference_number,
				'code' 				=> '400',
				'status' 			=> 'ERROR',
				'account_balance' 	=> $request->amount,
				'customer' 			=> $request->customer,
				'description' 		=> 'Invalid amount provided, please provide valid amount',
				'payment_reference' => $request->transactionid
				];


        return response($response, 400)
        		->header('Content-Type', 'application/json');

	}

	$standardrenewalRequest = '<soap:Envelope
	xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
	xmlns:xsd="http://www.w3.org/2001/XMLSchema"
	xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
	<soap:Header/>
	<soap:Body
		xmlns:ns1="MpaymentService">
		<ns1:registerStandardRenewal soap:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/">
			<sInXmlData xsi:type="xsd:string">
				<![CDATA[<RegisterStandardRenewal
				
				xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"><idBase>Wcgamobile</idBase><numSubscriber>CANAL_ID</numSubscriber><numContract>1</numContract><accountRef></accountRef><amount>'.$request->amount.'</amount><currency>RWF</currency><eTopupTransactionId></eTopupTransactionId><operatorName>TIGO</operatorName><country>146</country><eTopupDistributorId></eTopupDistributorId><mainOffer>
				   '.$offer->offer_code.'</mainOffer><duration>1</duration><optionsList></optionsList>
					<tokenId>CANAL_TOKEN</tokenId></RegisterStandardRenewal>]]>
			</sInXmlData>
		</ns1:registerStandardRenewal>
	</soap:Body>
</soap:Envelope>';


		// Check account
	    Log::info($checkAccountRequest);
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
		$standardrenewalRequest = str_replace('CANAL_TOKEN', $tokens[1][0], $standardrenewalRequest);

		// Extract numsubscriber from account response				
		preg_match_all('/<numSubscriber>(.*?)<\/numSubscriber>/s', $accountResponse, 													$numSubscriber);

		// use correct numSubscriber from check accounts
		$standardrenewalRequest = str_replace('CANAL_ID', $numSubscriber[1][0], $standardrenewalRequest);

		// We have check account now send resubscriptiont
	        Log::info($standardrenewalRequest);
		$resubscriptionResponse = $this->call($standardrenewalRequest);
        
	    	// Make sure all HTML entities are well decoded
	       $resubscriptionResponse = htmlspecialchars_decode($resubscriptionResponse); 
               Log::info($resubscriptionResponse);

	    // 4. ANALYSE CANAL RESPONSE
	    $code = '200';
	    $status = 'OK';
	    $message = 'Thank you for paying '.$request->amount.' to Canal';
	    if (strpos($resubscriptionResponse, '<returnCode>0</returnCode>') === FALSE) {
		    $code = '400';
		    $status = 'ERROR';
		    $message = 'ERROR occured while doing transaction';
		 // If the response comes from canal then we will extract message from 
		// Canal error
		if (strpos($resubscriptionResponse, '<errorLabel>') !== FALSE)
		{ 
			// This is a canal response extract message
			preg_match_all('/<errorLabel>(.*?)<\/errorLabel>/s', $resubscriptionResponse, $messages);
			$message = $messages[1][0];
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

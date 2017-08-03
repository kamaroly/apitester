<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class resubscriptionwithdifferentoffer extends Controller
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

	$renewaloffers = '<soap:Envelope
	xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
	xmlns:xsd="http://www.w3.org/2001/XMLSchema"
	xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
	<soap:Header/>
	<soap:Body
		xmlns:ns1="MpaymentService">
		<ns1:verifyRenewalOffers soap:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/">
			<sInXmlData xsi:type="xsd:string">
				<![CDATA[<VerifyRenewalOffers
				
				xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"><idBase>Wcgamobile</idBase><numSubscriber>CANAL_ID</numSubscriber><numContract>1</numContract><accountRef></accountRef><operatorName>TIGO</operatorName><country>146</country><currency>RWF</currency><mainOffer>48M5EVP|EVPDD</mainOffer><duration>1</duration>
					<tokenId>CANAL_TOKEN</tokenId><optionsList></optionsList>
					</VerifyRenewalOffers>]]>
			</sInXmlData>
		</ns1:verifyRenewalOffers>
	</soap:Body>
</soap:Envelope>'

	$standardrenewal = '<soap:Envelope
	xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
	xmlns:xsd="http://www.w3.org/2001/XMLSchema"
	xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
	<soap:Header/>
	<soap:Body
		xmlns:ns1="MpaymentService">
		<ns1:registerStandardRenewal soap:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/">
			<sInXmlData xsi:type="xsd:string">
				<![CDATA[<RegisterStandardRenewal
				
				xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"><idBase>Wcgamobile</idBase><numSubscriber>CANAL_ID</numSubscriber><numContract>1</numContract><accountRef></accountRef><amount>'.$request->amount.'</amount><currency>RWF</currency><eTopupTransactionId></eTopupTransactionId><operatorName>TIGO</operatorName><country>146</country><eTopupDistributorId></eTopupDistributorId><mainOffer>48M5EVP|EVPDD</mainOffer><duration>1</duration><optionsList></optionsList>
					<tokenId>CANAL_TOKEN</tokenId></RegisterStandardRenewal>]]>
			</sInXmlData>
		</ns1:registerStandardRenewal>
	</soap:Body>
</soap:Envelope>'



    }
}

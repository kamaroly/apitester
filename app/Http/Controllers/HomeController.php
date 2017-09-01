<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Payment;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // If we have q in the request, we assume the user is searching 
        // for a specific transactions, therefore let's fetch and 
        // display related results
        switch (request()->has('q')) {
            case true:

                $payment  = new Payment; 

                $payments = $payment->where('subs_account',request()->get('q'))
                                     ->orWhere('PaymentSpTxId',request()->get('q'));

                 
                //$payments = $this->checkmno($payments); 
                // If we don't have data, then consider archive information 
                // before displaying by changing connection to archive
                if ($payments->count() == 0) {
                  $payments = $payment->setConnection('havanao_archive')
                                     ->where('subs_account',request()->get('q'))
                                     ->orWhere('PaymentSpTxId',request()->get('q'));  
                }


                break;
            
            default:
                $payments = new Payment;
                break;
        }
        
        $payments = $this->checkmno($payments);
        $payments = $payments->orderBy('id','DESC')
                             ->paginate(20);

        return view('home',compact('payments'));

    }

    public function checkmno($payments)
    {
        $userMno = Auth::user()->mno; // Get MNO
        $userMno = strtoupper($userMno);
        $userMno = trim($userMno); // Remove any trailing space
        if ($userMno =='MTN') {
            $payments = $payments->where('subs_account','like','25078%');
        }

        if ($userMno =='TIGO') {
            $payments = $payments->where('subs_account','like','25072%');
        }

        if ($userMno =='AIRTEL') {
            $payments = $payments->where('subs_account','like','25073%');
        }
        
        return $payments;
    }
}

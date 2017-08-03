<?php

namespace App\Http\Controllers;

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
                $payments = Payment::where('subs_account',request()->get('q'))
                                     ->orWhere('PaymentSpTxId',request()->get('q'))
                                     ->orderBy('id','DESC')->paginate(5);
                break;
            
            default:
                $payments = Payment::orderBy('id','DESC')->paginate(20);
                break;
        }
        
        return view('home',compact('payments'));
    }
}

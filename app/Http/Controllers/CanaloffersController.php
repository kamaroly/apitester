<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Offer;


class CanaloffersController  extends Controller
{
	public function index () 

	{
	    $offers = Offer::all();

	    return view ('offers.index', compact('offers'));
    }

    public function show ($amount) 
    {
	    $offer = Offer::where('amount',$amount)->first();
	    ($offer->offer_label);
	    return view ('offers.show', compact('offer'));
    }
}

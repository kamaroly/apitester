@extends('app-offer')


@section('content')

	<h1>{{ $offer->offer_label }}</h1>

	<offer>

		{{ $offer->amount }}

	</offer>


@stop
@extends('app-offer')


@section('content')

	<h1>offers</h1>

	@foreach ($offers as $offer)

	<offer>

		<h2>{{ $offer->offer_label }}</h2>
		<div class="amount">{{ $offer->amount }}</div>

	</offer>	

	@endforeach

@stop
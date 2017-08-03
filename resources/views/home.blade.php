@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard
<div class=" pull-right">
        <form class="navbar-form" role="search" action="{{ route('home') }}">
        <div class="input-group">
            <input type="text" class="form-control" placeholder="Search" name="q" id="srch-term">
            
        </div>
        </form>
                </div>

                <div class="panel-body">
                   @include('payments-table')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

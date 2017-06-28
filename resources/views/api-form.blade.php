<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>API TESTER FOR HAVANAO</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

	<style type="text/css" media="screen">
		.bs-example {
    position: relative;
    padding: 45px 15px 15px;
    margin: 0 -15px 15px;
    border-color: #e5e5e5 #eee #eee;
    border-style: solid;
    border-width: 1px 0;
    -webkit-box-shadow: inset 0 3px 6px rgba(0,0,0,.05);
    box-shadow: inset 0 3px 6px rgba(0,0,0,.05);
}
	</style>
</head>
<body>
	<div class="container">
		<div class="row">
			<div class="bs-example" data-example-id="textarea-form-control"> 
			<form class="form" action="{{ route('submit.request') }}" method="POST"> 
			<b>Enter your request URL</b>
			<input type="text" name="url" class="form-control" placeholder="http://yourapikurl">
			
			<b>Enter your request payload</b>
			<textarea class="form-control" rows="10" placeholder="Textarea" name="request">
				

			</textarea> 

@if(isset($apiResponse))
			<code>
				{{ $apiResponse }}
			</code>
@endif
			<button type="submit" class="btn btn-lg btn-success col-12">
				
				SUBMIT
			</button>

			{{ csrf_field() }}
			</form> 
			</div>
		</div>
	</div>
</body>
</html>
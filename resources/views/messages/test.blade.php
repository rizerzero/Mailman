@extends('partials.layout')

@section('content')
	<div class="page-header">
		<h1>Send Test Message</h1>
	</div>


	<div class="col-sm-12">
		<form action="{{ action('MessageController@sendTestMessage'), $message->id) }}" method="POST">
			<div class="form-group">
				<label for="email">Email</label>
				<input type="text" class="form-control" name="email" value="tomfordweb@gmail.com">
			</div>

			<input type="submit" class="btn btn-default">
		</form>
	</div>

@endsection
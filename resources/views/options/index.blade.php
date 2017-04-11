
@extends('partials.layout')

@section('content')
    <div class="page-header">
    	<h1>Edit Options</h1>
    </div>

    <div class="alert alert-info">Due to the bootstrapping and caching services of this application, sometimes it takes about 10 seconds for the changes made here to be seen. <strong>Even after refreshing the page.</strong> When altering these values, you should double check after a brief moment to make sure everything has been saved correctly.</div>
	<form action="{{ action('OptionController@update') }}" method="POST">


	@include('options.partials.mail-data')
	@include('options.partials.api-credentials')

	@include('options.partials.signature')

	<input type="submit" class="btn btn-success" value="Update">
	{{ csrf_field() }}
	</form>
@endsection
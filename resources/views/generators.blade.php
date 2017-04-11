
@extends('partials.layout')

@section('content')
    <div class="page-header">
    	<h1>Generate Test Data</h1>

    	<ul class="nav nav-pills">
		  <li role="presentation"><a href="{{ action('GeneratorController@generate', 'entry-csv') }}">CSV Import Data</a></li>
		</ul>

		<form action="{{ action('GeneratorController@generate', $action) }}" method="GET">
			<div class="form-group">
				<label for="amount">Amount:</label>
				<input type="text" name="amount" class="form-control">
			</div>
		</form>
    </div>

<pre>
{{ $data }}
</pre>
@endsection
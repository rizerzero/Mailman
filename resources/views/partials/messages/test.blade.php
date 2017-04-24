<div class="page-header">
		<h1>Send Test Message</h1>
	</div>
<p><a href="{{ action('MessageController@render', $message->id )}}" target="_blank">Preview</a></p>

<div class="col-sm-12">
	<form action="{{ action('MessageController@sendTestMessage', $message->id) }}" method="POST">
		<div class="form-group">
			<label for="email">Email</label>
			<input type="text" class="form-control" name="email" value="tomfordweb@gmail.com">
		</div>
		{{ csrf_field() }}
		<input type="submit" class="btn btn-default">
	</form>
</div>
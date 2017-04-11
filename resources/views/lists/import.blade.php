
@extends('partials.layout')

@section('content')
	<div class="page-header">
		<h1>Import Entries to <strong>{{ $list->title }}</strong></h1>
	</div>
	<div>
	<p>Please import CSV data with the persons name in the first column, and their email in the second. Use a comma as a delimiter, and a newline character for line endings. (\r\n).</p>
	<h3>Example:</h3>
	<pre>{{ $dummy}}</pre>
	<form action="{{ action('ListController@importEntries', $list->id) }}" method="POST" id="confirm-import">
		<div class="form-group">
			<textarea name="csv_data" class="form-control"></textarea>
		</div>
		<input type="hidden" name="list_id" value="{{ $list->id }}">
		<div class="form-group">
			<input type="submit" class="btn btn-default">
		</div>
		{{ csrf_field() }}
	</form>


@include('partials.modal', ['title' => 'Import Entries'])


	<script>
		$('#confirm-import').submit(function(e) {
			e.preventDefault();

			var el = $(this);
			var modalBody = $('#myModal').find('.modal-body');
			$.ajax({
			  url: el.attr('action'),
			  method: el.attr('method'),
			  data: el.serialize(),
			  beforeSend: function(xhr) {
			  	modalBody.html('');
			  }
			}).done(function(res) {
			  modalBody.html(res);
			  $('#myModal').modal('toggle');
			});

		});
	</script>

@endsection
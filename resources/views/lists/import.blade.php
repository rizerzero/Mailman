@extends('partials.layout')

@section('content')
	<div class="page-header">
		<h1>Import Entries to <strong>{{ $list->title }}</strong></h1>
	</div>
	<div>
	<p>Paste the contents of a CSV file without the headers. The best method is to view the CSV data in Windows Notepad and select the second line down.</p>

	<p>CSV Format should be comma delimited, with a newline character for rows. This is the standard output I believe.</p>

	<p><a href="/sample.csv">Download Example File</a></p>

	<p>All imported data <strong>MUST</strong> be in the order indicated below <br>
		<code>First Name, Last Name, Email, Segment, Company, Phone, Address</code> <br>
	</p>
	<p>Remove the characters: <code>,</code>, <code>"</code>, <code>'</code> from content of columns. Do not remove the commas that are used to seperate columns.</p>

	<p class="help-block">Import data should look similar to this</p>
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
			  $('#myModal').find('.modal-dialog').addClass('modal-lg');
			  $('#myModal').modal('toggle');
			});

		});
	</script>

@endsection
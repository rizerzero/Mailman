
<p>Before saving these entries, please take a minute to examine the imports for any errors. If everything looks fine, click the submit button below.</p>

<table class="table">
	<thead>
		<th>Name</th>
		<th>Email</th>
	</thead>

	<tbody>
		@foreach($data->output() as $entry)
		<tr>
			<td>{{ $entry->name }}</td>
			<td>{{ $entry->email }}</td>
		</tr>
		@endforeach
	</tbody>
</table>




<div class="modal-footer">
	<form action="{{ action('ListController@saveEntries') }}" method="POST">
	<input type="hidden" name="list_id" value="{{ $list->id }}">
		<input type="hidden"  name="csv_json" value="{{ $data->getJson() }}">
		{{ csrf_field() }}
		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		<button type="submit" class="btn btn-primary" id>Import Entries</button>
	</form>
</div>

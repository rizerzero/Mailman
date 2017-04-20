
<p>Before saving these entries, please take a minute to examine the imports for any errors. If everything looks fine, click the submit button below.</p>

<table class="table">
	<thead>
		<th>First Name</th>
		<th>Last Name</th>
		<th>Email</th>
		<th>Segment</th>
		<th>Company</th>
		<th>Phone</th>
		<th>Address</th>
	</thead>

	<tbody>
		@foreach($data->output() as $entry)
		<tr>
			<td>{{ $entry->first_name }}</td>
			<td>{{ $entry->last_name }}</td>
			<td>{{ $entry->email }}</td>
			<td>{{ $entry->segment }}</td>
			<td>{{ $entry->company_name }}</td>
			<td>{{ $entry->phone }}</td>
			<td>{{ $entry->address }}</td>
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

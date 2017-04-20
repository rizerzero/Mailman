<table class="table table-condensed table-hover table-striped">
<thead>
	<tr>
		<th>Name</th>
		<th>Email</th>
		<th>Segment</th>
		<th>Company</th>
		<th>Address</th>
		<th>Phone</th>
		<th>Subscribed</th>
		<th>Deliveries</th>
		<th>Opens</th>
		<th>Clicks</th>
		<th>Spam Complaints</th>
		<th>Pending Messages</th>
	</tr>
</thead>
	@foreach($entries as $entry)
		<tr>
			<td>{{ $entry->first_name . ' ' . $entry->last_name }}</td>
			<td>{{ $entry->email }}</td>
			<td>{{ $entry->segment }}</td>
			<td>{{ $entry->company_name }}</td>
			<td>{{ $entry->address }}</td>
			<td>{{ $entry->phone }}</td>
			<td>{{ ! $entry->clcked_unsubscribe }}</td>
			<td>{{ $entry->stats->sum('deliveries') }}</td>
			<td>{{ $entry->stats->sum('opens') }}</td>
			<td>{{ $entry->stats->sum('clicks') }}</td>
			<td>{{ $entry->stats->sum('spam_complaints')}}</td>
			<td>{{ $entry->mailqueue->where('status', '=', 1)->count() }}</td>
		</tr>
	@endforeach
</table>
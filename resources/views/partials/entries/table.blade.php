<table class="table table-condensed table-hover table-striped">
<thead>
	<tr>
		<th>Name</th>
		<th>Email</th>
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
			<td>{{ $entry->name }}</td>
			<td>{{ $entry->email }}</td>
			<td>{{ ! $entry->clcked_unsubscribe }}</td>
			<td>{{ $entry->deliveries }}</td>
			<td>{{ $entry->opens }}</td>
			<td>{{ $entry->clicks }}</td>
			<td>{{ $entry->spam_complaints }}</td>
			<td>{{ $entry->mailqueue->where('status', '=', 1)->count() }}</td>
		</tr>
	@endforeach
</table>
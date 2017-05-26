

<table class="table table-condensed table-hover">
	<thead>
		<tr class="table-legend">
			<th colspan="6">List</th>
			<th colspan="4">Queue</th>
			<th colspan="3">Stats</th>
		</tr>
		<tr>
			<th>Title</th>
			<th>Status</th>
			<th>Start</th>
			<th>From</th>
			<th>Entries</th>
			<th class="border-right">Messages</th>

			<th>New</th>
			<th>Processing</th>
			<th>Complete</th>
			<th class="border-right">Total</th>

			<th>Clicks</th>
			<th>Opens</th>
			<th>Deliveries</th>

		</tr>

	</thead>

	<tbody>
	@foreach($lists as $list)
		<tr>
			<td><a href="{{ action('ListController@single', $list->id) }}">{{ $list->title }}</a></td>
			<td>{{ $list->getStatus() }}</td>
			<td>{{ $list->campaign_start }}</td>
			<td>{{ $list->from_name . ' - ' . $list->from_email }}</td>
			<td>{{ $list->entries->count() }}</td>
			<td class="border-right">{{ $list->messages->count() }}</td>

			<td>{{ $list->queues()->getNew()->count() }}</td>
			<td>{{ $list->queues()->fromStatus(3)->count() }}</td>
			<td>{{ $list->queues()->fromStatus(2)->count() }}</td>
			<td class="border-right">{{ $list->queues()->count() }}</td>

			<td>{{ $list->getListClicks() }}</td>
			<td>{{ $list->getListOpens() }}</td>
			<td>{{ $list->getListDeliveries() }}</td>
		</tr>
	@endforeach
	</tbody>
</table>

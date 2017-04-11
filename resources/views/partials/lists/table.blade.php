

<table class="table">
	<thead>
		<th>Title</th>
		<th>Entries</th>
		<th>Clicks/Opens/Deliveries</th>
		<th>Status</th>
		<th>Start</th>
		<th>Messages</th>
	</thead>

<tbody>
@foreach($lists as $list)
	<tr>
		<td><a href="{{ action('ListController@single', $list->id) }}">{{ $list->title }}</a></td>
		<td>{{ $list->entries->count() }}</td>
		<td>{{ $list->getListClicks() . '/' . $list->getListOpens() . '/' . $list->getListDeliveries() }}</td>
		<td>{{ $list->getStatus() }}</td>
		<td>{{ $list->campaign_start }}</td>
		<td>{{ $list->messages->count() }}</td>
	</tr>

@endforeach
</tbody>
</table>

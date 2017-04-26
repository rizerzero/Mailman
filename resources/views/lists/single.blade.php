
@extends('partials.layout')

@section('content')
<div class="col-sm-12">
	{!! Breadcrumbs::render('list', $list) !!}

	<div class="page-header">
		<h1>{{ $list->title }} <small>{{ $list->entries->count() }} entries in this list</small></h1>
		<p>{{ $list->description }}</p>
	</div>


	@include('partials.lists.controls')

	<!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Entries</a></li>
    <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Settings</a></li>
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">



    <div role="tabpanel" class="tab-pane active" id="home">

    	<div class="row">
		<div class="col-sm-6">
			<h2>Messages <small><a href="{{ action('MessageController@index', $list->id) }}">View All</a> | <a href="{{ action('MessageController@create', $list->id) }}">Create New</a></small></h2>
			@include('partials.messages.schedule', ['messages' => $list->messages()->orderBy('position', 'asc')->get()])

			<h2>Queue <small><a href="{{ action('ListController@viewQueue', $list->id) }}">View All</a></small></h2>
			@include('partials.queues.table', ['queues' => $list->queues()->take(20)->get() ])
		</div>
		<div class="col-sm-6">

			<h2>Stats <small><a href="{{ action('ListController@singleStats', $list->id) }}" target="_blank">See More</a></small></h2>
			@include('partials.stats.graph', ['hide_controls' => true])
		</div>
		</div>

		<h2>List Entries <small><a href="{{ action('ListController@import', $list->id) }}">Import</a> | <a href="{{ action('ListController@exportListEntries', $list->id) }}">Export</a></small></h2>

		@include('partials.entries.table', ['entries' => $entries ])


		{{ $entries->appends(['find_entry' => $search])->links() }}

    </div>

    <div role="tabpanel" class="tab-pane" id="profile">
    	@include('partials.lists.form')
    </div>



  </div>

</div>

@include('partials.modal', ['title' => 'List Action'])


@endsection

@section('footer-js')
	<script>
var modalBody = $('#myModal').find('.modal-body');
var modalSizeControl = $('#myModal').find('.modal-dialog');
$('#export').click(function(e) {
	e.preventDefault();
	var el = $(this),
		action = el.attr('href');

	$.ajax({
		url: action,
		method: 'GET',
		beforeSend: function(xhr) {
			modalBody.html('');
			modalSizeControl.removeClass('modal-lg');
		}
	}).done(function(res) {
		modalBody.html('<pre>'+res+'</pre>');
		$('#myModal').modal('toggle');
	})
});


$(".disabled a").click(function(event) {
  event.preventDefault();
});
</script>

@endsection
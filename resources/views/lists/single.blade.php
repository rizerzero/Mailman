
@extends('partials.layout')

@section('content')
{!! Breadcrumbs::render('list', $list) !!}
<div class="page-header col-sm-12">
	<h1>{{ $list->title }} <small>{{ $list->entries->count() }} entries in this list</small></h1>
	<p>{{ $list->description }}</p>
</div>

			<div class="form-group">

			<div class="btn-group">
			  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Messages <span class="caret"></span></button>
			  <ul class="dropdown-menu">
			  	<li><a href="{{ action('MessageController@index', $list->id) }}">View Messages for this list</a></li>
			    <li class="{{ ($list->isActive() ) ? 'disabled' : null }}" ><a href="{{ action('MessageController@create', $list->id) }}">Create Message for this list</a></li>
			  </ul>
			</div>
			<div class="btn-group">
			  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Entries <span class="caret"></span></button>
			  <ul class="dropdown-menu">
			    <li  class="{{ ($list->isActive() ) ? 'disabled' : null }}"><a href="{{ action('ListController@import', $list->id) }}">Import Entries for this list</a></li>
			    <li><a href="{{ action('ListController@exportListEntries', $list->id) }}" id="export">Export Entries for this list</a></li>
			  </ul>
			</div>
			<div class="btn-group">
				<a href="{{ action('ListController@viewQueue', $list->id) }}" id="queue-status" class="btn btn-default">Queue Status</a>
			</div>
			<div class="btn-group">
			  <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">List Controls <span class="caret"></span></button>
			  <ul class="dropdown-menu">
			  	<li class="{{ ($list->isActive() ) ? 'disabled' : null }}"><a href="{{ action('ListController@startCampaign', $list->id) }}">Start Campaign</a></li>

			  	<li>
			  		@if(! $list->isPaused() && $list->isActive())
			  		<a href="{{ action('ListController@pauseCampaign', $list->id) }}">Pause Campaign</a></li>
			  		@else
					<a href="{{ action('ListController@resumeCampaign', $list->id) }}">Resume Campaign</a>
			  		@endif
			  	</li>

			    <li class="divider"></li>
			    <li class="{{ ($list->isActive() ) ? 'disabled' : null }}"><a href="{{ action('ListController@clearListEntries', $list->id) }}">Clear List</a></li>
				<li class="{{ ($list->isActive() ) ? 'disabled' : null }}"><a href="{{ action('ListController@deleteList', $list->id) }}">Delete List</a></li>
			  </ul>
			</div>


		</div>


  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Entries</a></li>
    <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Settings</a></li>
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="home">
		<form action="{{ action('ListController@single', $list->id) }}" method="GET">
			<div class="form-group">
				<label for="find_entry">Search Entries: </label>
				<input type="text" name="find_entry" class="form-control">
			</div>
		</form>
    	@if($list->entries->count() == 0 )
			No Entries Exist Yet
		@else
			@include('partials.entries.table', ['entries' => $entries ])
		@endif

		{{ $entries->appends(['find_entry' => $search])->links() }}

    </div>
    <div role="tabpanel" class="tab-pane" id="profile">
    	@include('partials.lists.form')
    </div>
  </div>

</div>

@include('partials.modal', ['title' => 'List Action'])

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

$('#queue-status').click(function(e) {
	e.preventDefault();
	var el = $(this),
		action = el.attr('href');

	$.ajax({
		url: action,
		method: 'GET',
		beforeSend: function(xhr) {
			modalBody.html('');
			modalSizeControl.addClass('modal-lg');
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
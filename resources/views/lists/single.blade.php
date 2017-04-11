
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
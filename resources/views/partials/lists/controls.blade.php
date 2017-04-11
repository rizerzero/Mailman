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
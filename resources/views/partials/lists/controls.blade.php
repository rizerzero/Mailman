<div class="form-group">



	<div class="btn-group">
	  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">List Controls <span class="caret"></span></button>
	  <ul class="dropdown-menu">
	  	<li class="{{ ($list->isActive() || $list->isPaused() ) ? 'disabled' : null }}"></li>
		<li>
			@if($list->isActive())
				<a href="{{ action('ListController@pauseCampaign', $list->id) }}">Pause Campaign</a>
			@elseif($list->isPaused())
				<a href="{{ action('ListController@resumeCampaign', $list->id) }}">Resume Campaign</a>
			@else
				<a href="{{ action('ListController@startCampaign', $list->id) }}">Start Campaign</a>
			@endif
		</li>

		@if(! $list->isActive())
			<li class="divider"></li>
		    <li><a href="{{ action('ListController@clearListEntries', $list->id) }}">Clear List</a></li>
			<li><a href="{{ action('ListController@deleteList', $list->id) }}">Delete List</a></li>
		@endif

	  </ul>
	</div>


</div>
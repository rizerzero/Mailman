<div class="well">
	<h2>Mail Service API Credentials</h2>
		<div>

		  <!-- Nav tabs -->
		  <ul class="nav nav-tabs" role="tablist">
		    <li role="presentation" class="active"><a href="#mailgun" aria-controls="mailgun" role="tab" data-toggle="tab">Mailgun</a></li>
{{-- 		    <li role="presentation"><a href="#mandrill" aria-controls="mandrill" role="tab" data-toggle="tab">Mandrill</a></li>
		    <li role="presentation"><a href="#ses" aria-controls="ses" role="tab" data-toggle="tab">SES</a></li> --}}
		    <li role="presentation"><a href="#sparkpost" aria-controls="sparkpost" role="tab" data-toggle="tab">SparkPost</a></li>
		  </ul>

		  <!-- Tab panes -->
		  <div class="tab-content">
		  	{{-- Mailgun --}}
		    <div role="tabpanel" class="tab-pane active" id="mailgun">
			    <div class="form-group">
					<label for="services[mailgun][domain]">Domain:</label>
					<input type="text" name="services[mailgun][domain]" class="form-control" value="{{ config('services.mailgun.domain') }}">
				</div>

				<div class="form-group">
					<label for="services[mailgun][secret]">Secret</label>
					<input type="text" name="services[mailgun][secret]" class="form-control" value="{{ config('services.mailgun.secret') }}">
				</div>


		    </div>


{{-- 		    <div role="tabpanel" class="tab-pane" id="mandrill">Mandrill</div>


		    <div role="tabpanel" class="tab-pane" id="ses">
		    	<div class="form-group">
					<label for="services[ses][key]">Key:</label>
					<input type="text" name="services[ses][key]" class="form-control" value="{{ config('services.ses.key') }}">
				</div>

				<div class="form-group">
					<label for="services[ses][secret]">Secret</label>
					<input type="text" name="services[ses][secret]" class="form-control" value="{{ config('services.ses.secret') }}">
				</div>

				<div class="form-group">
					<label for="services[ses][region]">Region:</label>
					<input type="text" name="services[ses][region]" class="form-control" value="{{ config('services.ses.region') }}">
				</div>

		    </div> --}}

		    {{-- Sparkpost --}}
		    <div role="tabpanel" class="tab-pane" id="sparkpost">
				<div class="form-group">
					<label for="services[sparkpost][secret]">Secret:</label>
					<input type="text" name="services[sparkpost][secret]" class="form-control" value="{{ config('services.sparkpost.secret') }}">
				</div>
		    </div>
		  </div>

		</div>
	</div>
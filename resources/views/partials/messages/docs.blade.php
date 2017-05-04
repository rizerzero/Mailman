<h1>Template Variables</h1>
			<h2>Usage</h2>

			<p>In order to display a variable in the message, you must use double curly brackets before and after like so <code>&#123;&#123; $model->property &#125;&#125;</code>.</p>

			<p>Examples:</p>

			<p><code>&#123;&#123; $entry->name &#125;&#125;</code></p>
			<p><code>&#123;&#123; $list->title &#125;&#125;</code></p>
			<p><code>&#123;&#123; $mailmessage->name &#125;&#125;</code></p>
	    	<h2>Models and Properties</h2>
	    	<ul>
		    	<li>$entry
		    		<ul>
		    			<li><strong>name</strong> Name of the recipeint</li>
		    			<li><strong>email</strong> Email of the recipeint</li>
		    		</ul>
		    	</li>
		    	<li>$list
		    		<ul>
		    			<li><strong>title</strong> Title of the list</li>
		    			<li><strong>description</strong> Description of the list</li>
		    		</ul>
		    	</li>
		    	<li>$mailmessage
					<ul>
						<li><strong>name</strong></li>
						<li><strong>content</strong></li>
						<li><strong>subject</strong></li>
					</ul>
		    	</li>

		    </ul>

		    <h2>Template Tags</h2>

		    <h2>Features with Image</h2>

		    <p><code>&#64;include('emails.partials.features-left-radio-right')</code></p>

			<p><em>The above will output</em></p>
			<div class="well">
			@include('emails.partials.features-left-radio-right')
			</div>

			<h2>CTA Button</h2>
			<p>Place a centered button that links to a page</p>
			<p><strong>Arguments</strong></p>
			<pre>
				$alignment = Table column alignment attribute. - https://www.w3schools.com/tags/att_td_align.asp
				$link = The link to navigate to
				$text = The text to use for the button
			</pre>
			<p><strong>Usage</strong></p>
			<p><code>&#64;include('emails.partials.cta-button', ['alignment' => 'center', 'link' => 'http://example.com', 'text' => 'Badass Example'])</code></p>

			<p>I can't get an example of this to look correctly on the actual website, but it works...make sure to double check in the message preview.</p>
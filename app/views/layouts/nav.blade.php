<nav class="navbar navbar-default navbar-fixed-top navbar-inverse" role="navigation">
	<!-- Brand and toggle get grouped for better mobile display -->
	<div class="navbar-header">
		<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>
		{{ link_to_route('home', 'Web Visual Voice Mail', null, ['class' => 'navbar-brand']) }}
	</div>

	<!-- Collect the nav links, forms, and other content for toggling -->
	<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
		@unless (Auth::check() or Session::get('access_token') )
			<p class="navbar-text navbar-right">You are not signed in. {{ link_to_action('SessionsController@create', "Login") }}</p>
			}
		@else
			<p class="navbar-text navbar-right">{{ (isset($ctn_id)) ? format_phone($ctn_id) : "" }} {{ link_to_action('SessionsController@destroy', "Logout" ) }}</p>
		@endunless
	</div>
	<!-- /.navbar-collapse -->


</nav>

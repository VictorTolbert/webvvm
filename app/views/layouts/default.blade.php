<!DOCTYPE html>
<!--[if lt IE 7]>
<html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>
<html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>
<html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js"> <!--<![endif]-->
<head>
	<meta charset="UTF-8">
	<title>{{$page_title or 'Web Visual Voicemail'}}</title>
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css">
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap-theme.min.css">
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">
	<link rel="stylesheet" href="/assets/css/animate.css">
	<link rel="stylesheet" href="/assets/css/app.css">
	<!-- App -->
	<script>
		window.App = window.App || {};
		App.siteURL = '{{ URL::to("/") }}';
		App.currentURL = '{{ URL::current() }}';
		App.fullURL = '{{ URL::full() }}';
		App.apiURL = '{{ URL::to("api") }}';
		App.assetURL = '{{ URL::to("assets") }}';
	</script>
	<script src="/assets/js/wow.min.js"></script>
	<script>new WOW().init(); </script>
	@yield('head')
</head>
<body>
@include('layouts.nav')

<div class="container">
	@if (Session::get('flash_message'))
	<div class="alert alert-info alert-dismissable">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		{{ Session::get('flash_message')}}
	</div>
	@endif
	@yield('content')
</div>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
@yield('footer')
</body>
</html>

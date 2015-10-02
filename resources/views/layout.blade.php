<!DOCTYPE html>
<html lang="en">
	<head>
		@section('head')
			<meta charset="utf-8">
			<meta http-equiv="X-UA-Compatible" content="IE=edge">
			<meta name="viewport" content="width=device-width, initial-scale=1">
			<meta name="description" content="">
			<meta name="author" content="">
			<link rel="icon" href="/favicon.ico">

			<title>kTube - @yield('title', 'Home')</title>

			<link href="//fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
			<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet" type="text/css">

			@section('style')
				<style>
					body {
						padding-top: 50px;
					}
					.video_thumbnail {
						width: 100px;
						height: 100px;
						display: inline-block;
					}
				</style>
			@show

			<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
			<!--[if lt IE 9]>
				<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
				<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
			<![endif]-->
		@show
	</head>
	<body>
		@section('body')
			<nav class="navbar navbar-inverse navbar-fixed-top">
				<div class="container-fluid">
					<div class="navbar-header">
						<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
							<span class="sr-only">Toggle navigation</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
						<a class="navbar-brand" href="/">kTube</a>
					</div>
					<div id="navbar" class="navbar-collapse collapse">
						<ul class="nav navbar-nav navbar-right">
							<li><a href="#">Dashboard</a></li>
							<li><a href="#">Settings</a></li>
							<li><a href="/profile">Profile</a></li>
							<li><a href="#">Help</a></li>
						</ul>
						<form class="navbar-form navbar-right">
							<input type="text" class="form-control" placeholder="Search...">
						</form>
		                @if (Auth::check())
		                    <span class="navbar-text navbar-right navbar-link">
		                    	<a class="" href="#">{{ Auth::user()->name }}</a>
		                    </span>
		                @endif
					</div>
				</div>
			</nav>

			<div class="container-fluid">
				@yield('content')
			</div>
		@show

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
	</body>
</html>

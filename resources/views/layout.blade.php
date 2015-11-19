<!DOCTYPE html>
<html lang="en" ng-app="ktube" id="ng-app">
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
		<link href="<< asset('media/style/app.css') >>" rel="stylesheet" type="text/css">
		<link href="<< asset('media/style/loading.css') >>" rel="stylesheet" type="text/css">

		@yield('headers')

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
						<a class="" href="#"><< Auth::user()->name >></a>
					</span>
					@endif
				</div>
			</div>
		</nav>

		<div id="container" class="container-fluid" ng-cloak>
			@yield('content')
		</div>

		<div class="cssload-triangles">
			<div class="cssload-tri cssload-invert"></div>
			<div class="cssload-tri cssload-invert"></div>
			<div class="cssload-tri"></div>
			<div class="cssload-tri cssload-invert"></div>
			<div class="cssload-tri cssload-invert"></div>
			<div class="cssload-tri"></div>
			<div class="cssload-tri cssload-invert"></div>
			<div class="cssload-tri"></div>
			<div class="cssload-tri cssload-invert"></div>
		</div>
	@show

		<script src="<< asset('media/js/dependencies.js') >>"></script>
		<script type="text/javascript">
			window.name = 'NG_DEFER_BOOTSTRAP! ' + window.name;
			Dependencies
				.add("MomentJS", "https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.6/moment.js")
				.add("jQuery", "https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.js")
				.add("Bootstrap", "https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.js", ["jQuery"])
				.add("Angular", "https://ajax.googleapis.com/ajax/libs/angularjs/1.3.12/angular.js")
				.add("Angular-InfiniteScroll", "https://cdnjs.cloudflare.com/ajax/libs/ngInfiniteScroll/1.2.1/ng-infinite-scroll.js", ["jQuery", "Angular"])
				.add("Angular-Bootstrap", "https://cdnjs.cloudflare.com/ajax/libs/angular-ui-bootstrap/0.14.2/ui-bootstrap.js", ["Angular", "Bootstrap"])
				.add("App", "<< asset('media/js/app.js') >>", ["Angular", "MomentJS", "Angular-InfiniteScroll"])
				.add("Script", "<< asset('media/js/script.js') >>", ["jQuery", "MomentJS"])
				.init();
		</script>
	</body>
</html>

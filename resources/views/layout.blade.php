<!DOCTYPE html>
<html lang="en" id="root">
	<head>
	@section('head')
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="">
		<meta name="author" content="">
		<link rel="icon" href="/favicon.ico">

		<title v-if="!title">kTube - @yield('title')</title>
		<template v-else>
			<title>kTube - {{ title }}</title>
		</template>

		@section('styles')
			<link href="<< elixir('css/main.css') >>" rel="stylesheet" type="text/css">
			<link href="<< elixir('css/app.css') >>" rel="stylesheet" type="text/css">
		@show

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
						<li><a v-link="'/'">Dashboard</a></li>
						<li><a v-link="'/videos'">Videos</a></li>
						<li><a href="#">Settings</a></li>
						<li><a href="#">Profile</a></li>
						<li><a href="#">Help</a></li>
						<li><a href="/auth/logout">Logout</a></li>
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

		<div id="container" class="container-fluid" :class="{'loading': loading}" v-cloak>
			<router-view></router-view>
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

		<script src="<< elixir('js/vendor.js') >>"></script>
		<script src="<< elixir('js/app.js') >>"></script>
	</body>
</html>

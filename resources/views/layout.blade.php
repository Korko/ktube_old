<html>
<head>
	<title>kTube - @yield('title', 'Home')</title>
	@section('head')

	@show
</head>
<body>
	<div id="navbar">

		<span class="user">{{ Auth::user()->name }}</span>
	</div>

	@section('body')

	@show
</body>
</html>

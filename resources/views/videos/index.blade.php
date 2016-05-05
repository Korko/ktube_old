@extends('layout')

@section('title')
Videos
@endsection

@section('content')
<div id="video-list" class="row">
	<ul>
		<li v-for="video in videos">
			<video></video>
		</li>
	</ul>

	<nav>
		<button type="button" class="btn btn-primary btn-lg">
			<span class="glyphicon" aria-hidden="true"></span> More
		</button>
	</nav>
</div>
@endsection

@extends('layout')

@section('head')
	@parent
	<link href="/media/style/cover.css" rel="stylesheet" type="text/css">
@endsection

@section('content')
	<div class="row">
		<ul>
		@foreach($videos as $video)
			<li>
				<div class="row">
					<div class="col-md-2">.col-md-4</div>
					<div class="col-md-10">
						<h2>{{ $video->name }}</h2>
						<span class="author small em">by {{ $video->channel->name }}</span>
						<span>{{ $video->published_at->diffForHumans() }}</span>
						<span>in {{ $video->channel->site->name }}</span>
					</div>
				</div>
			</li>
		@endforeach
		</ul>

		<nav>{!! $videos->render() !!}</nav>
	</div>
@endsection

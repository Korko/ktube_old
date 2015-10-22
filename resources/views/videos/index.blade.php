@extends('layout')

@section('content')
	<div class="row">
		<ul>
		@foreach($videos as $video)
			<li>
				<div class="row">
					<div class="video-thumbnail">
						<img src="{{ $video->thumbnail }}" />
					</div>
					<div class="video-data">
						<h3><a href="/video/{{ Hashids::encode($video->id) }}">{{ $video->name }}</a></h3>
						<span class="author small em">by {{ $video->channel->name }}</span>
						<span class="small em">{{ $video->published_at->diffForHumans() }} in {{ $video->channel->site->name }}</span>
					</div>
				</div>
			</li>
		@endforeach
		</ul>

		<nav>{!! $videos->render() !!}</nav>
	</div>
@endsection

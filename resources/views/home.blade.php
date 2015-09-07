@extends('layout')

@section('content')
	<div class="row">
		<ul>
		@foreach($videos as $video)
			<li>
				<div class="row">
					<div class="col-md-2">
						@if($video->thumbnail === null)
							<img src="http://img.youtube.com/vi/79818649422324816361/default.jpg" />
						@else
							<img src="{{ $video->thumbnail }}" />
						@endif
					</div>
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

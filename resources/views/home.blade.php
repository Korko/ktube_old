@extends('layout')

<ul>
@foreach($videos as $video)
	<li>{{ $video->name }} (#{{ $video->video_id }}) - {{ $video->published_at->diffForHumans() }}</li>
@endforeach
</ul>

{!! $videos->render() !!}
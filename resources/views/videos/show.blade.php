@extends('layout')

@section('content')
	@if ($video->channel->site->provider === 'google')
		<iframe src="//www.youtube.com/embed/<< $video->video_id >>?origin=http://ktube.yt" width="1024" height="768" frameborder="0" allowfullscreen></iframe>
	@elseif ($video->channel->site->provider === 'dailymotion')
		<iframe src="//www.dailymotion.com/embed/video/<< $video->video_id >>?html5=1" width="1024" height="768" frameborder="0" allowfullscreen></iframe>
	@endif
@endsection


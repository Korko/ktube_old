@extends('layout')

<ul>
@foreach($channels as $channel)
	<li>{{ $channel->name }} (#{{ $channel->channel_id }})</li>
@endforeach
</ul>
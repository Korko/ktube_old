@extends('layout')

@section('content')
<div class="row" ng-controller="VideoController">
	<ul>
		<li ng-repeat="video in videos.data">
			<div class="row">
				<div class="col-md-2">
					<img ng-src="{{ video.thumbnail }}" />
				</div>
				<div class="col-md-10">
					<h2><a ng-href="/video/{{ video.hash }}">{{ video.name }}</a></h2>
					<span class="author small em">by {{ video.channel.name }}</span>
					<span data-date="{{ video.published_at }}" class="timer">{{ video.published_at | fromNow }}</span>
					<span>in {{ video.channel.site.name }}</span>
				</div>
			</div>
		</li>
	</ul>

	<nav>
		<ul class="pager" ng-model="videos.current_page">
			<li ng-class="videos.current_page <= 1 ? 'disabled' : ''"><a href="#"><span aria-hidden="true">&larr;</span> Newer</a></li>
			<li ng-class="!videos.has_more ? 'disabled' : ''"><a href="#">Older <span aria-hidden="true">&rarr;</span></a></li>
		</ul>
	</nav>
</div>
@endsection

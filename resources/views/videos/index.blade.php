@extends('layout')

@section('content')
<div class="row" ng-controller="VideoController">
	<ul infinite-scroll="videoLoader.nextPage()" infinite-scroll-distance="3" infinite-scroll-disabled="!videoLoader.hasNextPage()">
		<li ng-repeat="video in videoLoader.videos">
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
		<button type="button" class="btn btn-primary btn-lg" aria-label="Left Align" ng-click="videoLoader.nextPage()" ng-disabled="videoLoader.isLocked() || !videoLoader.hasNextPage()">
			<span ng-class="videoLoader.isLocked() ? 'glyphicon-refresh glyphicon-refresh-animate' : 'glyphicon-triangle-bottom'" class="glyphicon" aria-hidden="true"></span> More
		</button>
	</nav>
</div>
@endsection

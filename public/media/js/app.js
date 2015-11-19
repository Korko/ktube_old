var myApp = angular.module('ktube', ['infinite-scroll']);

myApp.value('appName', 'kTube');

myApp.controller('VideoController', function($scope, VideoLoader) {

	$scope.init = function(url) {
		$scope.videoLoader = new VideoLoader(url);
	};

});

myApp.factory('VideoLoader', function($http) {

	var VideoLoader = function(url) {

		this.videos = [];
		this.has_more = false;
		this.last_video = null;
		this.lock = false;
		this.url = url;
		this.$container = $('#container');

		this.$container.addClass('loading');
		this.loadPage(this.current_page);

	};

	VideoLoader.prototype.isLocked = function() {
		return this.lock;
	};

	VideoLoader.prototype.getVideos = function() {
		return this.videos;
	};

	VideoLoader.prototype.loadPage = function(last_video) {

		if (this.lock) return;
		this.lock = true;

		$http.get(this.url + '?last=' + (last_video || '')).success(function ($data) {

			this.videos = this.videos.concat($data.data);
			this.has_more = $data.has_more;
			this.last_video = this.videos[this.videos.length - 1].hash;
			this.$container.removeClass('loading');
			this.lock = false;

		}.bind(this));

	};

	VideoLoader.prototype.hasNextPage = function() {
		return this.has_more;
	};

	VideoLoader.prototype.nextPage = function() {
		this.hasNextPage() && this.loadPage(this.last_video);
	};

	return VideoLoader;

});

myApp.filter('fromNow', function() {
	return function(dateString) {
		return moment(dateString, 'YYYY-MM-DD HH:mm:ss').fromNow()
	};
});

angular.element(document).ready(function() {
	angular.resumeBootstrap();
});

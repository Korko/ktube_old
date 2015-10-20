var myApp = angular.module('ktube', ['infinite-scroll']);

myApp.controller('VideoController', function($scope, VideoLoader) {

	$scope.videoLoader = new VideoLoader();

});

myApp.factory('VideoLoader', function($http) {

	var VideoLoader = function() {

		this.videos = [];
		this.has_more = false;
		this.current_page = 1;
		this.lock = false;
		this.$container = $('#container');

		this.$container.addClass('loading');
		this.loadPage(this.current_page);

	};

	VideoLoader.prototype.isLocked = function() {
		return this.lock;
	};

	VideoLoader.prototype.getVideos = function(page) {
		return this.videos;
	};

	VideoLoader.prototype.loadPage = function(page) {

		if (this.lock) return;
		this.lock = true;

		$http.get('/videos/all?page=' + parseInt(page, 10)).success(function ($data) {

			this.videos = this.videos.concat($data.data);
			this.has_more = $data.has_more;
			this.current_page = $data.current_page;
			this.$container.removeClass('loading');
			this.lock = false;

		}.bind(this));

	};

	VideoLoader.prototype.hasPrevPage = function() {
		return this.current_page > 1;
	};

	VideoLoader.prototype.prevPage = function() {
		this.hasPrevPage() && this.loadPage(this.current_page - 1);
	};

	VideoLoader.prototype.hasNextPage = function() {
		return this.has_more;
	};

	VideoLoader.prototype.nextPage = function() {
		this.hasNextPage() && this.loadPage(this.current_page + 1);
	};

	return VideoLoader;

});

myApp.filter('fromNow', function() {
	return function(dateString) {
		return moment(dateString, 'YYYY-MM-DD HH:mm:ss').fromNow()
	};
});
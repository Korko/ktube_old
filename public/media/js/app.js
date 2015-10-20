var myApp = angular.module('ktube',[]);

myApp.controller('VideoController', ['$scope', '$http', function($scope, $http) {
	$scope.videos = [];
	$('#container').addClass('loading');
	$http.get('/videos/all').success(function ($data) {
		$scope.videos = $data;
		$('#container').removeClass('loading');
	});
}]);

myApp.filter('fromNow', function() {
	return function(dateString) {
		return moment(dateString, 'YYYY-MM-DD HH:mm:ss').fromNow()
	};
});
Vue.config.devtools = true;

var VideosComponent = require('../vue/Videos.vue');

Vue.filter('fromNow', function (value) {
	return moment(value, 'YYYY-MM-DD HH:mm:ss').fromNow();
});

var router = new VueRouter();
router.map({
        '/videos': {
                component: function (resolve) {
                        resolve(VideosComponent);
                }
        }
});

var App = Vue.extend({
	data() {
		return {
			loading: false
		}
	}
});
router.start(App, '#root');

Vue.config.devtools = true;

Vue.filter('fromNow', function (value) {
	return moment(value, 'YYYY-MM-DD HH:mm:ss').fromNow();
});

var Foo = Vue.extend({
    template: '<p>This is foo!</p>'
});

var router = new VueRouter();
router.map({
	'/': {
		component: Foo
	},
	'/videos': {
		component: function (resolve) {
			resolve(require('../vue/Videos.vue'));
		}
	}
});

var App = Vue.extend({
	data() {
		return {
			loading: false,
			title: "Home",
			store: {}
		}
	}
});
router.start(App, '#root');

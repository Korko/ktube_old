import Vue from 'vue';
import { sync } from 'vuex-router-sync';
import App from './app.js';
import router from './routes.js';
import store from './store.js';

Vue.config.devtools = true;

Vue.filter('fromNow', function (value) {
	return moment(value, 'YYYY-MM-DD HH:mm:ss').fromNow();
});

sync(store, router);
router.start(App, '#root');

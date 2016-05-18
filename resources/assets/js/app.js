import Vue from 'vue';
import store from './store.js';

export default Vue.extend({
        data: function() {
                return {
                        loading: false,
                        title: "Home"
                }
        },
	store: store
});

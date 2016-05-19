import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex)

export default new Vuex.Store({
	state : {
		videos: [],
		hasMore: true
	},
	mutations: {
		APPEND: function (state, videos, hasMore) {
			Vue.set(state, 'videos', state.videos.concat(videos));
			state.hasMore = (hasMore === undefined) ? state.hasMore : hasMore;
		},
		PREPEND: function (state, videos) {
			Vue.set(state, 'videos', videos.concat(state.videos));
		}
	},
	strict: true
})

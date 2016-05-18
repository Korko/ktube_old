import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex)

export default new Vuex.Store({
	state : {
		videos: videos || []
	},
	mutations: {
		APPEND: function (state, videos) {
			state.videos = state.videos.concat(videos);
		},
		PREPEND: function (state, videos) {
			state.videos = videos.concat(state.videos);
		}
	},
	strict: true
})

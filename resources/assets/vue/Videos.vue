<template>
        <div class="row" v-if="!videos.length">
                Lors de la première connexion, il n'y a encore rien, cela va venir, le temps de récupérer vos abonnements et vidéos associées.
        </div>

        <div id="new-videos-list" class="row" v-if="newVideos.length">
                <a v-on:click="appendNewVideos">{{ newVideos.length }} nouvelles vidéos</a>
        </div>

        <div id="video-list" class="row" v-if="videos.length">

                <ul>
                        <li v-for="video in videos">
                                <div class="row">
                                        <div class="video-thumbnail">
                                                <img :src="video.thumbnail" />
                                        </div>
                                        <div class="video-data">
                                                <h3><a href="/video/{{ video.hash }}">{{ video.name }}</a></h3>
                                                <span class="author small em">by {{ video.channel.name }}</span>
                                                <span data-date="{{ video.published_at }}" class="small em timer">{{ video.published_at | fromNow }}</span>
                                                <span class="small em">in {{ video.channel.site.name }}</span>
                                        </div>
                                </div>
                        </li>
                </ul>

                <nav>
                        <button type="button" class="btn btn-primary btn-lg" :disabled="hasMore ? false : true" v-on:click="nextPage">
                                <span class="glyphicon" :class="{'glyphicon-refresh': lock, 'glyphicon-refresh-animate': lock, 'glyphicon-triangle-bottom': !lock}" aria-hidden="true"></span> More
                        </button>
                </nav>
        </div>
</template>

<script>
export default {
        activate: function() {
                // Change the title
                this.$root.title = "Videos";
        },
	vuex: {
               	getters: {
                       	videos: function(state) {
                       	        return state.videos;
                       	},
			hasMore: function(state) {
				return state.hasMore;
			}
               	},
               	actions: {
                       	appendVideos: function(store, videos, hasMore) {
                               	store.dispatch('APPEND', videos, hasMore);
                       	},
                       	prependVideos: function(store, videos) {
                       	        store.dispatch('PREPEND', videos);
                       	}
               	}
        },
        created: function() {
        	// Every 5 minutes, ask the server if there's new messages
                setInterval(function() {
                       this.checkNewVideos();
                }.bind(this), 5 * 60 * 1000);

                this.$data = {
                        lock: false,
                        newVideos: []
                };
        },
	computed: {
		lastVideo: function() {
			return this.videos.length ? this.videos[this.videos.length - 1].hash : null;
		},
		firstVideo: function() {
			return this.newVideos.length ? this.newVideos[0].hash : (this.videos.length ? this.videos[0].hash : null); 
		}
	},
	methods: {
                nextPage: function() {
               	        // If we are alreay getting next page, don't request more
                       	if (this.lock) return;

                       	// Lock for further calls while we handle this one
                       	this.lock = true;

                       	$.ajax('/videos/all?last='+this.lastVideo).success(function ($data) {
                               	// Add videos in the list
                               	this.appendVideos($data.videos, $data.hasMore);

                               	// Allow further calls
                               	this.lock = false;
                       	}.bind(this));
               	},
               	checkNewVideos: function() {
                       	$.ajax('/videos/all?first='+this.firstVideo).success(function ($data) {
                               	// Add the new videos at the very first in the list of new videos
                               	this.newVideos = $data.videos.concat(this.newVideos);
                       	}.bind(this));
               	},
		appendNewVideos: function() {
			this.prependVideos(this.newVideos);
			this.newVideos = [];
		}
        }
};
</script>

<style>
        .video-thumbnail {
                float: left;
                width: 350px;
        }

        .video-data {
                float: left;
                clear: right;
        }

        .video_thumbnail {
                width: 100px;
                height: 100px;
                display: inline-block;
        }
</style>


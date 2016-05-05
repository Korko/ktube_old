<template>
        <div class="row" v-if="!videos.length">
                Lors de la première connexion, il n'y a encore rien, cela va venir, le temps de récupérer vos abonnements et vidéos associées.
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
                        <button type="button" class="btn btn-primary btn-lg" :disabled="has_more ? false : true" v-on:click="nextPage">
                                <span class="glyphicon" :class="{'glyphicon-refresh': lock, 'glyphicon-refresh-animate': lock, 'glyphicon-triangle-bottom': !lock}" aria-hidden="true"></span> More
                        </button>
                </nav>
        </div>
</template>

<script>
export default {
	methods: {
		loadPage: function(last_video) {
			if (this.lock) return;
			this.lock = true;

			$.ajax('/videos/all?last=' + (last_video || '')).success(function ($data) {

				this.$root.loading = false;
				this.videos = this.videos.concat($data.data);
				this.has_more = $data.has_more;
				this.last_video = this.videos.length ? this.videos[this.videos.length - 1].hash : null;
				this.lock = false;

			}.bind(this));
		},
		nextPage : function() {
			this.has_more && this.loadPage(this.last_video);
		}
	},
        data () {
		this.$root.loading = true;
		this.loadPage();
                return {
			has_more: false,
			last_video: null,
			lock: false,
                        videos: []
                };
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


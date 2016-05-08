<template>
        <div class="row" v-if="!videos.length">
                Lors de la première connexion, il n'y a encore rien, cela va venir, le temps de récupérer vos abonnements et vidéos associées.
        </div>

        <div id="new-videos-list" class="row" v-if="new_videos.length">
                <a v-click="appendNewVideos">{{ new_videos.length }} nouvelles vidéos</a>
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
        keepAlive: true, // Seems useless....
        canReuse: true, // Seems useless....
        route: {
                activate: function() {
                        // Considere we are globally loading when displaying this page
                        this.$root.loading = true;

                        // Change the title
                        this.$root.title = "Videos";
                },
                data: function() {
                        setTimeout(function() {
                                this.getMoreVideos();
                        }.bind(this), 10);

                        // Every 5 minutes, ask the server if there's new messages
                        setInterval(function() {
                                this.checkNewVideos();
                        }.bind(this), 5 * 60 * 1000);

                        return {
                                has_more: false,
                                lock: false,
                                new_videos: [],
                                videos: []
                        };
                }
        },
        methods: {
                getMoreVideos: function(last_video) {
                        // If we are alreay getting next page, don't request more
                        if (this.lock) return;

                        // Lock for further calls while we handle this one
                        this.lock = true;

                        $.ajax('/videos/all?last=' + (last_video || '')).success(function ($data) {
                                // In case of the first call of the page, the root will be loading do stop it
                                this.$root.loading = false;

                                // Add the new videos at the end
                                this.videos = this.videos.concat($data.data);

                                // The server will say if there's more videos after those
                                this.has_more = $data.has_more;

                                // Allow further calls
                                this.lock = false;
                        }.bind(this));
                },
                nextPage : function() {
                        // Determine what is the last video listed to get those after this one
                        var last_video = this.videos.length ? this.videos[this.videos.length - 1].hash : null;

                        // Ask the server
                        this.getMoreVideos(last_video);
		},
                getNewVideos: function(first_video) {
                        $.ajax('/videos/all?first=' + (first_video || '')).success(function ($data) {
                                // Add the new videos at the very first in the list of new videos
                                this.new_videos = $data.data.concat(this.new_videos);
                        }.bind(this));
                },
                checkNewVideos: function() {
                        // Determine what is the first video listed to get those before this one
                        var first_video = null;
                        if(this.new_videos.length) {
                                first_video = this.new_videos[0].hash;
                        } else if(this.videos.length) {
                                first_video = this.videos[0].hash;
                        }

                        // Ask the server
                        this.getNewVideos(first_video);
                },
                appendNewVideos: function() {
                        this.videos = this.new_videos.concat(this.videos);
                        this.new_videos = [];
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


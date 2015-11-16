<?php

namespace Korko\kTube\Library;

use Google_Client;
use Google_Service_YouTube;
use Google_Service_YouTube_Playlist;
use Google_Service_YouTube_PlaylistItem;
use Google_Service_YouTube_PlaylistItemSnippet;
use Google_Service_YouTube_PlaylistSnippet;
use Google_Service_YouTube_PlaylistStatus;
use Google_Service_YouTube_ResourceId;

class YoutubeLib
{
    /**
     * Get a fresh Youtube API object
     * @param  String|null $accessToken
     * @return Google_Service_YouTube
     */
    public static function getApi($accessToken = null)
    {
        $client = new Google_Client();

        if(isset($accessToken)) {
	        $client->setAccessToken(json_encode([
	            'access_token' => $accessToken,
	            'created' => time(),
	            'expires_in' => 3600
	        ]));
	    }

        $client->setDeveloperKey(config('services.youtube.api_key'));

        return new Google_Service_YouTube($client);
    }

    public static function getPlaylist($title, $description = '', $status = 'private')
    {
        // Create the snippet for the playlist. Set its title and description.
        $playlistSnippet = new Google_Service_YouTube_PlaylistSnippet();
        $playlistSnippet->setTitle($title);
        $playlistSnippet->setDescription($description);

        // Define the playlist's status.
        $playlistStatus = new Google_Service_YouTube_PlaylistStatus();
        $playlistStatus->setPrivacyStatus($status);

        // Define a playlist resource and associate the snippet and status
        // defined above with that resource.
        $youTubePlaylist = new Google_Service_YouTube_Playlist();
        $youTubePlaylist->setSnippet($playlistSnippet);
        $youTubePlaylist->setStatus($playlistStatus);

        return $youTubePlaylist;
    }

    public static function getPlaylistItem($videoId, $playlistId)
    {
        // First, define the resource being added
        // to the playlist by setting its video ID and kind.
        $resourceId = new Google_Service_YouTube_ResourceId();
        $resourceId->setVideoId($videoId);
        $resourceId->setKind('youtube#video');

        // Then define a snippet for the playlist item. Set the playlist item's
        // title if you want to display a different value than the title of the
        // video being added. Add the resource ID and the playlist ID retrieved
        // in step 4 to the snippet as well.
        $playlistItemSnippet = new Google_Service_YouTube_PlaylistItemSnippet();
        $playlistItemSnippet->setPlaylistId($playlistId);
        $playlistItemSnippet->setResourceId($resourceId);

        // Finally, create a playlistItem resource and add the snippet to the
        // resource, then call the playlistItems.insert method to add the playlist
        // item.
        $playlistItem = new Google_Service_YouTube_PlaylistItem();
        $playlistItem->setSnippet($playlistItemSnippet);

        return $playlistItem;
    }
}
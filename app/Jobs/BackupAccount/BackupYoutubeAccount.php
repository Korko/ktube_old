<?php

namespace Korko\kTube\Jobs\BackupAccount;

use Google_Service_Exception;
use Google_Service_YouTube;
use Google_Service_YouTube_Playlist;
use Google_Service_YouTube_PlaylistItem;
use Google_Service_YouTube_PlaylistItemSnippet;
use Google_Service_YouTube_PlaylistSnippet;
use Google_Service_YouTube_PlaylistStatus;
use Google_Service_YouTube_ResourceId;
use Korko\kTube\Account;
use Korko\kTube\Jobs\YoutubeJob;
use Korko\kTube\Video;
use Log;

class BackupYoutubeAccount extends BackupAccount
{
    use YoutubeJob;

    protected function createPlaylist(Account $account, $title, $videos)
    {
        $youTubePlaylist = $this->getYouTubePlaylist($title);

        $api = $this->getApi($account);

        $playlistResponse = $api->playlists->insert('snippet,status', $youTubePlaylist);

        foreach ($videos as $video) {
            try {
                $this->addVideoToPlaylist($api, $video, $playlistResponse['id']);
            } catch (Google_Service_Exception $e) {
                Log::error('Exception thrown when trying to backup videos', ['account' => $account, 'video' => $video, 'exception' => $e]);
            }
        }
    }

    protected function getYouTubePlaylist($title, $description = '')
    {
        // Create the snippet for the playlist. Set its title and description.
        $playlistSnippet = new Google_Service_YouTube_PlaylistSnippet();
        $playlistSnippet->setTitle($title);
        $playlistSnippet->setDescription($description);

        // Define the playlist's status.
        $playlistStatus = new Google_Service_YouTube_PlaylistStatus();
        $playlistStatus->setPrivacyStatus('private');

        // Define a playlist resource and associate the snippet and status
        // defined above with that resource.
        $youTubePlaylist = new Google_Service_YouTube_Playlist();
        $youTubePlaylist->setSnippet($playlistSnippet);
        $youTubePlaylist->setStatus($playlistStatus);

        return $youTubePlaylist;
    }

    protected function addVideoToPlaylist(Google_Service_YouTube $api, Video $video, $playlistId)
    {
        // First, define the resource being added
        // to the playlist by setting its video ID and kind.
        $resourceId = new Google_Service_YouTube_ResourceId();
        $resourceId->setVideoId($video->video_id);
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
        $playlistItemResponse = $api->playlistItems->insert(
            'snippet,contentDetails', $playlistItem, []
        );
    }
}

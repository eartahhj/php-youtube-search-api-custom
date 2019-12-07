<?php
namespace Youtube\SearchAPI;

abstract class YoutubeSearchAPI
{
    protected $apiCallUrl = '';
    protected $key = '';
    protected $maxResults = 0;
    protected $channelId = '';
    protected $searchType = '';
    protected $searchQuery = '';
    protected $dataJson = '';
    protected $data = '';
    protected $rawItemsList = [];
    protected $itemsList = [];
    protected $videos = [];
    protected $playlists = [];

    public function __construct()
    {
        $this->key = Config::API_KEY;
    }

    protected function setApiCallUrl() : bool
    {
        if(!$this->key) {
            throw new \LogicException('API Key was not set.');
            return false;
        }

        $this->apiCallUrl = "https://www.googleapis.com/youtube/v3/search?key={$this->key}&part=snippet,id&order=date&maxResults={$this->maxResults}";

        if($this->channelId) {
            $this->apiCallUrl .= "&channelId={$this->channelId}";
        }

        if($this->searchType) {
            $this->apiCallUrl .= "&type={$this->searchType}";
        }

        if($this->searchQuery) {
            $this->apiCallUrl .= '&q=' . urlencode($this->searchQuery);
        }
var_dump($this->apiCallUrl);
        return true;
    }

    protected function setDataFromApiCall() : void
    {
        $this->setApiCallUrl();
        $this->dataJson = file_get_contents($this->apiCallUrl);
        $this->data = json_decode($this->dataJson);
        return;
    }

    public function getDataJson() : string
    {
        return $this->dataJson;
    }

    public function getRawItemsList() : array
    {
        return $this->rawItemsList;
    }

    public function getItemsList() : array
    {
        return $this->itemsList;
    }

    public function getVideos() : array
    {
        return $this->videos;
    }

    public function getPlaylists() : array
    {
        return $this->playlists;
    }

    public function setMaxResults(int $maxResults) : bool
    {
        if($maxResults < 0 or $maxResults > 50) {
            throw new \InvalidArgumentException('Parameter maxResults must be between 0 and 50, inclusive.');
            return false;
        }

        $this->maxResults = $maxResults;
        return true;
    }

    public function setKey(string $key) : void
    {
        $this->key = $key;
        return;
    }

    public function setChannelId(string $channelId) : void
    {
        $this->channelId = $channelId;
        return;
    }

    public function populateList() : bool
    {
        $this->setDataFromApiCall();
        if (!$this->data) {
            throw new \ErrorException('Data is not set. Did you call the API and did you process the data from the call?');
            return false;
        } else {
            $this->processDataItemsIntoList();
            return true;
        }
    }

    public function addVideoToList($data) : void
    {
        $video = new YoutubeVideo();
        $video->setFromData($data);
        $video->useDefaultUrl();
        $video->useDefaultHtmlIframe();
        $video->addHtmlBefore('<div class="gaminghouse-video">');
        $video->addHtmlAfter('</div>');
        if($video->getId()) {
            $this->itemsList[$video->getId()] = clone $video;
            $this->videos[$video->getId()] = clone $video;
        }
        unset($video);

        return;
    }

    public function addPlaylistToList($data) : void
    {
        $playlist = new YoutubePlaylist();
        $playlist->setFromData($data);
        $playlist->useDefaultHtmlIframe();
        $playlist->addHtmlBefore('<div class="gaminghouse-playlist">');
        $playlist->addHtmlAfter('</div>');
        if($playlist->getId()) {
            $this->itemsList[$playlist->getId()] = clone $playlist;
            $this->playlists[$playlist->getId()] = clone $playlist;
        }
        unset($playlist);

        return;
    }

    public function processDataItemsIntoList() : void
    {
        foreach ($this->data->items as $itemNumber => $itemData) {

            $this->rawItemsList[] = $itemData;

            if($itemData->id->kind == 'youtube#video') {
                $this->addVideoToList($itemData);
            }

            if($itemData->id->kind == 'youtube#playlist') {
                $this->addPlaylistToList($itemData);
            }
        }

        return;
    }
}

# Retrieve generic items with a query, like a normal youtube research
class YoutubeItemsFromQuery extends YoutubeSearchAPI
{
    public function setQuery(string $query) : void
    {
        $this->searchQuery = $query;
        return;
    }
}

class YoutubeVideosFromChannel extends YoutubeSearchAPI
{
    protected $searchType = 'video';
}

class YoutubePlaylistsFromChannel extends YoutubeSearchAPI
{
    protected $searchType = 'playlist';
}

# NOTE: Please note that you can currently retrieve 50 results from the API, so you won't get all the items if they are more than 50.
class YoutubeVideosAndPlaylistsFromChannel extends YoutubeSearchAPI
{
    protected $searchType = 'video,playlist';
}

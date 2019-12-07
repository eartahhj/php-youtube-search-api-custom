<?php
namespace Youtube\SearchAPI;

abstract class YoutubeItem
{
    protected $id = '';
    protected $url = '';
    protected $channel = '';
    protected $html = '';
    protected $cssClass = '';
    protected $title = '';
    protected $publishedAt = '';
    protected $description = '';
    protected $thumbnails = null;
    protected $thumbnail = '';
    protected $channelTitle = '';

    abstract public function setFromData($data) : void;

    public function setId(string $id) : void
    {
        $this->id=$id;
        return;
    }

    public function getId() : string
    {
        return $this->id;
    }

    public function setTitle(string $title) : void
    {
        $this->title = $title;
        return;
    }

    public function setPublishedAt(string $publishedAt) : void
    {
        $dateTime = new \DateTime($publishedAt);
        $this->publishedAt = $dateTime->format(Config::DATE_FORMAT . ' ' . Config::TIME_FORMAT);
        $this->publishedAtDate = $dateTime->format(Config::DATE_FORMAT);
        $this->publishedAtTime =  $dateTime->format(Config::TIME_FORMAT);
        return;
    }

    public function setDescription(string $description) : void
    {
        $this->description = $description;
        return;
    }

    public function setThumbnails(object $thumbnails) : void
    {
        $this->thumbnails = $thumbnails;
        return;
    }

    public function setThumbnail(object $thumbnail) : void
    {
        $this->thumbnail = $thumbnail;
        return;
    }

    public function setChannelTitle(string $channelTitle) : void
    {
        $this->channelTitle = $channelTitle;
        return;
    }

    public function getUrl() : string
    {
        return $url;
    }

    public function setCssClass(string $cssClass) : void
    {
        $this->cssClass=$cssClass;
        return;
    }

    public function addHtmlBefore(string $html) : void
    {
        $this->html=$html.$this->html;
        return;
    }

    public function addHtmlAfter(string $html) : void
    {
        $this->html=$this->html.$html;
        return;
    }

    public function getHtml() : string
    {
        return $this->html;
    }


    public function useDefaultHtmlIframe() : void
    {
        $this->html='<iframe width="474" height="315" src="'.$this->url.'" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
        return;
    }
}

class YoutubeVideo extends YoutubeItem
{

    protected $startAt = 0; # time in seconds eg. 605 = start at 00:10:05

    public function setFromData($data) : void
    {
        $this->setId($data->id->videoId);
        $this->setTitle($data->snippet->title);
        $this->setPublishedAt($data->snippet->publishedAt);
        $this->setDescription($data->snippet->description);
        $this->setThumbnails($data->snippet->thumbnails);
        $this->setThumbnail($data->snippet->thumbnails->high);
        $this->setChannelTitle($data->snippet->channelTitle);

        return;
    }

    public function useDefaultUrl() : void
    {
        $this->url='https://www.youtube.com/watch?v='.$this->id;
        if ($this->startAt) {
            $this->url .= '&amp;t='.(int)$this->startAt;
        }
        return;
    }
}

class YoutubePlaylist extends YoutubeItem
{
    public function setFromData($data) : void
    {
        $this->setId($data->id->playlistId);
        $this->setTitle($data->snippet->title);
        $this->setPublishedAt($data->snippet->publishedAt);
        $this->setDescription($data->snippet->description);
        $this->setThumbnails($data->snippet->thumbnails);
        $this->setThumbnail($data->snippet->thumbnails->high);
        $this->setChannelTitle($data->snippet->channelTitle);

        return;
    }
}

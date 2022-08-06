<?php
namespace Youtube\SearchAPI;

require_once 'app/config.php';

require_once 'app/youtube-items.php';

require_once 'app/youtube-results.php';

# Examples:
$youtubeVideos = new YoutubeVideosFromChannel();
$youtubeVideos->setChannelId('UCRZHI3lfoL_mmCQHx7-GpVA'); # https://www.youtube.com/GamingHouseYT
$youtubeVideos->setMaxResults(20);
$youtubeVideos->populateList();
print_r($youtubeVideos->getVideos());

$youtubeItems = new YoutubeItemsFromQuery();
$youtubeItems->setQuery('age of empires');
$youtubeItems->populateList();
print_r($youtubeItems->getItemsList());

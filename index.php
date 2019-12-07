<?php
/*
* @author: eartahhj - www.liberamenteweb.it - https://www.youtube.com/GamingHouseYT
* @version: 1.0
* @phpversion: 7.3
* @lastUpdate: (dd/mm/yyyy) 07/12/2019
* @docs: Youtube Data API v3 => https://developers.google.com/youtube/v3/docs/search
* @info: You can use this tool to convert the results from Youtube Search API (JSON) into PHP and create any output you want with it. At this moment, it works with videos and playlists, retrieved from a specific Search Query or from a Youtube Channel.
* @api: To use this, you need to setup your own Youtube Data API Key at https://console.cloud.google.com
*
* @donate: If you want, you can buy me a coffe by donating at https://www.paypal.me/chrmar - Thank you!
*/

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

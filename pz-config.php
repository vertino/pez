<?php
define('ABSPATH', dirname(__FILE__));
define('CHARSET', 'ISO-8859-1');
define('MAX_ITEMS', 5); // Set the number of blog articles you want to appear here.
define('DATE_FORMAT', 'M j Y');

include_once(ABSPATH . '/pz-includes/functions.php');
include_once(ABSPATH . '/pz-includes/classes.php');


// Enter your full name here.
$fullname = "Benjamin A. Taylor";

// This is a paragraph sized description of you that appears at the top of the page. Remember to use paragraph and break tags.
$description = "<p>Hi! I'm the guy who runs bogd. I've been making website magic since 1996.</p>

<p>I spend my days hanging out, reading, writing, doing manly things, filling my head with what I hope is useful knowledge, taking power naps, day dreaming (spacing out), and drawing.</p> 

 ";

// Leave this blank if you don't want to publish your email address.
$email = "ben@bogdind.com";

// Ignore this for now
$usetwitter = false;

// Enter the rss/atom/etc. feeds of any blogs you have as below (seperated by commas and enclosed in single quotes).
$blogfeeds = array('http://feeds.feedburner.com/thebentaylorblog');

// Enter the names for the blogs above (in order and seperated by commas and enclosed in single quotes).
$blognames = array('bogdind');

// Enter the rss/atom/etc. feeds of any bookmark feeds (del.icio.us, digg, etc.) you have as below (seperated by commas and enclosed in single quotes).
$bookmarkfeeds = array('http://feeds.delicious.com/rss/thebentaylor','http://digg.com/users/Venkman/history.rss', 'http://www.google.com/reader/public/atom/user/08322762871648052302/state/com.google/broadcast');

// Enter the rss/atom/etc. feeds from last.fm (seperated by commas and enclosed in single quotes).
$musicfeeds = array('http://feeds.feedburner.com/benlastfm');

// Set the number of photos you want to appear here.
$piclength = 6;

// Enter the feed of your flickr public photos.
$imagefeedurl = "http://api.flickr.com/services/feeds/photos_public.gne?id=25409663@N00&lang=en-us&format=atom";
?>


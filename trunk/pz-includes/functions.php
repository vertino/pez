<?php
if (!defined('ABSPATH'))
	die('unable to continue...');

include_once(ABSPATH . '/pz-includes/simplepie/simplepie.inc');

function combine_feeds( $feed_list, $max_items = 10, $delimiter = '~', $remove_html = true )
{
	if (empty($feed_list))
		return false;
	
	$combined_feed = array();
	
	foreach($feed_list as $url)
	{
		$feed = new SimplePie();
		$feed->set_feed_url($url);
		$feed->set_cache_location(ABSPATH . '/pz-content/cache');
		//$feed->replace_headers(true);
		if ($remove_html)
			$feed->strip_htmltags(array('img', 'a', 'object', 'embed', 'param', 'iframe', 'p', 'br', 'div', 'span', 'li', 'ul'));
		$feed->set_output_encoding(CHARSET);
		$feed->init();
		
		foreach ($feed->get_items(0, $max_items) as $item)
		{
			$combined_feed[$item->get_date('U') . $delimiter . $feed->get_title() . $delimiter . $url] = $item;
		}
		unset($feed);
	}
	
	krsort($combined_feed);
	
	return array_slice($combined_feed, 0, $max_items);
}

function link_list( $items, $delimiter = '~' )
{
	if (empty($items))
		return false;
	
	$i = 0;
	$html = "<ul>\n";
	$items_count = (sizeof($items) - 1);
	foreach($items as $key => $item)
	{
		$source = explode($delimiter, $key);
		$class = ( ($i == 0) ? 'first ' : ( ($i == $items_count) ? 'last ' : '' ) ) . 'item ' . get_domain($source[2]);
		$html .= '<li><a class="' . $class . '" href="' . $item->get_permalink() . '">' . $item->get_title() . "</a></li>\n";
		$i++;
	}
	$html .= "</ul>\n";
	
	unset($feeds, $item, $i);
	
	return $html;
}

function trim_excerpt( $content = '', $word_count = 100, $more_link = '[...]' ) {
		
	$content = trim($content);
	
	if ( '' != $content ) {
		$content = strip_tags($content, '<br>');
		$words = explode(' ', $content, $word_count + 1);
		if (count($words) > $word_count) {
			array_pop($words);
			$content = implode(' ', $words);
		}
	}
	
	return "$content $more_link";
}

function flickr_photos( $feed_url, $max_items = 10 )
{
	//$items = combine_feeds( array($feed_url), $max_items, '~', false);
	
	$feed = new SimplePie();
	$feed->set_feed_url($feed_url); 
	$feed->set_cache_location(ABSPATH . '/pz-content/cache');
	$feed->set_output_encoding('ISO-8859-1');
	$feed->init();

	$html = '';
	
	if ($feed->data)
	{
		foreach ($feed->get_items(0, $max_items) as $item)
		{
			$image = $item->get_description();
			$image = substr($image, strpos($image, '<img') + 10);
			$image = trim(substr($image, 0, strpos($image, "\" width")));
			$healthy = array("%3A", "%2F");
			$yummy = array(":", "/");
			$image = str_replace($healthy, $yummy, $image);
			$imagetn = str_replace('m.jpg', 's.jpg', $image);
			
			$html .= '<a href="' . $item->get_permalink() . '">';
			$html .= '<img src="' . $imagetn . '" alt="[flickr photo: ' . $item->get_title() . ']" title="' . $item->get_title() . '" />';
			$html .= "</a>\n";
		}
	}
	
	return $html;
}

function profile_list( $deletable = false )
{
	if (!class_exists('WebDataSources'))
		return false;
	
	$profile = new WebDataSources();
	//$profile->open();
		
	if (empty($profile->profiles))
		return false;
	
	asort($profile->profiles);
	
	$i = 0;
	$html = "<ul class=\"profiles\">\n";
	$profile_count = (sizeof($profile->profiles) - 1);
	foreach($profile->profiles as $idx => $profile)
	{
		if ($deletable)
		{
			$delete_me = '<form method="post" id="delete-network-' . $idx . '" onsubmit="javascript:return confirm(\'Are you sure you want to delete your social network profile for ' . $profile[1][0] . '?\');">';
			$delete_me .= '<input type="hidden" name="form_name" value="delete_sn_form" />';
			$delete_me .= '<input type="hidden" name="delete_id" value="' . $idx . '"/>';
			$delete_me .= '<input type="submit" name="save" id="id_save_sn_' . $idx . '" value="X" class="remove" />';
			$delete_me .= '</form>';
		}
		
		$class = ( ($i == 0) ? 'first ' : ( ($i == $profile_count) ? 'last ' : '' ) ) . 'item ' . $profile[0];
		$html .= '<li><a class="' . $class . '" rel="me" href="' . sprintf($profile[1][1], $profile[2]) . '">' . stripslashes( $profile[1][0] ) . '</a>' . $delete_me . "</li>\n";
		$i++;
	}
	$html .= "</ul>\n";
	
	unset($profile, $profile_count, $i);
	
	return $html;
}

function source_list( $show_ids = false, $deletable = false )
{
	if (!class_exists('WebDataSources'))
		return false;
	
	$profile = new WebDataSources();
	//$profile->open();
		
	if (empty($profile->sources))
		return false;
	
	asort($profile->sources);
	
	$i = 0;
	$html = "<ul class=\"sources\">\n";
	$source_count = (sizeof($profile->sources) - 1);
	foreach($profile->sources as $idx => $source)
	{
		if ($deletable)
		{
			$delete_me = '<form method="post" id="delete-source-' . $idx . '" onsubmit="javascript:return confirm(\'Are you sure you want to delete your web data source for ' . $source[0] . '?\');">';
			$delete_me .= '<input type="hidden" name="form_name" value="delete_wds_form" />';
			$delete_me .= '<input type="hidden" name="delete_id" value="' . $idx . '"/>';
			$delete_me .= '<input type="submit" name="save" id="id_save_wds_' . $idx . '" value="X" class="remove" />';
			$delete_me .= '</form>';
		}
		$id = ( ($show_ids) ? ' id="' . $idx . '"' : '');
		$class = ( ($i == 0) ? 'first ' : ( ($i == $source_count) ? 'last ' : '' ) ) . 'item ' . get_domain($source[1]);
		$html .= '<li' . $id . '><a class="' . $class . '" rel="me" href="' . $source[1] . '">' . stripslashes( $source[0] ) . '</a>' . $delete_me . "</li>\n";
		$i++;
	}
	$html .= "</ul>\n";
	
	unset($source, $source_count, $i);
	
	return $html;
}

function blog_list( $deletable = false, $removable = false )
{
	if (!class_exists('WebDataSources'))
		return false;
	
	$sources = new WebDataSources();
		
	if ( (empty($sources->blogs)) && (!$removable) )
		return false;
	
	asort($sources->blogs);
	
	$i = 0;
	$html = "<ul class=\"blogs\">\n";
	$blogs_count = (sizeof($sources->blogs) - 1);
	
	foreach($sources->blogs as $idx => $blog)
	{
		$blog_source = $sources->sources[$blog];
		if ($deletable)
		{
			$delete_me = '<form method="post" id="delete-blog-' . $idx . '" onsubmit="javascript:return confirm(\'Are you sure you want to remove ' . $blog_source[0] . ' from your blogs module?\');">';
			$delete_me .= '<input type="hidden" name="form_name" value="delete_blg_form" />';
			$delete_me .= '<input type="hidden" name="delete_id" value="' . $idx . '"/>';
			$delete_me .= '<input type="submit" name="save" id="id_save_blg_' . $idx . '" value="X" class="remove" />';
			$delete_me .= '</form>';
		}
		
		if ($removable)
		{
			$remove_me = '<a href="#" class="remove">x</a>';
			$remove_me .= '<input type="hidden" name="blogs[]" id="blogs-' . array_search($blog_source, $sources->sources) . '" value="' . array_search($blog_source, $sources->sources) . '" />';
		}
		
		$class = ( ($i == 0) ? 'first ' : ( ($i == $blogs_count) ? 'last ' : '' ) ) . 'item ' . get_domain($blog_source[1]);
		$html .= '<li><a class="' . $class . '" rel="me" href="' . $blog_source[1] . '">' . stripslashes( $blog_source[0] ) . '</a>' . $delete_me . $remove_me . "</li>\n";
		$i++;
	}
	$html .= "</ul>\n";
	
	unset($sources, $source, $blogs_count, $i);
	
	return $html;
}

function bookmark_list( $deletable = false, $removable = false )
{
	if (!class_exists('WebDataSources'))
		return false;
	
	$sources = new WebDataSources();
		
	if ( (empty($sources->bookmarks)) && (!$removable) )
		return false;
	
	ksort($sources->bookmarks);
	
	$i = 0;
	$html = "<ul class=\"bookmarks\">\n";
	$bookmark_count = (sizeof($sources->bookmarks) - 1);
	
	foreach($sources->bookmarks as $idx => $bookmark)
	{
		$bookmark_source = $sources->sources[$bookmark];
		if ($deletable)
		{
			$delete_me = '<form method="post" id="delete-bookmark-' . $idx . '" onsubmit="javascript:return confirm(\'Are you sure you want to remove ' . $bookmark_source[0] . ' from your bookmarks module?\');">';
			$delete_me .= '<input type="hidden" name="form_name" value="delete_bkm_form" />';
			$delete_me .= '<input type="hidden" name="delete_id" value="' . $idx . '"/>';
			$delete_me .= '<input type="submit" name="save" id="id_save_bkm_' . $idx . '" value="X" class="remove" />';
			$delete_me .= '</form>';
		}
		
		if ($removable)
		{
			$remove_me = '<a href="#" class="remove">x</a>';
			$remove_me .= '<input type="hidden" name="bookmarks[]" id="bookmarks-' . array_search($bookmark_source, $sources->sources) . '" value="' . array_search($bookmark_source, $sources->sources) . '" />';
		}
		
		$class = ( ($i == 0) ? 'first ' : ( ($i == $bookmark_count) ? 'last ' : '' ) ) . 'item ' . get_domain($bookmark_source[1]);
		$html .= '<li><a class="' . $class . '" rel="me" href="' . $bookmark_source[1] . '">' . stripslashes( $bookmark_source[0] ) . '</a>' . $delete_me . $remove_me . "</li>\n";
		$i++;
	}
	$html .= "</ul>\n";
	
	unset($sources, $source, $bookmark_count, $i);
	
	return $html;
}

function photo_list( $deletable = false, $removable = false )
{
	if (!class_exists('WebDataSources'))
		return false;
	
	$sources = new WebDataSources();
		
	if ( (empty($sources->photos)) && (!$removable) )
		return false;
	
	asort($sources->photos);
	
	$i = 0;
	$html = "<ul class=\"photos\">\n";
	$photo_count = (sizeof($sources->photos) - 1);
	
	foreach($sources->photos as $idx => $photo)
	{
		$photo_source = $sources->sources[$photo];
		if ($deletable)
		{
			$delete_me = '<form method="post" id="delete-photo-' . $idx . '" onsubmit="javascript:return confirm(\'Are you sure you want to remove ' . $bookmark_source[0] . ' from your photos module?\');">';
			$delete_me .= '<input type="hidden" name="form_name" value="delete_pht_form" />';
			$delete_me .= '<input type="hidden" name="delete_id" value="' . $idx . '"/>';
			$delete_me .= '<input type="submit" name="save" id="id_save_pht_' . $idx . '" value="X" class="remove" />';
			$delete_me .= '</form>';
		}
		
		if ($removable)
		{
			$remove_me = '<a href="#" class="remove">x</a>';
			$remove_me .= '<input type="hidden" name="photos[]" id="photos-' . array_search($photo_source, $sources->sources) . '" value="' . array_search($photo_source, $sources->sources) . '" />';
		}
		
		$class = ( ($i == 0) ? 'first ' : ( ($i == $photo_count) ? 'last ' : '' ) ) . 'item ' . get_domain($photo_source[1]);
		$html .= '<li><a class="' . $class . '" rel="me" href="' . $photo_source[1] . '">' . stripslashes( $photo_source[0] ) . '</a>' . $delete_me . $remove_me . "</li>\n";
		$i++;
	}
	$html .= "</ul>\n";
	
	unset($sources, $source, $photo_count, $i);
	
	return $html;
}

function music_list( $deletable = false, $removable = false )
{
	if (!class_exists('WebDataSources'))
		return false;
	
	$sources = new WebDataSources();
		
	if ( (empty($sources->music)) && (!$removable) )
		return false;
	
	asort($sources->music);
	
	$i = 0;
	$html = "<ul class=\"music\">\n";
	$music_count = (sizeof($sources->music) - 1);
	
	foreach($sources->music as $idx => $music)
	{
		$music_source = $sources->sources[$music];
		if ($deletable)
		{
			$delete_me = '<form method="post" id="delete-music-' . $idx . '" onsubmit="javascript:return confirm(\'Are you sure you want to remove ' . $bookmark_source[0] . ' from your music module?\');">';
			$delete_me .= '<input type="hidden" name="form_name" value="delete_msc_form" />';
			$delete_me .= '<input type="hidden" name="delete_id" value="' . $idx . '"/>';
			$delete_me .= '<input type="submit" name="save" id="id_save_msc_' . $idx . '" value="X" class="remove" />';
			$delete_me .= '</form>';
		}
		
		if ($removable)
		{
			$remove_me = '<a href="#" class="remove">x</a>';
			$remove_me .= '<input type="hidden" name="music[]" id="music-' . array_search($music_source, $sources->sources) . '" value="' . array_search($music_source, $sources->sources) . '" />';
		}
		
		$class = ( ($i == 0) ? 'first ' : ( ($i == $music_count) ? 'last ' : '' ) ) . 'item ' . get_domain($music_source[1]);
		$html .= '<li><a class="' . $class . '" rel="me" href="' . $music_source[1] . '">' . stripslashes( $music_source[0] ) . '</a>' . $delete_me . $remove_me . "</li>\n";
		$i++;
	}
	$html .= "</ul>\n";
	
	unset($sources, $source, $music_count, $i);
	
	return $html;
}

function get_domain( $url )
{
	$removeables = array('www.', 'ws.', 'api.', 'blog.', 'feeds.', '.com', '.net', '.org', '.gov', '.co', '.uk', '.');
	$host = parse_url($url, PHP_URL_HOST);
	$host = str_replace($removeables, '', $host);
	return $host;
}

function do_messages( $echo = true )
{
	global $messages;
	
	$html = '';
	
	if ( isset($messages) )
	{
		foreach ($messages as $message)
		{
			$html .= "<div class=\"message-box\"><span class=\"{$message[0]}\">{$message[1]}</span></div>";
		}
	}
	
	if ( empty($html) )
		return;
	
	if ($echo)
	{
		echo $html;
		return;
	}
	
	return $html;
}

function auth_redirect()
{
	if ( (!defined('PASSWORD')) && (!defined('PASS_COOKIE')) )
		die('The password has not been defined, unable to continue...');
	
	// Checks if a user is logged in, if not redirects them to the login page
	if ( ( (!empty($_COOKIE[PASS_COOKIE])) && ( $_COOKIE[PASS_COOKIE] != md5(md5(PASSWORD)) ) ) || (empty($_COOKIE[PASS_COOKIE])) )
	{
		$location = './login.php?redirect_to=' . urlencode($_SERVER['REQUEST_URI']);
		header("Location: $location");
		exit();
	}
}

?>
<?php
if (!defined('ABSPATH'))
	die('unable to continue...');

include_once(ABSPATH . '/pz-includes/simplepie.inc');

function combine_feeds( $feed_list, $max_items = 10, $delimiter = '~', $remove_html = true )
{
	if (empty($feed_list))
		return false;
	
	$combined_feed = array();
	
	foreach($feed_list as $url)
	{
		$feed = new SimplePie();
		$feed->feed_url($url);
		$feed->cache_location(ABSPATH . '/pz-content/cache');
		$feed->replace_headers(true);
		if ($remove_html)
			$feed->strip_htmltags(array('img', 'a', 'object', 'embed', 'param', 'iframe', 'p', 'br', 'div', 'span', 'li', 'ul'));
		$feed->output_encoding(CHARSET);
		$feed->init();
		
		foreach ($feed->get_items(0, $max_items) as $item)
		{
			$combined_feed[$item->get_date('U') . $delimiter . $feed->get_feed_title() . $delimiter . $feed->get_feed_link()] = $item;
		}
		unset($feed);
	}
	
	krsort($combined_feed);
	
	return array_slice($combined_feed, 0, $max_items);
}

function link_list( $items )
{
	if (empty($items))
		return false;
	
	$i = 0;
	$html = "<ul>\n";
	foreach($items as $item)
	{
		$class = ( ($i == 0) ? 'first ' : ( ($i == (sizeof($items) - 1)) ? 'last ' : '' ) ) . 'item';
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
	$feed->feed_url($feed_url); 
	$feed->output_encoding('ISO-8859-1');
	$feed->init();

	$html = '';
	
	if ($feed->data)
	{
		foreach ($feed->get_items(0, $max_items) as $item)
		{
			$image = $item->get_description();
			$image = substr($image, strpos($image, '<img') + 13);
			$image = trim(substr($image, 0, strpos($image, "\" width")));
			$healthy = array("%3A", "%2F");
			$yummy = array(":", "/");
			$image = str_replace($healthy, $yummy, $image);
			$imagetn = str_replace('m.jpg', 's.jpg', $image);
			
			$html .= '<a href="' . $item->get_permalink() . '">';
			$html .= '<img src="htt' . $imagetn . '" alt="[flickr photo: ' . $item->get_title() . ']" title="' . $item->get_title() . '" />';
			$html .= "</a>\n";
		}
	}
	
	return $html;
}

function profile_list( $deletable = false )
{
	if (!class_exists('OtherProfiles'))
		return false;
	
	$profile = new OtherProfiles();
	$profile->open();
		
	if (empty($profile->profiles))
		return false;
	
	asort($profile->profiles);
	
	$i = 0;
	$html = "<ul>\n";
	foreach($profile->profiles as $idx => $profile)
	{
		if ($deletable)
		{
			$delete_me = '<form method="post" id="delete-network-' . $idx . '" onsubmit="javascript:return confirm(\'Are you sure you want to delete your social network profile for ' . $profile[1][0] . '? ('.$idx.')\');">';
			$delete_me .= '<input type="hidden" name="form_name" value="delete_sn_form" />';
			$delete_me .= '<input type="hidden" name="delete_id" value="' . $idx . '"/>';
			$delete_me .= '<input type="submit" value="X" class="remove" />';
			$delete_me .= '</form>';
		}
		
		$class = ( ($i == 0) ? 'first ' : ( ($i == (sizeof($profile->profiles) - 1)) ? 'last ' : '' ) ) . 'item ' . $profile[0];
		$html .= '<li><a class="' . $class . '" rel="me" href="' . sprintf($profile[1][1], $profile[2]) . '">' . $profile[1][0] . '</a>' . $delete_me . "</li>\n";
		$i++;
	}
	$html .= "</ul>\n";
	
	unset($profile, $profile, $i);
	
	return $html;
}

?>
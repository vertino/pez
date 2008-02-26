<?php
include_once('pz-config.php');

if (!class_exists('Profile'))
	die('unable to continue...');

$profile = new Profile();
$profile->open();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en">
	<head profile="http://gmpg.org/xfn/11">
		<title><?php echo $profile->first_name . ' ' . $profile->last_name; ?></title>
		<meta http-equiv="content-type" content="text/html; charset=<?php echo CHARSET; ?>" />
		<link rel="stylesheet" type="text/css" href="pz-content/themes/style.css" />
	</head>
	<body>
		<div id="page">
			<ul class="accessibility">
				<li><a href="#skip" title="Skip to content">Skip to content</a></li>
			</ul>
			<div id="header">
				<h1><?php echo $profile->first_name . ' ' . $profile->last_name; ?></h1>
				<p><?php echo $profile->blurb; ?></p>
			</div>
			<div id="main">
				<a name="skip"></a>
				<div id="content">
					
					<h2>What I'm Saying:</h2>
<?php foreach (combine_feeds($blogfeeds, MAX_ITEMS, '~') as $key => $item) : $feed_info = explode('~', $key); ?>
					<div class="item">
						<h3><a href="<?php echo $item->get_permalink(); ?>"><?php echo $item->get_title(); ?></a>:</h3>
						<blockquote>
							&ldquo;<?php echo trim_excerpt($item->get_description(), 50, '...'); ?>&rdquo; <a href="<?php echo $feed_info[2] ?>"><?php echo $feed_info[1]; ?></a> | <strong> <?php echo gmdate(DATE_FORMAT, $item->get_date('U')); ?></strong>
						</blockquote>
					</div>
<?php endforeach; unset($key, $item); ?>
					
				</div>
				<div id="panel">
					
					<h2>What I'm Seeing:</h2>
					<div class="photos">
						<?php echo flickr_photos($imagefeedurl, 6); ?>
					</div>
					
					<h2>What I'm Reading:</h2>
					<div class="links">
						<?php echo link_list( combine_feeds($bookmarkfeeds, MAX_ITEMS * 2) ); ?>
					</div>
					
					<h2>What I'm Hearing:</h2>
					<div class="links">
						<?php echo link_list( combine_feeds($musicfeeds, MAX_ITEMS * 2) ) ?>
					</div>
					
					<h2>Where You Can Find Me:</h2>
					<div class="links">
						<?php echo profile_list(); ?>
					</div>
					
				</div>
			</div>
			<div id="footer">
				<p>Powered by <a href="http://code.google.com/p/pez/" title="Pez">Pez</a>.</p>
			</div>
		</div>
	</body>
</html>
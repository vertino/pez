<?php include_once('pz-config.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en">
	<head profile="http://gmpg.org/xfn/11">
		<title><?php echo $fullname; ?></title>
		<meta http-equiv="content-type" content="text/html; charset=<?php echo CHARSET; ?>" />
		<link rel="stylesheet" type="text/css" href="pz-content/themes/style.css" />
	</head>
	<body>
		<div id="page">
			<ul class="accessibility">
				<li><a href="#skip" title="Skip to content">Skip to content</a></li>
			</ul>
			<div id="header">
				<h1><?php echo $fullname; ?></h1>
				<p><?php echo $description; ?></p>
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
						<a href="http://www.del.icio.us/thebentaylor/" title="delicious profile" rel="me" class="delicious">del.icio.us</a>
						<a href="http://digg.com/users/Venkman/" title="digg profile" rel="me" class="digg">digg</a>
						<a href="http://www.facebook.com/p/Ben_Taylor/73801719" title="facebook profile" rel="me" class="facebook">facebook</a>
						<a href="http://flickr.com/photos/nerdcast/" title="flickr profile" rel="me" class="flickr">flickr</a>
						<a href="http://www.last.fm/user/benjamintaylor/" title="lastfm profile" rel="me" class="lastfm">last.fm</a>
						<a href="http://www.myspace.com/gogobentaylor" title="myspace profile" rel="me" class="myspace">MySpace</a>
						<a href="http://pownce.com/thebentaylor/" title="Pownce profile" rel="me" class="pownce">Pownce</a>
						<a href="http://twitter.com/thebentaylor" title="twitter profile" rel="me" class="twitter">twitter</a>
					</div>
					
				</div>
			</div>
			<div id="footer">
				<p>Powered by <a href="http://code.google.com/p/pez/" title="Pez">Pez</a>.</p>
			</div>
		</div>
	</body>
</html>
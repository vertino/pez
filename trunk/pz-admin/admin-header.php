<?php if ( !isset($no_login) ) auth_redirect(); ?>
<html dir="ltr" lang="en">
	<head>
		<title>Pez: Admin</title>
		<style type="text/css">@import url(admin.css);</style>
		<script type="text/javascript" src="../pz-includes/js/jquery-1.2.3.pack.js"></script>
		<script type="text/javascript">
			$(document).ready( function() {
				$('a[rel=external]').click(function()
				{
					window.open( $(this).attr('href') );
					return false;
				});
				$('.message-box').click(function()
				{
					$(this).fadeOut('slow');
				});
			});
			function get_gravatar()
			{
				$('#gravatar-options').slideToggle('slow', function(){
					if ( $('#gravatar-options:visible').length )
					{
						if ( $('#id_gravatar_email').val() == '' )
							$('#id_gravatar_email').val( $('#id_email') );
							
						$.get('profile.php', { gravatar_email: $('#id_gravatar_email').val() }, function(data){
							var gravatar_url = 'http://www.gravatar.com/avatar.php?gravatar_id=' + data;
							$('#id_gravatar_url').val(gravatar_url);
							$('#gravatar_image').attr('src', gravatar_url).fadeIn('slow');
						});
					}
				});
			}
		</script>
	</head>
	<body>
		<div id="page">
			<div id="header">
				<h1>Pez: Your online personal profile</h1>
				<ul id="menu">
					<li class="first"><a href="../" rel="external">Pez</a></li>
					<li class="<?php if ($_SERVER['SCRIPT_NAME'] == '/pez/pz-admin/profile.php') echo 'current'; ?>"><a href="profile.php">Profile</a></li>
					<li class="<?php if ($_SERVER['SCRIPT_NAME'] == '/pez/pz-admin/web-sources.php') echo 'current'; ?>"><a href="web-sources.php">Web Data Sources</a></li>
					<li class="last<?php if ($_SERVER['SCRIPT_NAME'] == '/pez/pz-admin/settings.php') echo ' current'; ?>"><a href="settings.php">Settings</a></li>
					<?php if ( is_auth() ) : ?><li><a href="login.php?logout=true">Logout</a></li><?php endif; ?>
				</ul>
			</div>
			<div id="main">
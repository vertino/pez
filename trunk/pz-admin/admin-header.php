<html dir="ltr" lang="en">
	<head>
		<title>Pez: Admin</title>
		<style type="text/css">@import url(admin.css);</style>
		<script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script>
		<script type="text/javascript">
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
					<li><a href="../" target="_blank">Pez</a></li>
					<li><a href="profile.php">Profile</a></li>
					<li><a href="web-sources.php">Web Data Sources</a></li>
				</ul>
			</div>
			<div id="main">
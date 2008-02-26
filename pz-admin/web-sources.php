<?php
include_once('../pz-config.php');
include_once(ABSPATH . '/pz-admin/social-networks.php');
?>
<html>
	<head>
		<title>Pez: Admin: Web Sources</title>
	</head>
	<body>
		<ul>
			<li><a href="../" target="_blank">Pez</a></li>
			<li><a href="profile.php">Profile</a></li>
			<li><a href="web-sources.php">Web Data Sources</a></li>
		</ul>
		<h1>Web Data Sources</h1>
		
		<?php
			if ( isset($_POST['network_id']) && isset($_POST['username']) )
			{
				$network_id = $_POST['network_id'];
				$username = $_POST['username'];
				
				$profile = new OtherProfiles();
				$profile->open();
				$profile->profiles[] = array($network_id, $social_networks[$network_id], $username);
				$profile->save();
				unset($profile);
			}
			if ( isset($_POST['delete_id']) )
			{
				$delete_id = $_POST['delete_id'];
				
				$profile = new OtherProfiles();
				$profile->open();
				unset($profile->profiles[$delete_id]);
				$profile->save();
				unset($profile);
			}
		?>
		<form id="add-network" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
			<h2>Add a Social Network</h2>
			<input type="hidden" name="form_name" value="sn_form" id="id_form_name" />
			<div>
				<select name="network_id" id="id_network_id">
				<?php foreach ($social_networks as $name => $site) : ?>
					<option value="<?php echo $name; ?>"><?php echo $site[0]; ?></option>
				<?php endforeach; ?>
				</select>
			</div>
			<div>
				<label for="id_username">Username / User ID</label>
				<input id="id_username" type="text" name="username" maxlength="32" value="leekelleher" />
				<p class="note">Facebook? Use the number in the URL of your 'Profile' page (eg <?php echo rand(100000000, 999999999); ?>)</p>
			</div>
			<div><input type="submit" value="Add Social Network" class="button" /></div>
		</form>
		
		<?php echo profile_list(true); ?>
		
	</body>
</html>
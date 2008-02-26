<?php
include_once('../pz-config.php');
?>
<html>
	<head>
		<title>Pez: Admin: Profile</title>
	</head>
	<body>
		<ul>
			<li><a href="../" target="_blank">Pez</a></li>
			<li><a href="profile.php">Profile</a></li>
			<li><a href="web-sources.php">Web Data Sources</a></li>
		</ul>
		<h1>Profile</h1>
		<?php
			$profile = new Profile();
			$profile->open();
			
			if (isset($_POST['save']))
			{
				if ( isset($_POST['first_name']) && $_POST['first_name'] != '')
					$profile->first_name = $_POST['first_name'];
				
				if ( isset($_POST['last_name']) && $_POST['last_name'] != '')
					$profile->last_name = $_POST['last_name'];
				
				if ( isset($_POST['blurb']) && $_POST['blurb'] != '')
					$profile->blurb = $_POST['blurb'];
				
				if ( isset($_POST['location']) && $_POST['location'] != '')
					$profile->location = $_POST['location'];
				
				if ( isset($_POST['email']) && $_POST['email'] != '')
					$profile->email = $_POST['email'];
				
				if ( isset($_POST['dob_day']) && isset($_POST['dob_month']) && isset($_POST['dob_year']))
					$profile->dob = array('day' => $_POST['dob_day'], 'month' => $_POST['dob_month'], 'year' => $_POST['dob_year']);
				
				if ( isset($_POST['gender']) && $_POST['gender'] != '')
					$profile->gender = $_POST['gender'];
				
				$profile->save();
				echo '<p>saved</p>';
			}
		?>
		
		<form id="profile" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
			<div>
				<label for="id_first_name">First name</label>
				<input type="text" name="first_name" id="id_first_name" value="<?php echo $profile->first_name; ?>" maxlength="32" />
			</div>
			<div>
				<label for="id_last_name">Last name</label>
				<input type="text" name="last_name" id="id_last_name" value="<?php echo $profile->last_name; ?>" maxlength="32" />
			</div>
			<div>
				<label for="id_blurb">Short blurb about me</label>
				<textarea name="blurb" id="id_blurb" rows="3" cols="40"><?php echo $profile->blurb; ?></textarea>
			</div>
			<div>
				<label for="id_location">Location</label>
				<input type="text" name="location" id="id_location" value="<?php echo $profile->location; ?>" maxlength="32" />
			</div>
			<div>
				<label for="id_email">Email address</label>
				<input type="text" name="email" id="id_email" value="<?php echo $profile->email; ?>" />
			</div>
			<div>
				<label>Date of Birth</label>
				<select name="dob_day" id="id_dob_day">
					<option value="0">Day</option>
					<?php for ($i = 1; $i <= 31; $i++) echo "<option value=\"$i\"" . (($i == $profile->dob['day']) ? ' selected="true"' : '') . ">$i</option>"; ?>
				</select>
				<select name="dob_month" id="id_dob_month">
					<option value="0">Month</option>
					<?php for ($i = 1; $i <= 12; $i++) echo "<option value=\"$i\"" . (($i == $profile->dob['month']) ? ' selected="true"' : '') . ">" . date('F', mktime(0, 0, 0, $i)) . "</option>"; ?>
				</select>
				<select name="dob_year" id="id_dob_year">
					<option value="0">Year</option>
					<?php for ($i = date('Y'); $i >= 1900; $i--) echo "<option value=\"$i\"" . (($i == $profile->dob['year']) ? ' selected="true"' : '') . ">$i</option>"; ?>
				</select>
			</div>
			<div>
				<label>Gender</label>
				<select name="gender" id="id_gender">
					<option value="0">Select One</option>
					<option value="m"<?php if ($profile->gender == 'm') echo ' selected="true"'; ?>>Male</option>
					<option value="f"<?php if ($profile->gender == 'f') echo ' selected="true"'; ?>>Female</option>
				</select>
			</div>
			<div><input type="submit" name="save" id="id_save" value="Save Changes" class="button" /></div>
		</form>
	</body>
</html>
<?php
// check if an email address for the gravatar image is available
if ( isset($_GET['gravatar_email']) && $_GET['gravatar_email'] != '' )
{
	$gravatar_url = md5( $_GET['gravatar_email'] );
	exit($gravatar_url);
}

require_once('../pz-config.php');
require_once('social-networks.php');

$profile = new Profile();

if ( isset($_POST['save']) )
{
	if ( isset($_POST['first_name']) && $_POST['first_name'] != '')
		$profile->first_name = $_POST['first_name'];
	
	if ( isset($_POST['middle_name']) && $_POST['middle_name'] != '')
		$profile->middle_name = $_POST['middle_name'];
	
	if ( isset($_POST['last_name']) && $_POST['last_name'] != '')
		$profile->last_name = $_POST['last_name'];
	
	if ( isset($_POST['blurb']) && $_POST['blurb'] != '')
		$profile->blurb = $_POST['blurb'];
	
	if ( isset($_POST['location']) && $_POST['location'] != '')
		$profile->location = $_POST['location'];
	
	if ( isset($_POST['country']) && $_POST['country'] != '')
		$profile->country = $_POST['country'];
	
	if ( isset($_POST['email']) && $_POST['email'] != '')
		$profile->email = $_POST['email'];
	
	if ( isset($_POST['dob_day']) && isset($_POST['dob_month']) && isset($_POST['dob_year']))
		$profile->dob = array('day' => $_POST['dob_day'], 'month' => $_POST['dob_month'], 'year' => $_POST['dob_year']);
	
	if ( isset($_POST['gender']) && $_POST['gender'] != '')
		$profile->gender = $_POST['gender'];
	
	if ( isset($_POST['photo_url']) && isset($_POST['photo_url']) )
		$profile->photo_url = $_POST['photo_url'];
	
	if ( isset($_POST['gravatar_check']) && isset($_POST['gravatar_url']) )
	{
		$profile->gravatar = true;
		$profile->photo_url = $_POST['gravatar_url'];
	}
	
	if ( isset($_POST['openid_server']) && isset($_POST['openid_server']) )
		$profile->openid_server = $_POST['openid_server'];
	
	if ( isset($_POST['openid_delegate']) && isset($_POST['openid_delegate']) )
		$profile->openid_delegate = $_POST['openid_delegate'];
	
	$profile->save();
	
	$messages[] = array('success', 'Your profile has been updated.');
}

include_once('admin-header.php');
?>
		<h2>Profile</h2>
		<?php do_messages(); ?>
		
		<form id="profile" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
			<div class="column">
				<fieldset>
					<legend>Personal Details</legend>
					<div>
						<label for="id_first_name">First name</label>
						<input type="text" name="first_name" id="id_first_name" value="<?php echo $profile->first_name; ?>" maxlength="32" />
					</div>
					<div>
						<label for="id_last_name">Middle name</label>
						<input type="text" name="middle_name" id="id_middle_name" value="<?php echo $profile->middle_name; ?>" maxlength="32" />
					</div>
					<div>
						<label for="id_last_name">Last name</label>
						<input type="text" name="last_name" id="id_last_name" value="<?php echo $profile->last_name; ?>" maxlength="32" />
					</div>
					<div>
						<label for="id_blurb">Short blurb about me</label>
						<textarea name="blurb" id="id_blurb" rows="3" cols="40"><?php echo $profile->blurb; ?></textarea>
						<p>This is a paragraph sized description of you that appears at the top of the page. Remember to use paragraph and break tags.</p>
					</div>
					<div>
						<label for="id_location">Location</label>
						<input type="text" name="location" id="id_location" value="<?php echo $profile->location; ?>" maxlength="32" />
					</div>
					<div>
						<label for="id_country">Country</label>
						<select name="country" id="id_country">
							<option class="select" value="-1">Pick one...</option>
						<?php foreach ($countries as $code => $country) : ?>
							<option value="<?php echo $country; ?>"<?php if ($profile->country == $country) echo ' selected="true"'; ?>><?php echo $country; ?></option>
						<?php endforeach; ?>
						</select>
					</div>
					<div>
						<label for="id_email">Email address</label>
						<input type="text" name="email" id="id_email" value="<?php echo $profile->email; ?>" />
						<p>Leave this blank if you don't want to publish your email address.</p>
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
						<label for="id_gender">Gender</label>
						<select name="gender" id="id_gender">
							<option value="0">Select One</option>
							<option value="m"<?php if ($profile->gender == 'm') echo ' selected="true"'; ?>>Male</option>
							<option value="f"<?php if ($profile->gender == 'f') echo ' selected="true"'; ?>>Female</option>
						</select>
					</div>
					<div><input type="submit" name="save" id="id_save_1" value="Save Changes" class="button" /></div>
				</fieldset>
			</div>
			<div class="column">
				<fieldset>
					<legend>Avatar</legend>
					<div>
						<label for="id_photo_url">Profile Photo URL</label>
						<input type="text" name="photo_url" id="id_photo_url" value="<?php echo $profile->photo_url; ?>" />
						<p>Enter the URL of your profile image.</p>
						<?php
							if ( $profile->photo_url != '' )
								echo '<img src="' . $profile->photo_url . '" alt="[profile photo]" title="Profile Photo" />';
						?>
					</div>
<!--
					<div>
						<label for="id_gravatar_check">Got Gravatar?</label>
						<?php $has_gravatar = ($profile->gravatar != '') ? ' checked="true"' : ''; ?>
						<input type="checkbox" name="gravatar_check" id="id_gravatar_check" <?php echo $has_gravatar; ?>onchange="javascript:get_gravatar();" />
						<div id="gravatar-options" style="display:<?php echo ($profile->gravatar != '') ? 'block' : 'none'; ?>;">
							<div>
								<label for="id_gravatar_email">Email</label>
								<input type="text" name="gravatar_email" id="id_gravatar_email" value="<?php echo $profile->email; ?>" />
							</div>
							<div>
								<label for="id_gravatar_url">URL</label>
								<input type="text" name="gravatar_url" id="id_gravatar_url" value="<?php echo $profile->photo_url; ?>" />
							</div>
							<div>
								<img id="gravatar_image" src="<?php echo $profile->gravatar; ?>" alt="[gravatar]" title="Gravatar" />
							</div>
						</div>
					</div>
-->
					<div><input type="submit" name="save" id="id_save_2" value="Save Changes" class="button" /></div>
				</fieldset>
				<fieldset>
					<legend>OpenID</legend>
					<p>For more information about OpenID, please visit <a href="http://openid.net/" rel="external">http://openid.net/</a></p>
					<div>
						<label for="id_openid_server">OpenID Server</label>
						<input type="text" name="openid_server" id="id_openid_server" value="<?php echo $profile->openid_server; ?>" />
					</div>
					<div>
						<label for="id_openid_delegate">OpenID Delegate</label>
						<input type="text" name="openid_delegate" id="id_openid_delegate" value="<?php echo $profile->openid_delegate; ?>" />
					</div>
					<div><input type="submit" name="save" id="id_save_3" value="Save Changes" class="button" /></div>
				</fieldset>
			</div>
		</form>
<?php
include_once('admin-footer.php');
?>
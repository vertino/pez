<?php
// check if an email address for the gravatar image is available
if ( isset($_GET['gravatar_email']) && $_GET['gravatar_email'] != '' )
{
	$gravatar_url = md5( $_GET['gravatar_email'] );
	exit($gravatar_url);
}

require_once('../pz-config.php');
include_once('admin-header.php');
?>
		<h2>Settings</h2>
		<?php
			// form processing
			$settings = new Settings();
			
			if ( isset($_POST['save']) )
			{
				if ( isset($_POST['charset']) && $_POST['charset'] != '')
					$settings->charset = $_POST['charset'];
				
				if ( isset($_POST['max_items']) )
					$settings->max_items = $_POST['max_items'];
				
				if ( isset($_POST['date_format']) && $_POST['date_format'] != '')
					$settings->date_format = $_POST['date_format'];
				
				$settings->save();
				echo '<div class="message-box"><span class="success">Your settings have been updated.</span></div>';
			}
		?>
		
		<form id="settings" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
			<fieldset>
				<legend>Pez Settings</legend>
				<div>
					<label for="id_charset">Charset</label>
					<input type="text" name="charset" id="id_charset" value="<?php echo $settings->charset; ?>" maxlength="32" />
					<p>Enter your charset here. (i.e. UTF-8)</p>
				</div>
				<div>
					<label for="id_max_items">Max Items</label>
					<select name="max_items" id="id_max_items">
						<?php for ($i = 0; $i <= 25; $i++) echo "<option value=\"$i\"" . (($i == $settings->max_items) ? ' selected="true"' : '') . ">$i</option>"; ?>
					</select>
					<p>Set the number of blog articles you want to appear here.</p>
				</div>
				<div>
					<label for="id_date_format">Date Format</label>
					<input type="text" name="date_format" id="id_date_format" value="<?php echo $settings->date_format; ?>" />
					<p>[see the description from wordpress]</p>
				</div>
				<div><input type="submit" name="save" id="id_save_1" value="Save Changes" class="button" /></div>
			</fieldset>
		</form>
<?php
include_once('admin-footer.php');
?>
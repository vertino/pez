<?php
require_once('../pz-config.php');
require_once('social-networks.php');
include_once('admin-header.php');
?>
		<h2>Web Data Sources</h2>
		
		<?php
			if ( isset($_POST['form_name']) && $_POST['form_name'] != '' )
			{
				// open the profile data
				$profile = new WebDataSources();
				$profile->open();
				
				// choose the form process
				switch ($_POST['form_name'])
				{
					case 'sn_form' :
						$network_id = $_POST['network_id'];
						$username = $_POST['username'];
						$profile->profiles[] = array($network_id, $social_networks[$network_id], $username);
						break;
					
					case 'delete_sn_form' :
						$delete_id = $_POST['delete_id'];
						unset($profile->profiles[$delete_id]);
						break;
					
					case 'wds_form' :
						$source_title = $_POST['source_title'];
						$source_url = $_POST['source_url'];
						echo 'cool';
						// here we should use simplepie to:
						//   a. verify that it's an atom/rss feed
						//   b. if it's a webpage, then use simplepie's auto-discovery to get the atom/rss feed
						//   c. alert the user that it's not a valid feed url
						$profile->sources[] = array($source_title, $source_url);
						break;
					
					case 'delete_wds_form' :
						$delete_id = $_POST['delete_id'];
						unset($profile->sources[$delete_id]);
						break;
					
					default :
						break;
				}
				
				// save and close the profile data
				$profile->save();
				unset($profile);
			}
		?>
		<form id="add-network" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" onsubmit="javascript:return (this.network_id.value != '-1');">
			<fieldset>
				<legend>Add a Social Network</legend>
				<p>List your social network profiles here.</p>
				<input type="hidden" name="form_name" value="sn_form" id="id_form_name" />
				<div>
					<select name="network_id" id="id_network_id">
						<option class="select" value="-1">Select a social network...</option>
					<?php foreach ($social_networks as $name => $site) : ?>
						<option class="<?php echo $name; ?>" value="<?php echo $name; ?>"><?php echo $site[0]; ?></option>
					<?php endforeach; ?>
					</select>
				</div>
				<div>
					<label for="id_username">Username / User ID</label>
					<input id="id_username" type="text" name="username" maxlength="32" value="leekelleher" />
					<p class="note">Facebook? Use the number in the URL of your 'Profile' page (e.g. <?php echo rand(100000000, 999999999); ?>)</p>
				</div>
				<div><input type="submit" value="Add Social Network" class="button" /></div>
			</fieldset>
		</form>
		
		<form id="add-source" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
			<fieldset>
				<legend>Add a Web Data Source (Atom/RSS feed)</legend>
				<p>This section is to list all of your web data sources that you want to use within Pez. You can add any Atom and RSS feeds that you like, with no restrictions.</p>
				<input type="hidden" name="form_name" value="wds_form" id="id_form_name" />
				<div>
					<label for="id_source_title">Title</label>
					<input id="id_source_title" type="text" name="source_title" maxlength="32" value="" />
				</div>
				<div>
					<label for="id_source_url">URL</label>
					<input id="id_source_url" type="text" name="source_url" maxlength="255" value="" />
				</div>
				<div><input type="submit" value="Add Web Data Source" class="button" /></div>
			</fieldset>
		</form>
		
		<div style="position:absolute;top:100px;right:20px;background-color:#ccc;width:150px;">
			<?php echo profile_list(true); ?>
			<?php echo source_list(true); ?>
		</div>
		
<?php
include_once('admin-footer.php');
?>
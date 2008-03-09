<?php
require_once('../pz-config.php');
require_once('social-networks.php');
include_once('admin-header.php');
?>
		<h2>Web Data Sources</h2>
		
		<?php
			if ( isset($_POST['save']) )
			{
				// open the profile data
				$sources = new WebDataSources();
				
				// choose the form process
				if ( isset($_POST['form_name']) && $_POST['form_name'] != '' )
				{
					switch ($_POST['form_name'])
					{
						case 'sn_form' :
							$network_id = $_POST['network_id'];
							$username = $_POST['username'];
							$sources->profiles[] = array($network_id, $social_networks[$network_id], $username);
							break;
						
						case 'delete_sn_form' :
							$delete_id = $_POST['delete_id'];
							unset($sources->profiles[$delete_id]);
							break;
						
						case 'wds_form' :
							$source_title = $_POST['source_title'];
							$source_url = $_POST['source_url'];
							// here we should use simplepie to:
							//   a. verify that it's an atom/rss feed
							//   b. if it's a webpage, then use simplepie's auto-discovery to get the atom/rss feed
							//   c. alert the user that it's not a valid feed url
							$sources->sources[] = array($source_title, $source_url);
							break;
						
						case 'delete_wds_form' :
							$delete_id = $_POST['delete_id'];
							unset($sources->sources[$delete_id]);
							break;
						
						case 'blg_form' :
							$source_id = $_POST['source_id'];
							$sources->blogs[] = $source_id;
							break;
						
						case 'delete_blg_form' :
							$delete_id = $_POST['delete_id'];
							unset($sources->blogs[$delete_id]);
							break;
						
						case 'bkm_form' : 
							$source_id = $_POST['source_id'];
							$sources->bookmarks[] = $source_id;
							break;
						
						case 'delete_bkm_form' :
							$delete_id = $_POST['delete_id'];
							unset($sources->bookmarks[$delete_id]);
							break;
						
						case 'pht_form' :
							$source_id = $_POST['source_id'];
							$sources->photos[] = $source_id;
							break;
						
						case 'delete_pht_form' :
							$delete_id = $_POST['delete_id'];
							unset($sources->photos[$delete_id]);
							break;
						
						case 'msc_form' :
							$source_id = $_POST['source_id'];
							$sources->music[] = $source_id;
							break;
						
						case 'delete_msc_form' :
							$delete_id = $_POST['delete_id'];
							unset($sources->music[$delete_id]);
							break;
						
						default :
							break;
					}
				}
				
				// save and close the profile data
				$sources->save();
				unset($sources);
			}
		?>
		<form name="add-network" id="add-network" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>#add-network" onsubmit="javascript:return (this.network_id.value != '-1');">
			<fieldset>
				<legend>Add a Social Network</legend>
				<p>List your social network profiles here.</p>
				<input type="hidden" name="form_name" id="id_form_name" value="sn_form" />
				<div>
					<label for="id_network_id">Select a social network</label>
					<select name="network_id" id="id_network_id">
						<option class="select" value="-1">Pick one...</option>
					<?php foreach ($social_networks as $name => $site) : ?>
						<option class="<?php echo $name; ?>" value="<?php echo $name; ?>"><?php echo $site[0]; ?></option>
					<?php endforeach; ?>
					</select>
				</div>
				<div>
					<label for="id_username">Username / User ID</label>
					<input id="id_username" type="text" name="username" maxlength="32" value="" />
					<p class="note">Facebook? Use the number in the URL of your 'Profile' page (e.g. <?php $rand = rand(100000000, 999999999); echo "<a href=\"http://www.facebook.com/profile.php?id=$rand\" rel=\"external\">$rand</a>"; ?>)</p>
				</div>
				<div><input type="submit" name="save" id="id_save_1" value="Add Social Network" class="button" /></div>
			</fieldset>
		</form>
		
		<form name="add-source" id="add-source" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>#add-source">
			<fieldset>
				<legend>Add a Web Data Source (Atom/RSS feed)</legend>
				<p>This section is to list all of your web data sources that you want to use within Pez. You can add any Atom and RSS feeds that you like, with no restrictions.</p>
				<input type="hidden" name="form_name" id="id_form_name" value="wds_form" />
				<div>
					<label for="id_source_title">Title</label>
					<input id="id_source_title" type="text" name="source_title" maxlength="32" value="" />
				</div>
				<div>
					<label for="id_source_url">URL</label>
					<input id="id_source_url" type="text" name="source_url" maxlength="255" value="" />
				</div>
				<div><input type="submit" name="save" id="id_save_2" value="Add Web Data Source" class="button" /></div>
			</fieldset>
		</form>
		
		<form name="add-blogs" id="add-blogs" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>#add-blogs" onsubmit="javascript:return (this.source_id.value != '-1');">
			<fieldset>
				<legend>Add Sources to Blogs Module</legend>
				<p>Here you can select which web data sources you want to show in your blogs module.</p>
				<input type="hidden" name="form_name" id="id_form_name" value="blg_form" />
				<div>
					<label for="id_blg_source_id">Select a web data source</label>
					<select name="source_id" id="id_blg_source_id">
						<option class="select" value="-1">Pick one...</option>
					<?php
						$sources = new WebDataSources();
						asort($sources->sources);
					?>
					<?php foreach ($sources->sources as $idx => $source) : if ( !in_array($idx, $sources->blogs) ) : ?>
						<option value="<?php echo $idx; ?>"><?php echo stripslashes( $source[0] ); ?></option>
					<?php endif; endforeach; unset($sources); ?>
					</select>
				</div>
				<div><input type="submit" name="save" id="id_save_3" value="Add Source to Blogs" class="button" /></div>
			</fieldset>
		</form>
		
		<form name="add-bookmarks" id="add-bookmarks" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>#add-bookmarks" onsubmit="javascript:return (this.source_id.value != '-1');">
			<fieldset>
				<legend>Add Sources to Bookmark Module</legend>
				<p>Here you can select which web data sources you want to show in your bookmarks module.</p>
				<input type="hidden" name="form_name" id="id_form_name" value="bkm_form" />
				<div>
					<label for="id_bkm_source_id">Select a web data source</label>
					<select name="source_id" id="id_bkm_source_id">
						<option class="select" value="-1">Pick one...</option>
					<?php
						$sources = new WebDataSources();
						asort($sources->sources);
					?>
					<?php foreach ($sources->sources as $idx => $source) : if ( !in_array($idx, $sources->bookmarks) ) : ?>
						<option value="<?php echo $idx; ?>"><?php echo stripslashes( $source[0] ); ?></option>
					<?php endif; endforeach; unset($sources); ?>
					</select>
				</div>
				<div><input type="submit" name="save" id="id_save_4" value="Add Source to Bookmarks" class="button" /></div>
			</fieldset>
		</form>
		
		<form name="add-photos" id="add-photos" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>#add-photos" onsubmit="javascript:return (this.source_id.value != '-1');">
			<fieldset>
				<legend>Add Sources to Photos Module</legend>
				<p>Here you can select which web data sources you want to show in your photos module.</p>
				<input type="hidden" name="form_name" id="id_form_name" value="pht_form" />
				<div>
					<label for="id_pht_source_id">Select a web data source</label>
					<select name="source_id" id="id_pht_source_id">
						<option class="select" value="-1">Pick one...</option>
					<?php
						$sources = new WebDataSources();
						asort($sources->sources);
					?>
					<?php foreach ($sources->sources as $idx => $source) : if ( !in_array($idx, $sources->photos) ) : ?>
						<option value="<?php echo $idx; ?>"><?php echo stripslashes( $source[0] ); ?></option>
					<?php endif; endforeach; unset($sources); ?>
					</select>
				</div>
				<div><input type="submit" name="save" id="id_save_5" value="Add Source to Photos" class="button" /></div>
			</fieldset>
		</form>
		
		<form name="add-music" id="add-music" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>#add-music" onsubmit="javascript:return (this.source_id.value != '-1');">
			<fieldset>
				<legend>Add Sources to Music Module</legend>
				<p>Here you can select which web data sources you want to show in your music module.</p>
				<input type="hidden" name="form_name" id="id_form_name" value="msc_form" />
				<div>
					<label for="id_pht_source_id">Select a web data source</label>
					<select name="source_id" id="id_msc_source_id">
						<option class="select" value="-1">Pick one...</option>
					<?php
						$sources = new WebDataSources();
						asort($sources->sources);
					?>
					<?php foreach ($sources->sources as $idx => $source) : if ( !in_array($idx, $sources->music) ) : ?>
						<option value="<?php echo $idx; ?>"><?php echo stripslashes( $source[0] ); ?></option>
					<?php endif; endforeach; unset($sources); ?>
					</select>
				</div>
				<div><input type="submit" name="save" id="id_save_6" value="Add Source to Music" class="button" /></div>
			</fieldset>
		</form>
		
		<div style="position:absolute;top:100px;right:20px;background-color:#ccc;width:150px;">
			<h4>profiles</h4>
			<?php echo profile_list(true); ?>
			<hr />
			<h4>sources</h4>
			<?php echo source_list(true); ?>
			<hr />
			<h4>blogs</h4>
			<?php echo blog_list(true); ?>
			<hr />
			<h4>bookmarks</h4>
			<?php echo bookmark_list(true); ?>
			<hr />
			<h4>photos</h4>
			<?php echo photo_list(true); ?>
			<hr />
			<h4>music</h4>
			<?php echo music_list(true); ?>
		</div>
		
<?php
include_once('admin-footer.php');
?>
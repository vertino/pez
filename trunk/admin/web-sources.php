<?php
require_once('../pz-config.php');
require_once('social-networks.php');

if ( isset($_POST['save']) )
{
	// open the profile data
	$sources = new WebDataSources();
	
	// choose the form process
	if ( isset($_POST['form_name']) && $_POST['form_name'] != '' )
	{
		switch ($_POST['form_name'])
		{
			case 'wds_form' :
				$source_title = $_POST['source_title'];
				$source_url = $_POST['source_url'];
				// here we should use simplepie to:
				//   a. verify that it's an atom/rss feed
				//   b. if it's a webpage, then use simplepie's auto-discovery to get the atom/rss feed
				//   c. alert the user that it's not a valid feed url
				$sources->sources[] = array($source_title, $source_url);
				$messages[] = array('success', 'Successfully added ' . $source_title . ' to your data-source list.');
				break;
			
			case 'delete_wds_form' :
				$delete_id = $_POST['delete_id'];
				$messages[] = array('success', $sources->sources[$delete_id][0] . ' has been removed from your data-source list.');
				unset($sources->sources[$delete_id]);
				break;
				
			case 'modules_form' :
				if ( isset($_POST['blogs']) && is_array($_POST['blogs']) )
					$sources->blogs = $_POST['blogs'];
				else
					unset($sources->blogs);
				
				if ( isset($_POST['bookmarks']) && is_array($_POST['bookmarks']) )
					$sources->bookmarks = $_POST['bookmarks'];
				else
					unset($sources->bookmarks);
				
				if ( isset($_POST['photos']) && is_array($_POST['photos']) )
					$sources->photos = $_POST['photos'];
				else
					unset($sources->photos);
				
				if ( isset($_POST['music']) && is_array($_POST['music']) )
					$sources->music = $_POST['music'];
				else
					unset($sources->music);
				
				$messages[] = array('success', 'Your content modules have been updated.');
				break;
			
			default :
				break;
		}
	}
	
	// save and close the profile data
	$sources->save();
	unset($sources);
}

$html_head = <<<HTML
		<script src="../includes/js/jquery.dimensions.js"></script>
		<script src="../includes/js/ui.mouse.js"></script>
		<script src="../includes/js/ui.draggable.js"></script>
		<script src="../includes/js/ui.draggable.ext.js"></script>
		<script src="../includes/js/ui.droppable.js"></script>
		<script src="../includes/js/ui.droppable.ext.js"></script>
		<script type="text/javascript">
			$(document).ready(function()
			{
				$("#feed-sources>ul.sources>li").draggable({helper:'clone',cursor:'move'});
				$("ul.sources>li>a").click( function(){return false;}).removeAttr("href");
				$("a.remove").click( function(){ $(this).parent().fadeOut("slow", function(){ $(this).remove(); }); return false; });
				$(".drop").droppable(
				{
					accept: "#feed-sources>ul.sources>li",
					activeClass: 'droppable-active',
					hoverClass: 'droppable-hover',
					drop: function(ev, ui)
					{
						if ( !$(this).children("ul:contains('" + $(ui.draggable).text() + "')").length )
						{
							var block = $(ui.draggable).clone();
							var removeLink = $("<a href='#' class='remove'>x</a>");
							removeLink.click( function(){ $(this).parent().fadeOut("slow", function(){ $(this).remove(); }); return false; });
							block.find("form").remove();
							block.append( removeLink );
							block.append( $("<input type='hidden' name='" + $(this).attr("id") + "[]' id='" + $(this).attr("id") + "-" + block.attr("id") + "' value='" + block.attr("id") + "' />") );
							
							$(this).children("ul").append( block );
						}
					}
				});
			});
		</script>

HTML;

include_once('admin-header.php');
?>
		<h2>Web Data Sources</h2>
		<?php do_messages(); ?>
		
		<div class="column">
			<form name="add-source" id="add-source" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>#add-source">
				<fieldset>
					<legend>Add a Personal Feed (Atom/RSS feed)</legend>
					<p>This section is to list all of your personal feeds that you want to use within Pez. You can add any Atom and RSS feeds that you like, with no restrictions.</p>
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
		</div>
		
		<div class="column">
			<fieldset>
				<legend>Add Feeds to Modules</legend>
				
				<div id="feed-sources">
					<?php echo source_list(true, true); ?>
				</div>
				
				<form name="modules" id="modules" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>#modules">
					<div class="drop-boxes">
						<div id="blogs" class="drop">
							<h3>Blogs Module</h3>
							<?php echo blog_list(false, true); ?>
						</div>
						<div id="bookmarks" class="drop">
							<h3>Bookmarks Module</h3>
							<?php echo bookmark_list(false, true); ?>
						</div>
						<div id="photos" class="drop">
							<h3>Photos Module</h3>
							<?php echo photo_list(false, true); ?>
						</div>
						<div id="music" class="drop">
							<h3>Music Module</h3>
							<?php echo music_list(false, true); ?>
						</div>
					</div>
					
					<div>
						<input type="hidden" name="form_name" id="id_form_name" value="modules_form" />
						<input type="submit" name="save" id="save" value="Save" />
					</div>
					
				</form>
					
			</fieldset>
		</div>
		
<?php
include_once('admin-footer.php');
?>
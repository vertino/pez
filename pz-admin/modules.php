<?php
require_once('../pz-config.php');
require_once('social-networks.php');
include_once('admin-header.php');
?>
		<h2>Modules</h2>
		
		<?php
			if ( isset($_POST['save']) )
			{
				// open the profile data
				$sources = new WebDataSources();
				
				// process the form
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
				
				// save and close the profile data
				$sources->save();
				unset($sources);
				
				echo '<div class="message-box"><span class="success">Your content modules have been updated.</span></div>';
			}
		?>

<script src="http://dev.jquery.com/view/trunk/plugins/dimensions/jquery.dimensions.js"></script>
<script src="http://dev.jquery.com/view/tags/ui/1.0.1a/ui.mouse.js"></script>
<script src="http://dev.jquery.com/view/tags/ui/1.0.1a/ui.draggable.js"></script>
<script src="http://dev.jquery.com/view/tags/ui/1.0.1a/ui.draggable.ext.js"></script>
<script src="http://dev.jquery.com/view/tags/ui/1.0.1a/ui.droppable.js"></script>
<script src="http://dev.jquery.com/view/tags/ui/1.0.1a/ui.droppable.ext.js"></script>
<script type="text/javascript">
	$(document).ready(function()
	{

		$("#web-data-sources>ul.sources>li").draggable({helper:'clone',cursor:'move'});
		$("ul.sources>li>a").click( function(){alert( $("form#modules").serialize() );return false;}).removeAttr("href");
		$("a.remove").click( function(){ $(this).parent().fadeOut("slow", function(){ $(this).remove(); }); return false; });
		
		$(".drop").droppable(
		{
			accept: "#web-data-sources>ul.sources>li",
			activeClass: 'droppable-active',
			hoverClass: 'droppable-hover',
			drop: function(ev, ui)
			{
				if ( !$(this).children("ul.sources:contains('" + $(ui.draggable.element).text() + "')").length )
				{
					var block = $(ui.draggable.element).clone();
					var removeLink = $("<a href='#' class='remove'>x</a>");
					removeLink.click( function(){ $(this).parent().fadeOut("slow", function(){ $(this).remove(); }); });
					block.append( removeLink );
					block.append( $("<input type='hidden' name='" + $(this).attr("id") + "[]' id='" + $(this).attr("id") + "-" + block.attr("id") + "' value='" + block.attr("id") + "' />") );
					
					$(this).children("ul").append( block );
				}
			}
		});

	});
</script>
		
		<form name="modules" id="modules" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>#modules" onsubmit="javascript:return true;">
			
			<div id="web-data-sources">
					<?php echo source_list(true, false); ?>
			</div>
			
			<?php $sources = new WebDataSources(); ?>
			
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
			
			<div>
				<input type="submit" name="save" id="save" value="Save" />
			</div>
			
		</form>
		
<?php
include_once('admin-footer.php');
?>
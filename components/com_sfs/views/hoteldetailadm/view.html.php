<script src="<?php echo JURI::base();?>/templates/sfs_j16_hdwebsoft/js/jquery.min.js" type="text/javascript"></script>
<link rel="stylesheet" href="<?php echo JURI::base();?>/templates/sfs_j16_hdwebsoft/css/template-hdwebsoft.css" type="text/css" />
<script>
	jQuery.noConflict();
	(function( $ ) {
		$(document).ready(function (){
			var img = $("#hotel_image");
            img.error(function(){
                    var src = img.attr("data-img");
                    img.attr("src", src);
            });
		})
	})(jQuery);
</script>
<style>
	.search-result{
		display: block;
		position: relative;
		border-bottom: 0 !important;
		font-family: Arial, sans-serif;
	}
	.infomation{
		display: inline-block;
		position: absolute;
		margin-left: 20px;
	}
	.facilities div.item{
		float: left;
		width: 33%;
		display: inline-block;
	}
	.description{
		display: block;
		float: left;
	}
</style>
<?php
// No direct access
defined('_JEXEC') or die;
jimport('joomla.application.component.view');

class SfsViewHotelDetailAdm extends JView
{
	/**
	 * Display the view
	 */


	function display($tpl = null)
	{
		$id = JRequest::getInt('id');
		$result = SfsWs::getHotelDetail($id);
		$result->Rating = round($result->Rating, 0);
?>
		
		<div class="facilities">
			<?php
				$facilities = $result->Facilities;
				if(!empty($facilities))
				{
					echo '<div><b>Facilities:</b></div>';
					foreach($facilities as $facility)
					{
						echo '<div class="item">'.$facility->Facility.'</div>';
					}
				}
			?>
		</div>
		<div class="description">
			<b>Description:</b>
			<div class="overview">
				<?php echo SfsUtil::format_description($result->Description);?>
			</div>
		</div>
<?php
		jexit();
	}
}

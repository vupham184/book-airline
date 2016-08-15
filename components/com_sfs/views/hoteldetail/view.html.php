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

class SfsViewHotelDetail extends JView
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
		<div class="search-result" id="hotel-thumb">
			<div style="width:100px; float:right; text-align:right"><a onclick="window.parent.SqueezeBox.close();" style="cursor:pointer;text-decoration:underline">Close</a></div>
			<img id="hotel_image" src="<?php echo $result->CMSBaseURL . $result->MainImage;?>" data-img="<?php echo JURI::base() . "templates/sfs_j16_hdwebsoft/images/no-image.gif"?>" style="width: 240px;height: 180px">
			<div class="infomation">
				<strong class="hotel-name"><?php echo $result->PropertyName;?></strong>
				<span class="star star<?php echo (int)$result->Rating;?>"></span>
				<div class="address"><?php echo "Address: ".$result->Address1;?></div>
				<div class="zipcode"><?php echo "Zipcode: ".$result->Postcode;?></div>
				<div class="city"><?php echo "City: ".$result->Region;?></div>
				<br/>
				<div class="phone"><?php echo "Phone: ".$result->Telephone;?></div>
				<div class="fax"><?php echo "Fax: ".$result->Fax;?></div>
			</div>
		</div>
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

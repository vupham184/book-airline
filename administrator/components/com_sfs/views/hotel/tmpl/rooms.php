<?php
defined('_JEXEC') or die;
///JHtml::_('behavior.mootools');
$hotelSetting   = $this->item->getBackendSetting();
$roomTableStyle = 'height:225px;';

if( isset($hotelSetting) && (int)$hotelSetting->single_room_available == 1 || (int)$hotelSetting->quad_room_available == 1 ){
	
	$roomTableStyle = 'height:290px;';
}

if( isset($hotelSetting) && (int)$hotelSetting->single_room_available == 1 && (int)$hotelSetting->quad_room_available == 1 ){
	
	$roomTableStyle = 'height:355px;';
}

$listCur = JFactory::getUser();

?>

<style type="text/css">
.first_form{
	height: 55px;
}
.ratePrice{
	padding-top: 5px;
}
.roomloading-rate{
	padding-top: 2px;
}
</style>
<div class="clr"></div>
<form name="roomLoadingForm" id="roomLoadingForm" action="<?php echo JRoute::_('index.php?option=com_sfs&view=hotel');?>" method="post">
	<div class="roomloading">
	
		<div class="roomloading-left float-left ">
			<div style="padding-right:15px;">
				<div style="padding-bottom:18px; padding-top:9px;">Transport included</div>
				<?php if( isset($hotelSetting) && (int)$hotelSetting->single_room_available == 1 ) : ?>
				<div class="roomloading-rate first_form">
					<strong>Single rooms authorized</strong>
                    <p class="ratePrice">Rate (in <?php echo $this->currency->code; ?>)</p>
				</div>
				<?php endif;?>
				<div class="roomloading-rate first_form">
					<strong>Single/Double rooms authorized</strong>
                    <p class="ratePrice">Rate (in <?php echo $this->currency->code; ?>)</p>
				</div>
				<div class="roomloading-rate first_form">
					<strong>Triple rooms authorized</strong>
                    <p class="ratePrice">Rate (in <?php echo $this->currency->code; ?>)</p>
				</div>
				<?php if( isset($hotelSetting) && (int)$hotelSetting->quad_room_available == 1 ) :?>
				<div class="roomloading-rate first_form">
					<strong>Quad rooms authorized</strong>
                    <p class="ratePrice">Rate (in <?php echo $this->currency->code; ?>)</p>
				</div>
				<?php endif;?>
			</div>
		</div>
	
		<div class="roomloading-middle float-left">
			<div class="roomtable floatbox" style="<?php echo $roomTableStyle?>">
				<div style="margin:0 2px 6px 2px;">
					<?php echo $this->loadTemplate('table');?>
				</div>
			</div>
			<br />
			<input type="submit" value="Save Prices" class="button" style="margin-left:5px;background:green;color:white;padding:5px 20px;border:none; font-size:12px;" />
			<button type="button" class="button" id="check_ranking" style="margin-left:5px;background:green;color:white;padding:5px 20px;border:none; font-size:12px;">Trigger Ranking</button>
			
		</div>
	</div>
	
		
	<input type="hidden" name="task" value="hotel.saveRooms" />
	<input type="hidden" name="id" value="<?php echo $this->item->id;?>" />       
        
    <?php echo JHtml::_('form.token'); ?>
	
</form>

<?php echo $this->loadTemplate('rates');?>

<p class="clr" style="padding:10px;"></p>


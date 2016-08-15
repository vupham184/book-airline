<?php
defined('_JEXEC') or die();
JHTML::_('behavior.modal');
$app = JFactory::getApplication();
//var_dump($this->item);exit;
?>
<strong class="hotel-name"><?php echo $this->item->name?></strong>
<span class="star star<?php echo $this->item->star;?>"></span>
<?php if($this->item->distance):?>
<div class="distance-to-airport">Distance to airport: <span id="distance-to-airport-<?php echo $this->item->id?>"><?php echo $this->item->distance?></span><?php echo " ".$this->item->distance_unit ;?></div>
<?php else:?>
    <div class="distance-to-airport">Distance to airport: <span id="distance-to-airport-<?php echo $this->item->id?>">Unknown</span><?php echo " ".$this->item->distance_unit ;?></div>
<?php endif;?>

<?php
if( $this->item->web_address ) :
	$httpPos = JString::strpos($this->item->web_address, 'http://');
	if( !is_int($httpPos) )
	{
		$this->item->web_address = 'http://'.$this->item->web_address;
	}
	$tip = SfsHelper::getTooltip('search_result_website', $this->tooltip);
	if($tip):
	?>
		<span class="website hasTip" title="<?php echo $tip?>">
			<a href="<?php echo $this->item->web_address;?>" target="_blank">Website</a>
		</span>
	<?php else:?>
		<span class="website">
			<a href="<?php echo $this->item->web_address;?>" target="_blank">Website</a>
		</span>
	<?php endif;?>	
		
<?php endif;?>




<?php if(!empty($this->item->wsData)):?>
	<div style="margin-top: 20px">
		<button type="button" class="btn orange sm button-information" rel="<?php echo $this->item->hotel_id?>">More Information</button>
	</div>
<?php endif;?>
<!--<div data-step="5" data-intro="--><?php //echo SfsHelper::getTooltipTextEsc('search_result_transport', $text,'airline'); ?><!--">-->
<div style="margin-top: 20px">
	<?php echo $this->loadTemplate('item_transport');?>
</div>

<?php if(empty($this->item->wsData)):
    $certifiedTooltip = "<b>This is a SFS certified hotel</b><br/>";
    $certifiedTooltip .= "<div style='font-size: 12px;'>SFS partner hotels are hotels that have specific options for stranded passengers, like the option to book and handle large groups of passengers, they often have specific mealplans and partner hotels are familiar with the SFS system.</div>";
?>
    <span class="hasTip" title="<?php echo $certifiedTooltip;?>">
        <img src="<?php echo JURI::base();?>templates/<?php echo $app->getTemplate();?>/images/sfs-certified.png" style="display: block;margin: 20px 0 0 30px;" />
    </span>
<?php endif;?>
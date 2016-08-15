<?php
defined('_JEXEC') or die();
JHTML::_('behavior.modal');
$airline = SFactory::getAirline();
//var_dump($this->item);exit;
?>
<strong class="hotel-name"><?php echo $this->item->name?></strong>
<span class="star star<?php echo $this->item->star;?>"></span>
<?php if($this->item->distance):?>
<div class="distance-to-airport"><b>Distance to airport:</b> <span id="distance-to-airport-<?php echo $this->item->id?>"><?php echo $this->item->distance?></span><?php echo " ".$this->item->distance_unit ;?></div>
<?php else:?>
    <div class="distance-to-airport"><b>Distance to airport:</b> <span id="distance-to-airport-<?php echo $this->item->id?>">Unknown</span><?php echo " ".$this->item->distance_unit ;?></div>
<?php endif;?>
<?php if($this->item->telephone):?>
<div>
    <b>Telephone:</b> <span><?php echo $this->item->telephone; ?></span>
</div>
<?php endif;?>
<?php if($this->item->address):?>
<div>
    <b>Address:</b>
    <span><?php echo $this->item->address; ?></span>
</div>
<?php endif;?>
<?php if($this->item->isContractedRate) : ?>
<div class="contracted-checkbox">
<?php echo $airline->getAirlineName();?>
	contracted rate hotel
</div>
<?php endif;?>

<div>
	<?php echo $this->loadTemplate('item_transport');?>
</div>
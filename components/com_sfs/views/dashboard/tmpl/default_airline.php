<?php
// No direct access
defined('_JEXEC') or die;
// default layout for airline dashboard
$airline = SFactory::getAirline();

$airlineName = '';

if($airline->grouptype == 3) {
	$selectedAirline = $airline->getSelectedAirline();
	$airlineName = 	$selectedAirline->name;
} else {
	$airlineName = 	$airline->name;
}
?>
<div class="heading-block descript clearfix">
	<div class="heading-block-wrap">
		<h3><?php echo $airlineName;?>: Airline Dashboard</h3>
	</div>
</div>
<div id="sfs-wrapper" class="fs-14 main">
	
	<div class="">
	<div class="sfs-orange-wrapper">
	<div class="sfs-white-wrapper">

		Dear <?php echo $this->user->name;?>,
		
		<p>
		Welcome at Stranded Flight Solutions.
		</p>
		<p>
		Please select one of the below options.
		</p>
				
		<div style="padding:5px 0 15px" class="floatbox">
			<?php if(SFSAccess::check($this->user, 'a.admin')) :?>
			<div class="floatbox">
            	<ul class="button-primary-wrap">
                    <li>
	                    <a href="<?php echo JRoute::_('index.php?option=com_sfs&view=handler&layout=overview&Itemid='.JRequest::getInt('Itemid'));?>" class="btn orange lg">
	                    	<span>My Overview</span>
	                    </a>
                    </li>
                    <li>
	                    <a href="<?php echo JRoute::_('index.php?option=com_sfs&view=handler&layout=search&Itemid=119');?>" class="btn orange lg">
	                    	<span>Add Hotelrooms</span>
	                    </a>
                    </li>
                    <li>
                    	<a href="<?php echo JRoute::_('index.php?option=com_sfs&view=report&layout=market&Itemid='.JRequest::getInt('Itemid'));?>" class="btn orange lg">
                    		<span>Market</span>
                    	</a>
                    </li>
                </ul>
			</div>
			<?php else : ?>
			<div class="floatbox">
            	<ul class="button-primary-wrap">
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_sfs&view=match&Itemid='.JRequest::getInt('Itemid'));?>" class="btn orange lg">
							<span>Match</span>
						</a>
					</li>
                </ul>
			</div>
			
			<?php endif;?>
			
		</div>
	</div>
	</div>
	</div>


</div>
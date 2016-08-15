<?php
defined('_JEXEC') or die;
$taxi = SFactory::getTaxi();
?>
<div class="heading-block clearfix">
    <div class="heading-block-wrap">
        <h3><?php echo $taxi->name?>: Dashboard</h3>
    </div>
</div>
<div class="main sfs-wrapper fs-14">

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
					
			<div class="floatbox">
            	<ul class="button-primary-wrap">
                    <li>
	                    <a href="<?php echo JRoute::_('index.php?option=com_sfs&view=taxiprofile&layout=bookings&Itemid='.JRequest::getInt('Itemid'));?>" class="btn orange lg">
	                    	<span>Bookings</span>
	                    </a>
                    </li>
                    <li>
	                    <a href="<?php echo JRoute::_('index.php?option=com_sfs&view=taxiprofile&Itemid='.JRequest::getInt('Itemid'));?>" class="btn orange lg">
	                    	<span>Company data</span>
	                    </a>
                    </li>
                    <li>
                    	<a href="<?php echo JRoute::_('index.php?option=com_sfs&view=taxiprofile&layout=rates&Itemid='.JRequest::getInt('Itemid'));?>" class="btn orange lg">
                    		<span>Rates</span>
                    	</a>
                    </li>
                </ul>
			</div>
			
			
		</div>
	</div>
	</div>

</div>
<?php
// No direct access
defined('_JEXEC') or die;
$bus = SFactory::getBus();
?>
<div class="heading-block descript clearfix">
	<div class="heading-block-wrap">
		<h3><?php echo $bus->name?>: Dashboard</h3>
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
					
			<div class="floatbox">
            	<ul class="button-primary-wrap">
                    <li>
	                    <a href="<?php echo JRoute::_('index.php?option=com_sfs&view=bus&layout=bookings&Itemid='.JRequest::getInt('Itemid'));?>" class="btn orange lg">
	                    	<span>Bookings</span>
	                    </a>
                    </li>
                    <li>
	                    <a href="<?php echo JRoute::_('index.php?option=com_sfs&view=bus&Itemid='.JRequest::getInt('Itemid'));?>" class="btn orange lg">
	                    	<span>Company data</span>
	                    </a>
                    </li>
                    <li>
                    	<a href="<?php echo JRoute::_('index.php?option=com_sfs&view=bus&layout=profiles&Itemid='.JRequest::getInt('Itemid'));?>" class="btn orange lg">
                    		<span>Rates</span>
                    	</a>
                    </li>
                </ul>
			</div>
			
			
		</div>
	</div>
	</div>
	</div>


</div>
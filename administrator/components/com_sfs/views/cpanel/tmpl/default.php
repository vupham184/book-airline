<?php
// No direct access
defined('_JEXEC') or die;
?>
<div class="sfs-cpanel-left float-left">		

	<?php echo $this->loadTemplate('buttons');?>
	
	<?php echo $this->loadTemplate('roomloading');?>
	
	<?php //echo $this->loadTemplate('latest');?>		
		
</div>


<div class="sfs-cpanel-right"> 	
	<?php echo $this->loadTemplate('Reservations')?>		
</div>

<div class="clear"></div>

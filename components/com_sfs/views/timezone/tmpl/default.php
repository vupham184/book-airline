<?php
defined('_JEXEC') or die();
?>
<div class="heading-block descript clearfix">
	<div class="heading-block-wrap">
		<h3>Time zone</h3>
	</div>
</div>
<div id="sfs-wrapper" class="main">
	<div class="sfs-main-wrapper">
		<div class="sfs-orange-wrapper">
		
			<div class="sfs-white-wrapper" style="margin-bottom:25px;">
				It's important that we note the correct time and date please check the information below and adjust is neccesary.		
			</div>
			
			<div class="sfs-white-wrapper">
				
				Date and Time <br />
				
				Today it is: Date: <?php echo JHTML::_('date',JFactory::getDate()->toSQL(), JText::_('DATE_FORMAT_LC3'));?><br />
				
				Our timezone is: <?php echo $this->user->getParam('timezone');?><br />
				
				Local time is: <?php echo JHTML::_('date',JFactory::getDate()->toSQL(), JText::_('H:i A'));?>								
										
			</div>			
			
		</div>
	</div>
	
	<div>
		<a href="<?php echo JRoute::_('index.php?option=com_sfs&view=timezone&layout=edit&Itemid='.JRequest::getInt('Itemid'))?>" class="button">
			Edit
		</a>
	</div>
	
</div>
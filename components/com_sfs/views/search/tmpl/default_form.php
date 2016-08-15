<?php
// No direct access to this file
defined('_JEXEC') or die();
JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
?>

	
	<h3><?php echo JText::_("COM_SFS_SEARCH");?>:<?php JText::_('COM_SFS_AIR_FRANCE');?></h3>
	
	<div class="sfs-main-wrapper">
	<div class="sfs-white-wrapper">
	<div class="sfs-orange-wrapper">
	
        <form id="hotelSearchForm" action="<?php echo JRoute::_('index.php?option=com_sfs&view=search');?>" method="post" class="form-validate">
            <div class="register-field-short clear floatbox">
                <label><?php echo JText::_("COM_SFS_ROOMS_NEED");?>:</label>                            
                <input type="text" value="" size="1" name="rooms" id="rooms" class="required smaller-size" /><?php echo JText::_('COM_SFS_ROOMS'); ?>
            </div>        
            <div class="register-field-short clear floatbox">
                <label><?php echo JText::_("COM_SFS_START");?>:</label>
				<?php echo $this->start_date_list;?>
                <select name="hour_start" id="hour_start">
                    <?php
                    for($i=0;$i<=23;$i++){
                        echo '<option value="'.$i.'">'.$i.'.00 hrs</option>';
                    }
                    ?>
                </select>
                <input type="checkbox" name="hour_now" value="1" /><?php echo '&nbsp;&nbsp;'.JText::_('COM_SFS_NOW');?>
            </div>  
            <div class="register-field-short clear floatbox">
                <label><?php echo JText::_("COM_SFS_END");?>:</label>                            
				<?php echo $this->end_date_list;?>
                <select name="hour_end" id="hour_end">
                    <?php
                    for($i=0;$i<=23;$i++){
                        echo '<option value="'.$i.'">'.$i.'.00 hrs</option>';
                    }
                    ?>
                </select>
                <input type="checkbox" name="add_24h" value="1" /><?php echo '&nbsp;&nbsp;'.JText::_('COM_SFS_ADD_24H');?>	
            </div>   
            
            <button type="submit" name="B1" class="validate button"><?php echo JText::_('SEARCH'); ?></button><br />
			<input type="hidden" name="task" value="search.search" />			
			<input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid'); ?>" />                        				
                                   
		</form>
    
	<br />
	</div>
	</div>
	</div>




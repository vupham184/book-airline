<?php
defined('_JEXEC') or die;
?>
<div class="fieldset-fields" style="padding-top: 35px;padding-left:230px;" id="taxies">
	<?php if( count($this->taxiCompanies) ) : ?>
		<?php foreach ($this->taxiCompanies as $taxi ) : 
			
			$removeLink = 'index.php?option=com_sfs&view=taxi&tmpl=component&layout=remove&taxi_id='.$taxi->taxi_id.'&Itemid='.JRequest::getInt('Itemid');		
		?>		
			<div class="register-field clear floatbox">
				<label><?php echo JText::_('COM_SFS_TAXI_COMPANY_NAME');?> </label> 
				<div class="float-left" style="width: 180px;margin-left:20px;"><?php echo $taxi->name?></div>						
				<div class="float-left" style="width: 180px;margin-left:20px;">
					<a href="index.php?option=com_sfs&view=taxi&layout=rate&airline_id=<?php echo $taxi->airline_id?>&taxi_id=<?php echo $taxi->taxi_id?>&tmpl=component" class="modal" rel="{handler: 'iframe', size: {x: 900, y: 650}, onClose: function() {}}">
						Rate agreement
					</a>					
				</div>
			</div>		
		<?php endforeach;?>
	<?php else : ?>
	<div class="register-field clear floatbox" style="display:none;">
		<label><?php echo JText::_('COM_SFS_TAXI_COMPANY_NAME');?> </label> 
		<input type="text" name="companies[]" value=""  />
	</div>
	<?php endif;?>	
	
</div>

<div class="clear"></div>
	
<div class="midpaddingtop" style="display:none;">	
	<div class="s-button">
		<a class="s-button" id="newTaxiButton"><?php echo JText::_('COM_SFS_TAXI_ADD_NEW');?></a>	    
	</div>
</div>
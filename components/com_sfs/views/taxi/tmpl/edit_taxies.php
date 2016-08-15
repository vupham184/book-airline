<?php
defined('_JEXEC') or die;
?>
<div class="fs-16 midmarginbottom">
	<?php echo JText::_('COM_SFS_TAXI_YOUR_COMPANIES');?>
	<div class="fs-14 smallpaddingtop greycolor">
		<?php echo JText::_('COM_SFS_TAXI_YOUR_COMPANIES_DESC');?>
	</div>
</div>

<div class="fieldset-fields" style="padding-top: 15px;padding-left:230px;" id="taxies">
	<?php if( count($this->taxiCompanies) ) : ?>
		<?php foreach ($this->taxiCompanies as $taxi ) : 
			
			$removeLink = 'index.php?option=com_sfs&view=taxi&tmpl=component&layout=remove&taxi_id='.$taxi->taxi_id.'&Itemid='.JRequest::getInt('Itemid');		
		?>		
			<div class="register-field clear floatbox">
				<label><?php echo JText::_('COM_SFS_TAXI_COMPANY_NAME');?> </label> 
				<div class="float-left" style="width: 180px;"><?php echo $taxi->name?></div>
				<div class="float-left">
					<div class="s-button">
						<a class="s-button modal" rel="{handler: 'iframe', size: {x: 420, y: 150}}" href="<?php echo $removeLink?>">
							<?php echo JText::_('COM_SFS_TAXI_REMOVE');?>
						</a>
					</div>
				</div>				
			</div>		
			<?php if($taxi->email):?>
			<div class="register-field clear floatbox">
				<label style="text-indent: 25px;"><?php echo JText::_('COM_SFS_TAXI_COMPANY_EMAIL');?> </label> 
				<?php echo $taxi->email?>
			</div>
			<?php endif;?>
			<?php if($taxi->telephone):?>
			<div class="register-field clear floatbox">
				<label style="text-indent: 25px;"><?php echo JText::_('COM_SFS_TAXI_COMPANY_PHONE');?> </label> 
				<?php echo $taxi->telephone?>
			</div>
			<?php endif;?>
			<?php if($taxi->fax):?>
			<div class="register-field clear floatbox">
				<label style="text-indent: 25px;"><?php echo JText::_('COM_SFS_TAXI_COMPANY_FAX');?> </label> 
				<?php echo $taxi->fax?>
			</div>	
			<?php endif;?>
		<?php endforeach;?>
	<?php else : ?>
	<div class="register-field clear floatbox largemargintop">
		<label><?php echo JText::_('COM_SFS_TAXI_COMPANY_NAME');?> </label> 
		<input type="text" name="companies[0][name]" value="" class="required" />
	</div>
	<div class="register-field clear floatbox">
		<label class="textindent25"><?php echo JText::_('COM_SFS_TAXI_COMPANY_EMAIL');?> </label> 
		<input type="text" name="companies[0][email]" value="" class="validate-email" />
	</div>
	<div class="register-field clear floatbox">
		<label class="textindent25"><?php echo JText::_('COM_SFS_TAXI_COMPANY_PHONE');?> </label> 
		<input type="text" name="companies[0][telephone]" value="" class="validate-digits" />
	</div>
	<div class="register-field clear floatbox">
		<label class="textindent25"><?php echo JText::_('COM_SFS_TAXI_COMPANY_FAX');?> </label> 
		<input type="text" name="companies[0][fax]" value="" class="validate-digits" />
	</div>
	<?php endif;?>	
	
</div>

<div class="clear"></div>
	
<div class="midpaddingtop">	
	<div class="s-button">
		<a class="s-button" id="newTaxiButton"><?php echo JText::_('COM_SFS_TAXI_ADD_NEW');?></a>	    
	</div>
</div>
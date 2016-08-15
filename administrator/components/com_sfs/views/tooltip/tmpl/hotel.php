<?php
defined('_JEXEC') or die;
JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.modal');

if($this->tooltip)
{
	$tooltip = $this->tooltip;
} else {
	$tooltip = array();
}
?>

<form action="<?php echo JRoute::_('index.php?option=com_sfs&view=tooltip&layout=airline'); ?>" method="post" name="adminForm" id="item-form" class="form-validate">

<div class="width-100">	
<?php echo JHtml::_('tabs.start','airline-tooltip-tabs', array('useCookie'=>1)); ?>

	<?php echo JHtml::_('tabs.panel','Rooms Management', 'hotel-rooms-management'); ?>
	<table>
		<?php echo $this->getField('Single/Double rooms authorized','roomloading_sd_authorized',$tooltip);?>
		<?php echo $this->getField('Inventory','inventory',$tooltip);?>
		<?php echo $this->getField('Ranking','ranking',$tooltip);?>
		<?php echo $this->getField('Room Rate','room_rate',$tooltip);?>
		<?php echo $this->getField('Gross rate (in GBP)','roomloading_gross_rate',$tooltip);?>
		<?php echo $this->getField('Transport included','roomloading_transport_included',$tooltip);?>
		<?php echo $this->getField('Transport always included','roomloading_transport_always_included',$tooltip);?>
		<?php echo $this->getField('Save Prices','roomloading_save',$tooltip);?>
		<?php echo $this->getField('Check Ranking','roomloading_check_ranking',$tooltip);?>	
		<?php echo $this->getField('Transport Check','transport_check',$tooltip);?>	
	</table>  
	
	<?php echo JHtml::_('tabs.panel','Block Overview', 'hotel-Block-Overview'); ?>

	<?php echo JHtml::_('tabs.panel','Blocked / Booked rooms', 'airline-booked-rooms'); ?>
	<table>
		<?php echo $this->getField('Quick Selection Open','quick_selection_open',$tooltip);?>	
		<?php echo $this->getField('Quick Selection Pending','quick_selection_pending',$tooltip);?>
		<?php echo $this->getField('Quick Selection Tentative','quick_selection_tentative',$tooltip);?>
		<?php echo $this->getField('Quick Selection Challenged','quick_selection_challenged',$tooltip);?>
		<?php echo $this->getField('Quick Selection Approved','quick_selection_approved',$tooltip);?>
		<?php echo $this->getField('Quick Selection Archived','quick_selection_archived',$tooltip);?>
		<?php echo $this->getField('Search Date From','search_date_from',$tooltip);?>
		<?php echo $this->getField('Search Date To','search_date_to',$tooltip);?>
		<?php echo $this->getField('Block Code','block_code',$tooltip);?>
		<?php echo $this->getField('Status Select','status_select',$tooltip);?>
		<?php echo $this->getField('Button Search','button_search',$tooltip);?>

		<?php echo $this->getField('Button Print','button_print',$tooltip);?>
		<?php echo $this->getField('Customer Information','customer_information',$tooltip);?>
		<?php echo $this->getField('Blockcode Information','blockcode_information',$tooltip);?>
		<?php echo $this->getField('Estimate Information','estimate_information',$tooltip);?>
		<?php echo $this->getField('Blocked Rooms','blocked_rooms',$tooltip);?>
		<?php echo $this->getField('Mealplan List','mealplan_list',$tooltip);?>
		<?php echo $this->getField('Passengers List','passengers_list',$tooltip);?>
		<?php echo $this->getField('Button Send Message','button_send_message',$tooltip);?>		
	</table>  	
	

	<?php echo JHtml::_('tabs.panel','Dashboard', 'hotel-management'); ?>
	<table>
		<?php echo $this->getField('Support Info','support_info',$tooltip);?>			
		<?php echo $this->getField('Button Room Management','button_room_management',$tooltip);?>
		<?php echo $this->getField('Button Block Overview','button_block_overview',$tooltip);?>
		<?php echo $this->getField('Button Rooming List Loading','button_rooming_list_loading',$tooltip);?>
		<?php echo $this->getField('Button Hotel Data','button_hotel_data',$tooltip);?>
	</table>

	
	<?php echo JHtml::_('tabs.panel','Rooming List Loading', 'hotel-RoomingListLoading'); ?>

	<?php echo JHtml::_('tabs.panel','Reports', 'hotel-reports'); ?>
	<table>
		<?php echo $this->getField('Select Date Form','select_date_form',$tooltip);?>
		<?php echo $this->getField('Button Generate','button_generate',$tooltip);?>
	</table>

	<?php echo JHtml::_('tabs.panel','Sign Up', 'hotel-signup'); ?>
	<table>
		<?php echo $this->getField('Select Date Form','select_date_form',$tooltip);?>	
		<?php echo $this->getField('Direct Fax Field','direct_fax_field',$tooltip);?>
		<?php echo $this->getField('Taxes Field','taxes_field',$tooltip);?>
		<?php echo $this->getField('Merchant Fee Field','merchant_fee_field',$tooltip);?>
		<?php echo $this->getField('Free Release Policy','free_release_policy',$tooltip);?>
	</table>
		          
	
<?php echo JHtml::_('tabs.end'); ?>

	<div class="current">
		<button type="submit">Save</button>
	</div>
	
	<input type="hidden" name="task" value="tooltip.saveTooltip" />
	<input type="hidden" name="tooltip_type" value="hotel" /> 
	<input type="hidden" name="option" value="com_sfs" />					
	<?php echo JHtml::_('form.token'); ?>
	<div class="clr"></div>
	
</div>
</form>
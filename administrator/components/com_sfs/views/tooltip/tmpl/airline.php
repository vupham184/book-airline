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

	<?php echo JHtml::_('tabs.panel','Hotel Search', 'hotel-search-result'); ?>
	<table id="step1">
		<?php echo $this->getField('Start Need Room Field','start_need_room_field',$tooltip);?>
		<?php echo $this->getField('Start Date Field','search_start_date',$tooltip);?>
        <?php echo $this->getField('Change Airport','change_airport',$tooltip);?>
        <?php echo $this->getField('Geo Map','geo_map',$tooltip);?>
		<?php echo $this->getField('Your Request Left Area','search_request_left',$tooltip);?>
		<?php echo $this->getField('Your Request Right Area','search_request_right',$tooltip);?>
		<?php echo $this->getField('Sort by','search_sort_by',$tooltip);?>
		<?php echo $this->getField('Hotel details','hotel_details',$tooltip);?>
		<?php echo $this->getField('Mealplan','mealplan',$tooltip);?>
		<?php echo $this->getField('Item rooms','item_rooms',$tooltip);?>
		<?php echo $this->getField('Estimated Charges','estimated_charges',$tooltip);?>
		<?php echo $this->getField('Free Release Percentage','free_release_percentage',$tooltip);?>
		<?php echo $this->getField('Link to extend search','link_to_extend_search',$tooltip);?>
		<?php echo $this->getField('Website','search_result_website',$tooltip);?>
		<?php echo $this->getField('Button Booking','button_booking',$tooltip);?>
		<?php echo $this->getField('Button Search','button_search',$tooltip);?>
	</table>       
	
	<?php echo JHtml::_('tabs.panel','Match', 'airline-match'); ?>
	<table>
		<?php echo $this->getField('Previous Night','match_prev_night',$tooltip);?>
		<?php echo $this->getField('Next Night','match_next_night',$tooltip);?>
		<?php echo $this->getField('For the night starting','match_night_range',$tooltip);?>
		<?php echo $this->getField('Rooms Type','match_room_type',$tooltip);?>
		<?php echo $this->getField('Select Passengers','select_passengers',$tooltip);?>
		<?php echo $this->getField('Select All Passengers','select_all_passengers',$tooltip);?>
		<?php echo $this->getField('Hotel Match','hotel_match',$tooltip);?>
		<?php echo $this->getField('Hotel Match Item','hotel_match_item',$tooltip);?>
		<?php echo $this->getField('Issued Vouchers Overview','issued_vouchers_overview',$tooltip);?>
		<?php echo $this->getField('Amount Of Rooms','amount_of_rooms',$tooltip);?>
		<?php echo $this->getField('Match Button','match_submit',$tooltip);?>
		<?php echo $this->getField('Popup Add comment','popup_add_comment',$tooltip);?>
		<?php echo $this->getField('Popup Insert Name','popup_insert_name',$tooltip);?>
		<?php echo $this->getField('Popup Flight Number','popup_flight_number',$tooltip);?>
		<?php echo $this->getField('Popup Email Voucher','popup_email_voucher',$tooltip);?>
		<?php echo $this->getField('Popup Voucher Code','popup_voucher_code',$tooltip);?>
	</table>  
	
	<?php echo JHtml::_('tabs.panel','My Overview', 'airline-overview'); ?>
	<table>
		<?php echo $this->getField('Previous Night','overview_prev_night',$tooltip);?>
		<?php echo $this->getField('Next Night','overview_next_night',$tooltip);?>
		<?php echo $this->getField('For the night starting','overview_night_range',$tooltip);?>
		<?php echo $this->getField('Rooms Type','overview_room_type',$tooltip);?>
		<?php echo $this->getField('Delete passengers/flights','overview_delete_flights',$tooltip);?>
		<?php echo $this->getField('Select Passengers Overview','select_passengers_overview',$tooltip);?>
		<?php echo $this->getField('Hotel Overview','hotel_overview',$tooltip);?>
		<?php echo $this->getField('Button Add Passenger','button_add_passenger',$tooltip);?>
		<?php echo $this->getField('Add Hotels','overview_add_hotels',$tooltip);?>			
	</table>  
	
	<?php echo JHtml::_('tabs.panel','Vouchers Create', 'airline-voucher'); ?>
	<table>
		<?php echo $this->getField('Create voucher date','create_voucher_date',$tooltip);?>
		<?php echo $this->getField('Select voucher code','select_voucher_code',$tooltip);?>
		<?php echo $this->getField('Flight number','flight_number',$tooltip);?>
		<?php echo $this->getField('Block code Voucher number','block_code_voucher_number',$tooltip);?>
		<?php echo $this->getField('Seat number','seat_number',$tooltip);?>
		<?php echo $this->getField('Mealplan','mealplan_choice',$tooltip);?>
		<?php echo $this->getField('Payment status','payment_status',$tooltip);?>
		<?php echo $this->getField('Created name','created_name',$tooltip);?>
		<?php echo $this->getField('Room type status','room_type_status',$tooltip);?>
		<?php echo $this->getField('Group status','group_status',$tooltip);?>
		<?php echo $this->getField('Taxi status','taxi_status',$tooltip);?>
		<?php echo $this->getField('Created print date','created_print_date',$tooltip);?>
		<?php echo $this->getField('Button print hotel voucher','button_print_hotel_voucher',$tooltip);?>
		<?php echo $this->getField('Button cancel hotel voucher','button_cancel_hotel_voucher',$tooltip);?>
		<?php echo $this->getField('Total info','total_info',$tooltip);?>
		<?php echo $this->getField('Button Back','button_back',$tooltip);?>
	</table>

	<?php echo JHtml::_('tabs.panel','Add flights', 'airline-flights'); ?>
	<table>
		<?php echo $this->getField('Check passengers stay at night','check_passengers_stay_at_night',$tooltip);?>
		<?php echo $this->getField('Number of stranded passengers','number_of_stranded_passengers',$tooltip);?>
		<?php echo $this->getField('Flight Date Range','flight_date_range',$tooltip);?>
		<?php echo $this->getField('Flight Class','flight_class',$tooltip);?>
		<?php echo $this->getField('Add Comment Link','flight_add_comment_link',$tooltip);?>					
		<?php echo $this->getField('IATA stranded code','flight_delay_code',$tooltip);?>
		<?php echo $this->getField('Button Upload','btn_upload',$tooltip);?>		
	</table>       
	
		


	<?php echo JHtml::_('tabs.panel','Reports', 'airline-report'); ?>
	<?php echo JHtml::_('tabs.panel','Market overview', 'airline-market'); ?>

	
	 

	<?php echo JHtml::_('tabs.panel','Trace Passengers', 'airline-Trace-Passengers'); ?>
	<?php echo JHtml::_('tabs.panel','Book Transportation', 'airline-book-transportation'); ?>

	<?php echo JHtml::_('tabs.panel','Airline Data', 'airline-data'); ?>
	<?php echo JHtml::_('tabs.panel','Sign Up', 'airline-signup'); ?>
	<table>
		<?php echo $this->getField('Airline Code Field','airline_code_field',$tooltip);?>
		<?php echo $this->getField('Timezone Field','timezone_field',$tooltip);?>
		<?php echo $this->getField('Address Field','address_field',$tooltip);?>
		<?php echo $this->getField('Button Copy Address','button_copy_address',$tooltip);?>
		<?php echo $this->getField('Bill Detail Form','bill_detail_form',$tooltip);?>
		<?php echo $this->getField('Main Contact Form','main_contact_form',$tooltip);?>
		<?php echo $this->getField('Team Member Form','team_member_form',$tooltip);?>
		<?php echo $this->getField('Button Add Contact','button_add_contact',$tooltip);?>
	</table>
	

<?php echo JHtml::_('tabs.end'); ?>

	<div class="current">
		<button type="submit">Save</button>
	</div>
	
	<input type="hidden" name="task" value="tooltip.saveTooltip" />
	<input type="hidden" name="tooltip_type" value="airline" /> 
	<input type="hidden" name="option" value="com_sfs" />					
	<?php echo JHtml::_('form.token'); ?>
	<div class="clr"></div>
	
</div>
</form>
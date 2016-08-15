<?php
defined('_JEXEC') or die;
?>

<div id="sfsVoucherSelectRooms"  style="display:none;">	

			<div  class="modal-block">
				<div class="modal-inner">
						
					<div id="alertMsg2" class="uk-alert uk-alert-danger" style="display:none;"></div>
					
					<h3>Please select the number of rooms</h3>
					
					
						<div id="ajaxResponse"></div>
						<table cellspacing="10" cellpadding="10">
							<tr>
								<td>needed</td>
								<td>available</td>
								<td>&nbsp;</td>
							</tr>
							<tr id="tr_total_single_rooms">
								<td>
									<input type="text" name="total_single_rooms" id="total_single_rooms" style="width:50px;" class="inputbox validate-integer">
								</td>
								<td align="center" id="sroomAvailable"></td>
								<td>Single rooms</td>
							</tr>
							<tr >
								<td>
									<input type="text" name="total_double_rooms" id="total_double_rooms" style="width:50px;" class="inputbox validate-integer">
								</td>
								<td align="center" id="sdroomAvailable"></td>
								<td id="td_total_double_rooms">Single/Double rooms</td>
							</tr>
							<tr>
								<td>
									<input type="text" name="total_triple_rooms" id="total_triple_rooms" style="width:50px;" class="inputbox validate-integer">
								</td>
								<td align="center" id="troomAvailable"></td>
								<td>Triple rooms</td>
							</tr>
							<tr id="tr_quad_room_available">
								<td>
									<input type="text" name="total_quad_rooms" id="total_quad_rooms" style="width:50px;" class="inputbox validate-integer">
								</td>
								<td align="center" id="qroomAvailable"></td>
								<td>Quad rooms</td> 
							</tr>
						</table>
						
						<div style="margin-left: 250px;">
							<div id="ajax-Spinner1" class="float-left"></div>
							
						    	<button type="button" id="selectRoomButton"  class="btn orange lg">Select</button>
					        
			        	</div>							
				</div>
				<div class="wrap-modal"></div>
			</div>

					

</div>

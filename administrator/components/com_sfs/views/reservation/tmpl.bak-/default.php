<?php
defined('_JEXEC') or die;
JHtml::_('behavior.tooltip');
JHtml::_('behavior.modal');
$isWS = $this->reservation->ws_room;
$initial_rooms = 0;
foreach ($this->initial_rooms as $value) :
    $initial_rooms += $value;
endforeach;
$picked_rooms = 0;
foreach ($this->picked_rooms as $value) :
    $picked_rooms += $value;
endforeach;
$freeRooms = ( $initial_rooms * (int)$this->reservation->percent_release_policy) / 100;
$freeRooms = (int)$freeRooms;
$remainingRooms = $initial_rooms - $picked_rooms - $freeRooms;
?>
<style>
<!--
h3{padding:4px 0;margin:0}
.customer-information{font-size:13px;line-height:170%;}
.approved{color:green;}
-->
</style>


<div id="reservation-detail">
<?php if ( !isset($this->fakeVoucher) && (int)$remainingRooms > 0) :?>
<fieldset>
    <div class="fltrt">
        <a class="modal" rel="{handler: 'iframe', size: {x: 800, y: 550}, onClose: function() {}}" href="index.php?option=com_sfs&view=reservation&layout=issuevoucher&id=<?php echo $this->reservation->id?>&tmpl=component">
           <button type="button">Issue voucher</button>
        </a>
    </div>
    <div class="configuration">
        Issue soft block code
    </div>
</fieldset>
<?php endif;?>
<div class="width-50 fltlft">

	<?php echo $this->loadTemplate('blockdetail'); ?>
		
	
	<fieldset class="adminform">
		<legend>Mealplans Details</legend>	
		<table class="adminlist">
			<thead>
				<tr>
					<th><strong>Mealplans</strong></th>
					<th><strong>Nett rates</strong></th>
					<th><strong>Picked up mealplans</strong></th>					
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>Breakfast price:</td>
					<td><?php echo floatval($this->reservation->breakfast) > 0 ? $this->reservation->breakfast : 'N/A' ;?></td>					
					<td><?php echo floatval($this->reservation->breakfast) > 0  ? $this->picked_breakfasts : 'N/A' ;?></td>
				</tr>
				<tr>
					<td>Lunch price:</td>
					<td><?php echo floatval($this->reservation->lunch) > 0  ? $this->reservation->lunch : 'N/A';?></td>
					<td><?php echo floatval($this->reservation->lunch) > 0  ? $this->picked_lunchs : 'N/A' ;?></td>
				</tr>
				<tr>
					<td>Dinner price:</td>
					<td><?php echo floatval($this->reservation->mealplan) > 0 ? $this->reservation->mealplan : 'N/A';?></td>
					<td><?php echo floatval($this->reservation->mealplan) > 0 ? $this->picked_mealplans : 'N/A' ;?></td>
				</tr>				
			</tbody>
		</table>	
	</fieldset>		
</div>

<div class="width-50 fltlft">

	<fieldset class="adminform">
		<legend>Estimated Charges</legend>		
		<table class="adminlist">
			<tbody>
				<tr>
					<td width="300"><h3>Estimated room charge</h3></td>
					<td><?php echo $this->total_room_charge;?></td>
				</tr>
				<tr>
					<td><h3>Estimated mealplan charge</h3></td>
					<td><?php echo $this->total_mealplan_charge ;?></td>
				</tr>
				<tr>
					<td><h3>Estimated invoice charge</h3></td>
					<td><?php echo $this->total_invoice_charge;?></td>
				</tr>
				<tr>
					<td><h3>Currency</h3></td>
					<td><?php echo $this->hotel->currency;?></td>
				</tr>								
			</tbody>
		</table>				
	</fieldset>

	<?php if(!$isWS):?>
	<fieldset class="adminform">
		<legend>Rooms Details</legend>		
		<table class="adminlist">
			<thead>
				<tr>
					<th><strong>Rooms</strong></th>
					<th><strong>Nett rates</strong></th>
					<th><strong>Picked up rooms</strong></th>
					<th><strong>Initial rooms</strong></th>				
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>Single price:</td>
					<td><?php echo $this->reservation->s_rate;?></td>
					<td><?php echo $this->picked_rooms[1];?></td>
					<td><?php echo $this->initial_rooms[1];?></td>
				</tr>
				<tr>
					<td>Double price:</td>
					<td><?php echo $this->reservation->sd_rate;?></td>
					<td><?php echo $this->picked_rooms[2];?></td>
					<td><?php echo $this->initial_rooms[2];?></td>
				</tr>
				<tr>
					<td>Triple price:</td>
					<td><?php echo $this->reservation->t_rate;?></td>
					<td><?php echo $this->picked_rooms[3];?></td>
					<td><?php echo $this->initial_rooms[3];?></td>
				</tr>
				<tr>
					<td>Quad price:</td>
					<td><?php echo $this->reservation->q_rate;?></td>
					<td><?php echo $this->picked_rooms[4];?></td>
					<td><?php echo $this->initial_rooms[4];?></td>
				</tr>				
			</tbody>
		</table>		
		<?php if( (int) $this->reservation->percent_release_policy > 0):?>
			<div class="clr"></div>
			<div style="float:right;padding: 15px 0 0 0;font-size:15px;">
			free release percentage: <?php echo $this->reservation->percent_release_policy;?>%
			</div>
		<?php endif;?>
		<?php
            echo '<div style="padding: 15px 0 0 0;font-size:15px;">Total initial blocked rooms: '.$initial_rooms.'</div>';
		?>  	
		<?php
            echo '<div style="padding: 0;font-size:15px;">Total picked up (used) rooms: '.$picked_rooms.'</div>';
        ?> 	
	</fieldset>
	<?php else:?>
	<fieldset class="adminform">
		<legend>Rooms Details</legend>
		<table class="adminlist">
			<thead>
			<tr>
				<th><strong>Rooms</strong></th>
				<th><strong>Ws rates</strong></th>
				<th><strong>Sales price</strong></th>
				<th><strong>Picked up rooms</strong></th>
				<th><strong>Initial rooms</strong></th>
			</tr>
			</thead>
			<tbody>
			<tr>
				<td>Single price:</td>
				<td><?php echo $this->reservation->ws_s_rate;?></td>
				<td><?php echo number_format(ceil($this->reservation->s_rate),2);?></td>
				<td><?php echo $this->initial_rooms[1];?></td>
				<td><?php echo $this->initial_rooms[1];?></td>
			</tr>
			<tr>
				<td>Double price:</td>
				<td><?php echo $this->reservation->ws_sd_rate;?></td>
				<td><?php echo number_format(ceil($this->reservation->sd_rate),2);?></td>
				<td><?php echo $this->initial_rooms[2];?></td>
				<td><?php echo $this->initial_rooms[2];?></td>
			</tr>
			<tr>
				<td>Triple price:</td>
				<td><?php echo $this->reservation->ws_t_rate;?></td>
				<td><?php echo number_format(ceil($this->reservation->t_rate),2);?></td>
				<td><?php echo $this->initial_rooms[3];?></td>
				<td><?php echo $this->initial_rooms[3];?></td>
			</tr>
			<tr>
				<td>Quad price:</td>
				<td><?php echo $this->reservation->ws_q_rate;?></td>
				<td><?php echo number_format(ceil($this->reservation->q_rate),2);?></td>
				<td><?php echo $this->initial_rooms[4];?></td>
				<td><?php echo $this->initial_rooms[4];?></td>
			</tr>
			</tbody>
		</table>
		<?php if( (int) $this->reservation->percent_release_policy > 0):?>
			<div class="clr"></div>
			<div style="float:right;padding: 15px 0 0 0;font-size:15px;">
				free release percentage: <?php echo $this->reservation->percent_release_policy;?>%
			</div>
		<?php endif;?>
		<?php
		echo '<div style="padding: 15px 0 0 0;font-size:15px;">Total initial blocked rooms: '.$initial_rooms.'</div>';
		echo '<div style="padding: 0;font-size:15px;">Total picked up (used) rooms: '.$initial_rooms.'</div>';
		?>
	</fieldset>

	<?php endif;?>
		
	
</div>

<div class="clr"></div>

<div class="width-100 fltlft">
	<?php echo $this->loadTemplate('vouchers'); ?>
</div>

<div class="clr"></div>

<div class="width-33 fltlft">
	<fieldset class="adminform">
		<legend>Passengers</legend>
		
		<table class="adminlist" width="100%">
			<tr>
				<th>#</th>
				<th>First Name</th>
				<th>Lastname</th>
				<th>Voucher</th>			
			</tr>
			<?php 
			    $i = 0;
			    foreach ( $this->passengers as $item ) : ?>	
			    <tr>
			        <td><?php echo ++$i; ;?></td>
			        <td><?php echo $item->first_name;?></td>
			        <td><?php echo $item->last_name ;?></td>
			        <td><?php echo $item->code ;?></td>
			    </tr>			
		    <?php endforeach ; ?>
		</table>		
		
	</fieldset>
</div>	

<div class="width-33 fltlft">
	<fieldset class="adminform">
		<legend>Trace Passengers</legend>
		
		<table class="adminlist" width="100%">
			<tr>
				<th>Voucher number</th>
				<th>First name</th>
				<th>Last name</th>				
				<th>Phone passenger</th>			
				
			</tr>
			<?php
			if(count($this->tracePassengers)):		
			foreach ($this->tracePassengers as $item) : ?>
				<tr>
					<td><?php echo $item->voucher_code?></td>
					<td><?php echo $item->first_name?></td>
					<td><?php echo $item->last_name?></td>															
					<td><?php echo $item->phone_number?></td>										
				</tr>
			<?php
			endforeach;
			endif;
			?>
		</table>		
	</fieldset>
</div>

<div class="width-34 fltlft">
	<fieldset class="adminform">
		<legend>Voucher comments</legend>
			<table class="adminlist">
			<tr>
				<th width="120">Voucher number</th>
				<th>Comment</th>			
			</tr>
			<?php 
			if(count($this->vouchers)) :
			    $i = 0;
			    foreach ($this->vouchers as $item) : ?>
			    	<?php if($item->comment):?>
			    	<tr>	
			    		<td><?php echo $item->code?></td>
			    		<td><?php echo $item->comment?></td>		    		  		
			  		</tr>
			  		<?php endif;?>
		    	<?php 
		    	endforeach ; 
		    endif;
		    ?>					
			</table>
	</fieldset>
</div>

<div class="clr"></div>
	
<div class="width-50 fltlft">
	<fieldset class="adminform">
		<legend>Messages</legend>
		<?php
		if( count($this->messages) ) :
		foreach ($this->messages as $m) : 
		?>
			<div style="padding:10px;font-size:12px;<?php if($m->type==1) echo 'background:#F4F4F4;';?>">
				<div>
				<?php if($m->type==1) : ?>
					<i>From Airline by <?php echo $m->from_name?>, Posted at <?php echo JHtml::_('date',$m->posted_date,JText::_('DATE_FORMAT_LC2'))?></i>
				<?php else:?>
					<i>From Hotel by <?php echo $m->from_name?>, Posted at <?php echo JHtml::_('date',$m->posted_date,JText::_('DATE_FORMAT_LC2'))?></i>
				<?php endif;?>
				</div>			
				<?php echo $m->body;?>
			</div>
		<?php 
		endforeach;
		endif;
		?>
	</fieldset>
</div>

<div class="width-50 fltlft">
<fieldset class="adminform">
	<legend>Notes</legend>

		<?php 
		if(count($this->notes)) :
		    $i = 0;
		    foreach ( $this->notes as $item ) : ?>
		    	<div>	
		  		<?php echo $item->notes;?>
		  		</div>
	    	<?php 
	    	endforeach ; 
	    endif;
	    ?>
						
		<a rel="{handler: 'iframe', size: {x: 750, y: 650}, onClose: function() {}}" href="index.php?option=com_sfs&amp;view=reservation&amp;layout=notes&amp;tmpl=component&amp;id=<?php echo $this->reservation->id?>" class="modal">	
			Add Note
		</a>
	
</fieldset>
</div>	


</div>
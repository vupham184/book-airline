<?php
defined('_JEXEC') or die();
?>
	<tr class="r-heading">
		<td width="40%"><span class="r-heading roomtype">Rooms</span></td>
		<td><span class="r-heading roomtype">Rate</span></td>
		<td><span class="r-heading roomtype">Total</span></td>
	</tr>
	
	<?php 
	    //Single Rooms
		if((int)$this->item->s_room_total) : 
		
		$price = floatval($this->item->s_room_rate);
		if( $this->item->isContractedRate && $this->item->contracted_s_rate > 0 ) {
			$price = $this->item->contracted_s_rate;
		}			
		?>

		<tr class="room-item">
	    	<td>S Room:</td>
	    	<td><?php echo $this->item->currency_symbol.number_format($price, 2, ".", ""); ?></td>
	        <td><?php echo $this->item->s_room_total; ?></td>
	    </tr>
	    <?php else : ?>
	    <tr class="room-item">
	    	<td>S Room:</td>
	    	<td>N/A</td>
	        <td>N/A</td>
	    </tr>				    

	    <?php endif; ?>


	<?php 
	// Single/Double Rooms	
	if((int)$this->item->sd_room_total) :
		$price = floatval($this->item->sd_room_rate);
		if( $this->item->isContractedRate && $this->item->contracted_sd_rate > 0 ) {
			$price = $this->item->contracted_sd_rate;
		}
	?>	
	<tr class="room-item">
    	<td>S/D Room:</td>
    	<td><?php echo $this->item->currency_symbol.number_format($price, 2, ".", ""); ?></td>
        <td><?php echo $this->item->sd_room_total; ?></td>
    </tr>
    <?php else : ?>
    <tr class="room-item">
    	<td>S/D Room:</td>
        <td>N/A</td>
        <td>N/A</td>
    </tr>
    <?php endif; ?>
    
    <?php 
    // Triple Rooms   
    if((int)$this->item->t_room_total) : 
    	$price = floatval($this->item->t_room_rate);
		if( $this->item->isContractedRate && $this->item->contracted_t_rate > 0 ) {
			$price = $this->item->contracted_t_rate;
		}
    ?>
    <tr class="room-item">
    	<td class="result-row-room-title">T Room:</td>
    	<td class="result-row-room-price"><?php echo $this->item->currency_symbol.number_format($price, 2, ".", ""); ?></td>
        <td class="result-row-room-total"><?php echo $this->item->t_room_total; ?></td>
    </tr>
    <?php else: ?>
	<tr class="room-item">
    	<td class="result-row-room-title">T Room:</td>
        <td class="result-row-room-price">N/A</td>
        <td class="result-row-room-total">N/A</td>
    </tr>				    
    <?php endif;?>
    
    <?php 
        //Quad Rooms
		if((int)$this->item->q_room_total) : 
			$price = floatval($this->item->q_room_rate);
			if( $this->item->isContractedRate && $this->item->contracted_q_rate > 0 ) {
				$price = $this->item->contracted_q_rate;
			}	
		?>
		<tr class="room-item">
	    	<td class="result-row-room-title">Q Room:</td>
	    	<td class="result-row-room-price">
        		<?php echo $this->item->currency_symbol.number_format($price, 2, ".", ""); ?>
       		</td>
	        <td class="result-row-room-total">
	        	<?php echo $this->item->q_room_total; ?>
            </td>
	    </tr>
	    <?php else : ?>
	    <tr class="room-item">
	    	<td class="result-row-room-title">Q Room:</td>
	    	<td class="result-row-room-price">N/A</td>
	        <td class="result-row-room-total">N/A</td>
	    </tr>				    
	    <?php endif; ?>
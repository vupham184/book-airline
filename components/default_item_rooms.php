<?php
defined('_JEXEC') or die();
$rate_max = $this->item->contracted_max_rate;
?>

<?php
	$curr = (int) $this->item->currency_id;		
	$db = JFactory::getDbo();

	$query = 'SELECT code, exchange_rate FROM #__sfs_currency ';	
	$query .= ' WHERE id=' . $curr;
	$db->setQuery($query);	
	$result = $db->loadObject();

	
	$query = 'SELECT * FROM #__sfs_currency WHERE code = "USD"';
	$db->setQuery($query);	
	$code = $db->loadObject();	
?>


<script type="text/javascript">
	function roomSD(num){
		var room_sd =document.getElementsByName("sd_room")[0].value;			
		if(parseInt(room_sd) > parseInt(num)){
			document.getElementsByName("sd_room")[0].value = parseInt(num);
		}
	}
	function roomS(num){
		var room_sd =document.getElementsByName("s_room")[0].value;		
		if(parseInt(room_sd) > parseInt(num)){
			document.getElementsByName("s_room")[0].value = parseInt(num);
		}
	}
	function roomT(num){
		var room_sd =document.getElementsByName("t_room")[0].value;			
		if(parseInt(room_sd) > parseInt(num)){
			document.getElementsByName("t_room")[0].value = parseInt(num);
		}
	}
	function roomQ(num){
		var room_sd =document.getElementsByName("q_room")[0].value;		
		if(parseInt(room_sd) > parseInt(num)){
			document.getElementsByName("q_room")[0].value = parseInt(num);
		}
	}

</script>
	<tr class="r-heading">
		<td width="40%"><span class="r-heading roomtype">rooms</span></td>
        <td width="25%"><span class="r-heading roomtype">price</span></td>
		<td width="20%"><span class="r-heading roomtype">available</span></td>
        <td width="15%"><span class="r-heading roomtype">needed</span></td>
	</tr>
	
	<?php
    $needed  = (int)$this->state->get('filter.rooms');
    $s_needed = 0;   	
	//Single Rooms
	if( !empty($this->item->single_room_available) && (int)$this->item->single_room_available == 1 ) :?>
		<?php 
		
		if((int)$this->item->s_room_total) : 
				
		if(floatval($this->item->contracted_s_rate) == ""){
			$price = floatval($this->item->s_room_rate);
		}else{
			if(floatval($this->item->s_room_rate) < floatval($this->item->contracted_s_rate) && $rate_max->srate == "false" ){
    			$price = floatval($this->item->contracted_s_rate);
	    	}elseif (floatval($this->item->s_room_rate) < floatval($this->item->contracted_s_rate) && $rate_max->srate == "true") {
	    		$price = floatval($this->item->s_room_rate);
	    	}elseif ($this->item->s_room_rate > $this->item->contracted_s_rate) {
	    		$price = floatval($this->item->contracted_s_rate);
	    	}
		}

		
			
		if($result){
			$conv = floatval($price) / floatval($result->exchange_rate);
			$price = round($conv, 2);
			$prideUsd = round($conv * floatval($code->exchange_rate), 2);
		}else{
			$price = floatval($this->item->s_room_rate);
			$prideUsd = round($price * floatval($code->exchange_rate), 2);
		}
							
		//$price = floatval($this->item->s_room_rate);
		// if( $this->item->isContractedRate && $this->item->contracted_s_rate > 0 ) {
		// 	$price = floatval($this->item->contracted_s_rate);
		// 	$prideUsd = round($price * floatval($code->exchange_rate), 2);
		// }
        if( $this->item->s_room_total >= (int)$this->state->get('filter.rooms') )
        {
            $s_needed  = $needed;
        } else {
            $s_needed  = (int)$this->item->s_room_total;
        }

		?>
		
		<tr class="room-item">
	    	<td>S Room:</td>
            <!-- <td class="result-row-room-price"><?php echo $this->item->currency_symbol." ".number_format($price, 2, ".", ""); ?></td> -->
            <td class="result-row-room-price"><?php echo $this->item->currency_symbol." ". number_format($price, 2, ".", "") . 
        			"<br /><span class='hoverRate' 
        			title='Test' style='font-size:12px; font-weight:400;'> USD ". number_format($prideUsd, 2, ".", "") . "</span>"; ?></td>
            <td class="result-row-room-total"><?php echo $this->item->s_room_total; ?></td>
	        <td class="room-item-last-cell">
				<input type="text" name="s_room" onchange="roomS(<?php echo $this->item->s_room_total;?>)" value="<?php echo $needed;?>" size="1" class="smaller-size" />
				<input type="hidden" name="s_room_rate" value="<?php echo $price;?>" />
				<input type="hidden" name="s_room_need" value="<?php echo $this->item->s_room_total;?>" />
			</td>
	    </tr>
	    <?php else : ?>
	    <tr class="room-item">
	    	<td>S Room:</td>
            <td class="result-row-room-price">N/A</td>
            <td class="result-row-room-total">N/A</td>
	        <td class="room-item-last-cell"><input type="hidden" name="s_room" value="0" /></td>
	    </tr>				    

	    <?php endif; ?>
        <?php $needed = $needed - $s_needed;?>
    <?php endif;?>


	<?php 
	// Single/Double Rooms	
	if((int)$this->item->sd_room_total) :
		
		if(floatval($this->item->contracted_sd_rate) == ""){
			$price = floatval($this->item->sd_room_rate);
		}else{
			if(floatval($this->item->sd_room_rate) < floatval($this->item->contracted_sd_rate) && $rate_max->sdrate == "false" ){
	    		$price = floatval($this->item->contracted_sd_rate);
	    	}elseif (floatval($this->item->sd_room_rate) < floatval($this->item->contracted_sd_rate) && $rate_max->sdrate == "true") {
	    		$price = floatval($this->item->sd_room_rate);
	    	}elseif ($this->item->sd_room_rate > $this->item->contracted_sd_rate) {
	    		$price = floatval($this->item->contracted_sd_rate);
	    	} 
		}

				

		if($result){
			$conv = floatval($price) / floatval($result->exchange_rate);
			$price = round($conv, 2);
			$prideUsd = round($conv * floatval($code->exchange_rate), 2);
		}else{
			$price = floatval($this->item->sd_room_rate);
			$prideUsd = round($price * floatval($code->exchange_rate), 2);
		}
	
				
		// if( $this->item->isContractedRate && $this->item->contracted_sd_rate > 0 ) {
		// 	$price = floatval($this->item->contracted_sd_rate);
		// 	$prideUsd = round($price * floatval($code->exchange_rate), 2);
		// }
	?>	
	<tr class="room-item">
    	<td>S/D Room:</td>
        <!-- <td class="result-row-room-price"><?php echo $this->item->currency_symbol." ".number_format($price, 2, ".", ""); ?></td> -->
        <td class="result-row-room-price"><?php echo $this->item->currency_symbol." ". number_format($price, 2, ".", "") . 
        			"<br /><span class='hoverRate' 
        			title='Test' style='font-size:12px; font-weight:400;'> USD ". number_format($prideUsd, 2, ".", "") . "</span>"; ?></td>
        <td class="result-row-room-total"><?php echo $this->item->sd_room_total; ?></td>
        <td class="room-item-last-cell">
	        <?php
            $sd_needed = 0;
            if( $this->item->sd_room_total >= $needed )
            {
                $sd_needed  = $needed;
            } else {
                $sd_needed  = (int)$this->item->sd_room_total;
            }

            $needed = $needed - $sd_needed;
	        ?>
	        <input type="text" name="sd_room" onchange="roomSD(<?php echo $this->item->sd_room_total;?>)" value="<?php echo $sd_needed;?>" size="1" class="smaller-size" />
			<input type="hidden" name="sd_room_rate" value="<?php echo $price;?>" />
			<input type="hidden" name="sd_room_need" value="<?php echo $this->item->sd_room_total;?>" />
        </td>    
    </tr>
    <?php else : ?>
    <tr class="room-item">
    	<td data-step="7" data-intro="<?php echo SfsHelper::getTooltipTextEsc('item_rooms', $text,'airline'); ?>">S/D Room:</td>
        <td class="result-row-room-price"><?php echo $this->item->currency_symbol." ".number_format($price, 2, ".", ""); ?></td>
        <td class="result-row-room-total"><?php echo $this->item->sd_room_total; ?></td>
        <td class="room-item-last-cell"><input type="hidden" name="sd_room" value="0" /></td>
    </tr>
    <?php endif; ?>
    
    <?php 
    // Triple Rooms   
    if((int)$this->item->t_room_total) : 

    	if(floatval($this->item->contracted_t_rate) == ""){
    		$price = floatval($this->item->t_room_rate);
    	}else{
    		if(floatval($this->item->t_room_rate) < floatval($this->item->contracted_t_rate) && $rate_max->trate == "false" ){
	    		$price = floatval($this->item->contracted_t_rate);
	    	}elseif (floatval($this->item->t_room_rate) < floatval($this->item->contracted_t_rate) && $rate_max->trate == "true") {
	    		$price = floatval($this->item->t_room_rate);
	    	}elseif ($this->item->t_room_rate > $this->item->contracted_t_rate) {
	    		$price = floatval($this->item->contracted_t_rate);
	    	}
    	}

    	

    	if($result){
			$conv = floatval($price) / floatval($result->exchange_rate);
			$price = round($conv, 2);			
			$prideUsd = round($conv * floatval($code->exchange_rate), 2);
		}else{
			$price = floatval($this->item->t_room_rate);
			$prideUsd = round($price * floatval($code->exchange_rate), 2);
		}
		
		
    	//$price = floatval($this->item->t_room_rate);
		// if( $this->item->isContractedRate && $this->item->contracted_t_rate > 0 ) {
		// 	$price = floatval($this->item->contracted_t_rate);
		// 	$prideUsd = round($price * floatval($code->exchange_rate), 2);
		// }
    ?>
    <tr class="room-item">
    	<td class="result-row-room-title">T Room:</td>
        <!-- <td class="result-row-room-price"><?php echo $this->item->currency_symbol." ".number_format($price, 2, ".", ""); ?></td> -->
        <td class="result-row-room-price"><?php echo $this->item->currency_symbol." ". number_format($price, 2, ".", "") . 
        			"<br /><span class='hoverRate' title='Test' style='font-size:12px; font-weight:400;'> USD ". number_format($prideUsd, 2, ".", "") . "</span>"; ?></td>
        <td class="result-row-room-total"><?php echo $this->item->t_room_total; ?></td>
        <td class="room-item-last-cell" style="padding-right:5px;">
        	<?php 
        	$t_needed=0;
            if( $this->item->t_room_total >= $needed )
            {
                $t_needed  = $needed;
            } else {
                $t_needed  = (int)$this->item->t_room_total;
            }
	        			
	        if ( (int) $this->state->get('filter.show_all_rooms') ) {
	        	$t_needed = (int)$this->state->get('filter.rooms') - $needed;
	        }
	        ?>					       	
        	<input type="text" name="t_room" onchange="roomT(<?php echo $this->item->t_room_total;?>)" value="<?php echo $t_needed;?>" size="1" class="smaller-size" />
			<input type="hidden" name="t_room_rate" value="<?php echo $price;?>" />
			<input type="hidden" name="t_room_need" value="<?php echo $this->item->t_room_total;?>" />
		</td>
    </tr>
    <?php else: ?>
	<tr class="room-item">
    	<td class="result-row-room-title">T Room:</td>
        <td class="result-row-room-price">N/A</td>
        <td class="result-row-room-total">N/A</td>
        <td class="room-item-last-cell" style="padding-right:5px;"><input type="hidden" name="t_room" value="0" /></td>
    </tr>				    
    <?php endif;?>
    
    <?php 
    //Quad Rooms
    if( !empty($this->item->quad_room_available) && (int)$this->item->quad_room_available == 1 ) : ?>
		<?php 
		if((int)$this->item->q_room_total) : 

			if(floatval($this->item->contracted_q_rate) == ""){
				$price = floatval($this->item->q_room_rate);
			}else{
				if(floatval($this->item->q_room_rate) < floatval($this->item->contracted_q_rate) && $rate_max->qrate == "false" ){
	        		$price = floatval($this->item->contracted_q_rate);
	        	}elseif (floatval($this->item->q_room_rate) < floatval($this->item->contracted_q_rate) && $rate_max->qrate == "true") {
	        		$price = floatval($this->item->q_room_rate);
	        	}elseif ($this->item->q_room_rate > $this->item->contracted_q_rate) {
	        		$price = floatval($this->item->contracted_q_rate);
	        	}
			}			

			if($result){
				$conv = floatval($price) / floatval($result->exchange_rate);
				$price = round($conv, 2);
				$prideUsd = round($conv * floatval($code->exchange_rate), 2);
			}else{
				$price = floatval($this->item->q_room_rate);
				$prideUsd = round($price * floatval($code->exchange_rate), 2);
			}
			
			
			//$price = floatval($this->item->q_room_rate);
			// if( $this->item->isContractedRate && $this->item->contracted_q_rate > 0 ) {
			// 	$price = $this->item->contracted_q_rate;
			// 	$prideUsd = round($price * floatval($code->exchange_rate), 2);
			// }	
		?>
		<tr class="room-item">
	    	<td class="result-row-room-title">Q Room:</td>
	    	<!-- <td class="result-row-room-price">
                <?php echo $this->item->currency_symbol." ".number_format($price, 2, ".", ""); ?>
       		</td> -->
       		<td class="result-row-room-price"><?php echo $this->item->currency_symbol." ". number_format($price, 2, ".", "") . 
        			"<br /><span style='font-size:12px; font-weight:400;'> USD ". number_format($prideUsd, 2, ".", "") . "</span>"; ?></td>
	        <td class="result-row-room-total">
	        	<?php echo $this->item->q_room_total; ?>
	        </td>
	        <td class="room-item-last-cell" style="padding-right:5px;">		      
		        <input type="text" name="q_room" onchange="roomQ(<?php echo $this->item->q_room_total;?>)" value="0" size="1" class="smaller-size" />				        
		        <input type="hidden" name="q_room_rate" value="<?php echo $price;?>" />
		        <input type="hidden" name="q_room_need" value="<?php echo $this->item->q_room_total;?>" />
	        </td>
	    </tr>
	    <?php else : ?>
	    <tr class="room-item">
	    	<td class="result-row-room-title">Q Room:</td>
	    	<td class="result-row-room-price">N/A</td>
	        <td class="result-row-room-total">N/A</td>
	        <td class="room-item-last-cell" style="padding-right:5px;"><input type="hidden" name="q_room" value="0" /></td>
	    </tr>				    
	    <?php endif; ?>
    <?php endif;?>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <script type="text/javascript">
    jQuery(function($){
    	$(".hoverRate").tooltip({
    		content: "Estimated based on average exchange rate.",
           
            position: {
              my: "left top-35",
              at: "right bottom-10",
              collision: "none"
           }
    	});
    })
    </script>

    <style type="text/css">
    .ui-widget-content{
    	background: #82adf1;
    	color: #ffffff;
    	border: none;    	
    }
    </style>


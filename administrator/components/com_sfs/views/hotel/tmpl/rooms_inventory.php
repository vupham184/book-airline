<?php
defined('_JEXEC') or die;

$hotelTransport = $this->item->getTransportDetail();
$hotelSetting   = $this->item->getBackendSetting();

$s_room_rate  = null;
$sd_room_rate = null;
$t_room_rate  = null;
$q_room_rate  = null;

if( is_object($this->inventory)  ) {
	$s_room_rate  = ( (float)$this->inventory->s_room_rate_modified > 0 ) ? $this->inventory->s_room_rate_modified : $this->inventory->s_room_rate;
	$sd_room_rate = ( (float)$this->inventory->sd_room_rate_modified > 0 ) ? $this->inventory->sd_room_rate_modified : $this->inventory->sd_room_rate;
	$t_room_rate  = ( (float)$this->inventory->t_room_rate_modified > 0 ) ? $this->inventory->t_room_rate_modified : $this->inventory->t_room_rate;
	$q_room_rate  = ( (float)$this->inventory->q_room_rate_modified > 0 ) ? $this->inventory->q_room_rate_modified : $this->inventory->q_room_rate;
}

$transport_included = 0;
if(is_object($this->inventory)) {
	$transport_included =  $this->inventory->transport_included ? 1 : 0;
} else {
	if($this->tax->transport) {
		$transport_included = 1;
	}
}

if( isset($hotelTransport) && (int)$hotelTransport->transport_available == 0 )
{
	$transport_included = 0;
} 
?>

<?php if( isset($hotelTransport) && (int)$hotelTransport->transport_available == 0 ) : ?>
	<input style="display:none;" type="checkbox" class="transport" id="transport<?php echo $this->index;?>" name="rooms[<?php echo $this->index;?>][transport]" value="0" />
<?php else :?>
<div class="transport-field clear">
	<input type="checkbox" class="transport" id="transport<?php echo $this->index;?>" name="rooms[<?php echo $this->index;?>][transport]" value="1" <?php echo $transport_included ? 'checked="checked"' : ''; ?> />
</div>
<?php endif;?>

<?php 
if( isset($hotelSetting) && (int)$hotelSetting->single_room_available == 1 ) :

	$classAjax = '';
	$ajaxRule  = '';
	$s_room_total = '';
	$s_num_rank = '';
	$s_room_rank	=  '';
		
	if( is_object($this->inventory) ) {	
		$s_room_total = (int)$this->inventory->s_room_total;	
		if( $s_room_total > 0 ){			
			$classAjax = ' ajaxCheck';			
			$ajaxRule .= 's,'.floatval($s_room_rate).','.$s_room_total.','.$this->inventory->date.','.$this->inventory->transport_included;
		} else {			
			$this->inventory->s_num_rank = $this->inventory->s_room_rank = 'n/a';	
			$s_room_rate = '0';			
		}
		$s_room_rank = $this->inventory->s_room_rank;
		$s_num_rank  = $this->inventory->s_num_rank;
	}	
?>
	<div class="clear floatbox<?php echo $classAjax?>" rel="<?php echo strlen($ajaxRule) > 0 ? 'nrank,'.$ajaxRule.',snumrank'.$this->index : ''; ?>">
		<input type="text" id="sroom<?php echo $this->index;?>" class="inputbox validate-integer" name="rooms[<?php echo $this->index;?>][sroom]" value="<?php echo $s_room_total;?>" style="height: 24px !important;" />
		<div style="height: 24px !important;" id="snumrank<?php echo $this->index;?>" class="inputbox inputbox-rank"><?php echo $s_num_rank;?></div>
	</div>
	<div class="clear floatbox<?php echo $classAjax?>" rel="<?php echo strlen($ajaxRule) > 0 ? 'rank,'.$ajaxRule.',srank'.$this->index : ''; ?>">
		<input type="text" id="srate<?php echo $this->index;?>" class="inputbox validate-digits" name="rooms[<?php echo $this->index;?>][srate]" value="<?php echo ($s_room_rate !==null) ? $s_room_rate : '';?>" style="height: 24px !important;" />
		<div style="height: 24px !important;" id="srank<?php echo $this->index;?>" class="inputbox inputbox-rank"><?php echo $s_room_rank;?></div>
	</div>
<?php
endif;
?>

<?php 
$classAjax = '';
$ajaxRule  = '';
$sd_room_total = '';
$sd_num_rank = '';
$sd_room_rank= '';
	
if( is_object($this->inventory) ) {	
	$sd_room_total = (int)$this->inventory->sd_room_total;	
	if( $sd_room_total > 0 ){			
		$classAjax = ' ajaxCheck';			
		$ajaxRule .= 'sd,'.floatval($sd_room_rate).','.$sd_room_total.','.$this->inventory->date.','.$this->inventory->transport_included;
	} else {			
		$this->inventory->sd_num_rank = $this->inventory->sd_room_rank = 'n/a';	
		$sd_room_rate = '0';			
	}
	$sd_room_rank = $this->inventory->sd_room_rank;
	$sd_num_rank  = $this->inventory->sd_num_rank;
}	
?>

<div class="clear floatbox<?php echo $classAjax?>" rel="<?php echo strlen($ajaxRule) > 0 ? 'nrank,'.$ajaxRule.',sdnumrank'.$this->index : ''; ?>">
	<input type="text" id="sdroom<?php echo $this->index;?>" class="inputbox validate-integer" name="rooms[<?php echo $this->index;?>][sdroom]" value="<?php echo $sd_room_total;?>" style="height: 24px !important;" />
	<div style="height: 24px !important;" id="sdnumrank<?php echo $this->index;?>" class="inputbox inputbox-rank"><?php echo $sd_num_rank;?></div>
</div>
<div class="clear floatbox<?php echo $classAjax?>" rel="<?php echo strlen($ajaxRule) > 0 ? 'rank,'.$ajaxRule.',sdrank'.$this->index : ''; ?>">
	<input type="text" id="sdrate<?php echo $this->index;?>" class="inputbox validate-digits" name="rooms[<?php echo $this->index;?>][sdrate]" value="<?php echo ($sd_room_rate !==null) ? $sd_room_rate : '';?>" style="height: 24px !important;" />
	<div style="height: 24px !important;" id="sdrank<?php echo $this->index;?>" class="inputbox inputbox-rank"><?php echo $sd_room_rank;?></div>
</div>

<?php
$last = 'inputbox-last';
if( isset($hotelSetting) && (int)$hotelSetting->quad_room_available == 1 )
{
	$last = '';	
}
$classAjax = '';
$ajaxRule  = '';
$t_room_total = '';
$t_num_rank = '';
$t_room_rank= '';
	
if( is_object($this->inventory) ) {	
	$t_room_total = (int)$this->inventory->t_room_total;	
	if( $sd_room_total > 0 ){			
		$classAjax = ' ajaxCheck';			
		$ajaxRule .= 't,'.floatval($t_room_rate).','.$t_room_total.','.$this->inventory->date.','.$this->inventory->transport_included;
	} else {			
		$this->inventory->t_num_rank = $this->inventory->t_room_rank = 'n/a';
		$t_room_rate = '0';			
	}
	$t_room_rank = $this->inventory->t_room_rank;
	$t_num_rank  = $this->inventory->t_num_rank;
}
?>
<div class="clear floatbox<?php echo $classAjax?>" rel="<?php echo strlen($ajaxRule) > 0 ? 'nrank,'.$ajaxRule.',tnumrank'.$this->index : ''; ?>">
	<input type="text" id="troom<?php echo $this->index;?>" class="inputbox validate-integer" name="rooms[<?php echo $this->index;?>][troom]" value="<?php echo $t_room_total;?>" style="height: 24px !important;" />
	<div style="height: 24px !important;" id="tnumrank<?php echo $this->index;?>" class="inputbox inputbox-rank"><?php echo $t_num_rank;?></div>
</div>	
<div class="clear floatbox<?php echo $classAjax?>" rel="<?php echo strlen($ajaxRule) > 0 ? 'rank,'.$ajaxRule.',trank'.$this->index : ''; ?>">				
	<input type="text" id="trate<?php echo $this->index;?>" class="inputbox validate-digits <?php echo $last?>" name="rooms[<?php echo $this->index;?>][trate]" value="<?php echo ($t_room_rate !==null) ? $t_room_rate : '';?>" style="height: 24px !important;" />
	<div style="height: 24px !important;" id="trank<?php echo $this->index;?>" class="inputbox inputbox-rank <?php echo $last?>"><?php echo $t_room_rank;?></div>
</div>	

<?php 
if( isset($hotelSetting) && (int)$hotelSetting->quad_room_available == 1 ): 
	$classAjax = '';
	$ajaxRule  = '';
	$q_room_total = '';
	$q_num_rank = '';
	$q_room_rank	=  '';
		
	if( is_object($this->inventory) ) {	
		$q_room_total = (int)$this->inventory->q_room_total;	
		if( $q_room_total > 0 ){			
			$classAjax = ' ajaxCheck';			
			$ajaxRule .= 'q,'.floatval($q_room_rate).','.$q_room_total.','.$this->inventory->date.','.$this->inventory->transport_included;
		} else {			
			$this->inventory->q_num_rank = $this->inventory->q_room_rank = 'n/a';	
			$q_room_rate = '0';			
		}
		$q_room_rank = $this->inventory->q_room_rank;
		$q_num_rank  = $this->inventory->q_num_rank;
	}	
?>
	<div class="clear floatbox<?php echo $classAjax?>" rel="<?php echo strlen($ajaxRule) > 0 ? 'nrank,'.$ajaxRule.',qnumrank'.$this->index : ''; ?>">
		<input type="text" id="qroom<?php echo $this->index;?>" class="inputbox validate-integer" name="rooms[<?php echo $this->index;?>][qroom]" value="<?php echo $q_room_total;?>" style="height: 24px !important;" />
		<div style="height: 24px !important;" id="qnumrank<?php echo $this->index;?>" class="inputbox inputbox-rank"><?php echo $q_num_rank;?></div>
	</div>	
	<div class="clear floatbox<?php echo $classAjax?>" rel="<?php echo strlen($ajaxRule) > 0 ? 'rank,'.$ajaxRule.',qrank'.$this->index : ''; ?>">				
		<input type="text" id="qrate<?php echo $this->index;?>" class="inputbox validate-digits inputbox-last" name="rooms[<?php echo $this->index;?>][qrate]" value="<?php echo ($q_room_rate !==null) ? $q_room_rate : '';?>" style="height: 24px !important;" />
		<div style="height: 24px !important;" id="qrank<?php echo $this->index;?>" class="inputbox inputbox-rank inputbox-last"><?php echo $q_room_rank;?></div>
	</div>	
<?php
endif;
?>
			
			
	
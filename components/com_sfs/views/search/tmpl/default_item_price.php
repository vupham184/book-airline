<?php
defined('_JEXEC') or die();

if( !empty($this->item->single_room_available) && (int)$this->item->single_room_available == 1 )
{
	$price = floatval($this->item->s_room_rate);
	if( $this->item->isContractedRate && $this->item->contracted_s_rate > 0 ) {
		$price = $this->item->contracted_s_rate;
	}	
	if( ! empty($price) ) {
		echo '<div class="floatbox clear">';
		if((int)$this->item->s_room_total){
			echo '<span style="display:block;float:left;width:80px;padding-top:5px;">S Room:</span>'.'<span class="search-price-value">'.$this->item->currency_symbol.floatval($price).'</span>';
		} else {
			echo '<span style="display:block;float:left;width:80px;padding-top:5px;">S Room:</span><span class="search-price-value">N/A</span>';
		}
		echo '</div>';
	}
}

$price = floatval($this->item->sd_room_rate);
if( $this->item->isContractedRate && $this->item->contracted_sd_rate > 0 ) {
	$price = $this->item->contracted_sd_rate;
}
if( ! empty($price) ) {
	echo '<div class="floatbox clear">';
	if((int)$this->item->sd_room_total){
		echo '<span style="display:block;float:left;width:80px;padding-top:5px;">S/D Room:</span>'.'<span class="search-price-value">'.$this->item->currency_symbol.floatval($price).'</span>';
	} else {
		echo '<span style="display:block;float:left;width:80px;padding-top:5px;">S/D Room:</span><span class="search-price-value">N/A</span>';
	}
	echo '</div>';
}
$price = floatval($this->item->t_room_rate);
if( $this->item->isContractedRate && $this->item->contracted_t_rate > 0 ) {
	$price = $this->item->contracted_t_rate;
}
if( ! empty($price) ) {
	echo '<div class="floatbox clear">';	
	if((int)$this->item->t_room_total){
		echo '<span style="display:block;float:left;width:80px;padding-top:5px;">T Room:</span>'.'<span class="search-price-value">'.$this->item->currency_symbol.floatval($price).'</span>';
	} else {
		echo '<span style="display:block;float:left;width:80px;padding-top:5px;">T Room:</span> <span class="search-price-value">N/A</span>';
	}
	echo '</div>';
}


if( !empty($this->item->quad_room_available) && (int)$this->item->quad_room_available == 1 )
{
	$price = floatval($this->item->q_room_rate);
	if( $this->item->isContractedRate && $this->item->contracted_q_rate > 0 ) {
		$price = $this->item->contracted_q_rate;
	}	
	if( ! empty($price) ) {
		echo '<div class="floatbox clear">';
		if((int)$this->item->q_room_total){
			echo '<span style="display:block;float:left;width:80px;padding-top:5px;">Q Room:</span>'.'<span class="search-price-value">'.$this->item->currency_symbol.floatval($price).'</span>';
		} else {
			echo '<span style="display:block;float:left;width:80px;padding-top:5px;">Q Room:</span><span class="search-price-value">N/A</span>';
		}
		echo '</div>';
	}
}
?>


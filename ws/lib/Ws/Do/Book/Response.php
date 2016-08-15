<?php

class Ws_Do_Book_Response {
	public $BookingReference;
	public $TotalPrice;
	public $OriginalTotalPrice;
	public $TotalCommission;
	public $CustomerTotalPrice;
	
	public $PropertyBookings = array();
	
	public function toString(){
		return base64_encode(serialize($this));
	}
	
	public static function fromString($str){
		$var = unserialize(base64_decode($str));
		if($var instanceof Ws_Do_Book_Response) {
			return $var;
		} else {
			return null;
		}
	}
}
<?php

class Ws_Do_PreBook_Response {
	public $IssueTime; // GMT issue time
	public $PreBookingToken;
	public $TotalPrice;
	public $OriginalTotalPrice;
	public $TotalCommission;
	public $VATOnCommission;
	
	public $Cancellations = array();
	
	public function toString(){
		return base64_encode(serialize($this));
	}
	
	public static function fromString($str){
		$var = unserialize(base64_decode($str));
		if($var instanceof Ws_Do_PreBook_Response) {
			return $var;
		} else {
			return null;
		}
	}
}
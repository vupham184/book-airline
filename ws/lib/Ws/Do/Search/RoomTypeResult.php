<?php

class Ws_Do_Search_RoomTypeResult extends Ws_Do_Base_RoomType{
	public $PropertyID;
	public $ArrivalDate;
	public $Duration;
	public $NumberOfRooms;
	public $PropertyReferenceID;
	public $PropertyRoomTypeID;
	public $BookingToken;
	public $TotalRoomAvailable = 0;
	public $NumAdultsPerRoom;
	public $NumChildrenPerRoom;
	public $NumInfantsPerRoom;
	public $ChildAges = array();
	public $Name = '';
	public $MealBasisName = '';
	public $MealBasisID ;
	public $SubTotal = 0;
	public $Total = 0;
	public $OriginalTotal = 0;
    public $SpecialOfferApplied;
    public $Errata = array();

	public function toString(){
		return base64_encode(serialize($this));
	}
	
	public static function fromString($str){
		$var = unserialize(base64_decode($str));
		if($var instanceof Ws_Do_Search_RoomTypeResult) {
			return $var;
		} else {
			return null;
		}
	}
}
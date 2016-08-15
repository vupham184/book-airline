<?php

class Ws_Do_PreBook_RoomRequest {
	public $BookingToken;
	public $PropertyRoomTypeID;
	public $MealBasisID;
	public $MealBasisName;
	public $NumAdults = null;
	public $NumChildren = null;
	public $NumInfants = null;
	public $ChildAges = array();
}
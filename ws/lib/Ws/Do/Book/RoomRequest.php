<?php

class Ws_Do_Book_RoomRequest{
	public $BookingToken;
	public $PropertyRoomTypeID;
	public $TotalRoomAvailable = 0;
	public $MealBasisID = '';
	public $MealBasisName = '';
	public $NumAdults = null;
	public $NumChildren = null;
	public $NumInfants = null;

	public $Guests = array();
}
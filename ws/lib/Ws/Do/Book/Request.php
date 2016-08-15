<?php

class Ws_Do_Book_Request{
	public $PropertyID;
	public $ArrivalDate;
	public $Duration;
	public $PreBookingToken;
	public $LeadGuestTitle;
	public $LeadGuestFirstName;
	public $LeadGuestLastName;
	public $TradeReference;
	
	public $RoomBookings = array();
}
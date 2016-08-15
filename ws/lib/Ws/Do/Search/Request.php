<?php

class Ws_Do_Search_Request {
	public $AirportIATACode;
	public $ArrivalDate;
	public $Duration;
	public $NumberOfRooms = 1;
    public $CurrencyID;
	public $Rooms = array();
}
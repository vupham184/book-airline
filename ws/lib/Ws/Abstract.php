<?php

abstract class Ws_Abstract implements Ws_Interface {
	protected $_config = array();
	
	public function __construct($config) {
		$this->_config = $config;
	}
	
	public function getAllAirports(){
		throw new Exception('Not implemented!');
	}
	public function getAllBookingSources(){
		throw new Exception('Not implemented!');
	}
	public function getAllCardTypes(){
		throw new Exception('Not implemented!');
	}
	public function getAllCurrencies(){
		throw new Exception('Not implemented!');
	}
	public function getAllExtraTypes(){
		throw new Exception('Not implemented!');
	}
	public function getAllFacilities(){
		throw new Exception('Not implemented!');
	}
	public function getAllHotels(){
		throw new Exception('Not implemented!');
	}
	public function getAllLocations(){
		throw new Exception('Not implemented!');
	}
	public function getAllMealBasis(){
		throw new Exception('Not implemented!');
	}
	public function getAllProductAttributes(){
		throw new Exception('Not implemented!');
	}
	public function getAllRoomTypes(){
		throw new Exception('Not implemented!');
	}
	public function getAllStartRatings(){
		throw new Exception('Not implemented!');
	}
}
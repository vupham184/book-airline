<?php

abstract class Ws_Do_Abstract{
	
	public $PrimaryID = null;
	public $UniqueID = null;
	
	public function __set($property, $value) {
		if (!property_exists($this, $property)) {
			throw new Exception("Property [$property] does not exist");
		}
	}
}
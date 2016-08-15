<?php

class Ws_Factory {
	/**
	 *
	 * Enter description here ...
	 * @param array $config
	 * @return Ws_Interface
	 */
	static function createWS($config) {
		if(empty($config['type'])) {
			throw new Exception('type is missing');
		}
		
		$type = $config['type'];
		
		$dir = dirname(__FILE__);
		$adapterPath = $dir . DIRECTORY_SEPARATOR . 'Adapter' . DIRECTORY_SEPARATOR . ucfirst($type) . '.php';
		$adapterClass = 'Ws_Adapter_' . ucfirst($type);
		if(!file_exists($adapterPath)) {
			throw new Exception('file not found at ' . $adapterPath);
		}
		
		require_once $adapterPath;
		
		if(!class_exists($adapterClass)){
			throw new Exception('class "' .$adapterClass . '"not found at: ' . $adapterPath);
		}
		
		if(empty($config['params'])) {
			$obj = new $adapterClass;
		} else {
			$obj = new $adapterClass($config['params']);
		}
		
		if(!$obj instanceof Ws_Interface) {
			throw new Exception('must be Ws_Interface the ' . $adapterClass);
		}
		
		return $obj;
	}
}
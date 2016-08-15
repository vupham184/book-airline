<?php

class Ws_Loader {
	
	static $_dir = null;
	
	static function load($className) {
		
		if(class_exists($className)) {
			return;
		}

		# only auto load classes with Ws_
		if(strpos($className, 'Ws_') !== 0) {
			return;
		}
		
		if(self::$_dir === null) {
			self::$_dir = dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;
		}
		
		$classNameParts = explode('_', $className);
		$classPath = self::$_dir . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $classNameParts) . '.php';
		
		require_once $classPath;
	}
	
	static function init() {
		spl_autoload_register('Ws_Loader::load');
	}
}
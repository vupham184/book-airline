<?php

require_once dirname(__FILE__) . '/../lib/Ws/Loader.php';

Ws_Loader::init();

$view = @$_GET['view'];

if(empty($view)) {
	$view = 'index';
}

include dirname(__FILE__) . '/totalstay/' . $view. '.php';

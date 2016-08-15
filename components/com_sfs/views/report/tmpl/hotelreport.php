<?php
defined('_JEXEC') or die;
switch ($this->drawType){
	case 1:
		echo $this->loadTemplate('roomnights');
		break;
	case 2:
		echo $this->loadTemplate('average');
		break;
	case 3:
		echo $this->loadTemplate('revenue');
		break;		
}
?>
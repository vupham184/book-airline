<?php
defined('_JEXEC') or die;
switch ($this->drawType){
	case 1:
		echo $this->loadTemplate('iatacode');
		break;
	case 2:
		echo $this->loadTemplate('market');
		break;
	case 3:
		echo $this->loadTemplate('transportation');
		break;	
	case 4:
		echo $this->loadTemplate('initial');		
		break;		
}
?>
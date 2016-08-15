<?php
// access check
defined('_JEXEC') or die;
if( SFSAccess::isHotel($this->user, 'h.admin' ) ) {
	echo $this->loadTemplate('hotel');
} 

if ( SFSAccess::isAirline($this->user) ){
	echo $this->loadTemplate('airline');
} 
?>
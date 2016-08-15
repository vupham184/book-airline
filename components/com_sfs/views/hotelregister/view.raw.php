<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class SfsViewHotelRegister extends JView
{    
    public function display($tpl = null)
    {    	    
    	$this->hotel = SFactory::getHotel();
		$this->contacts = $this->hotel->getContacts();    	    	
        parent::display($tpl);
    }  
}

<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class SfsViewInvite_Hotel_For_Registration extends JView
{    
	
    protected $params;
    protected $state;
    protected $hotels;
    protected $inventory;
    protected $result;

    public function display($tpl = null)
    {
    	$app  = JFactory::getApplication();
		
		// Assign data to the view
		$this->state	  = $this->get('State');
        $this->hotels 	  = $this->get('Hotels');
		
        parent::display($tpl);
	}  
}

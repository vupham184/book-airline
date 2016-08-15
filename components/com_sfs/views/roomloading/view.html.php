<?php
defined('_JEXEC') or die;

class SfsViewRoomloading extends JViewLegacy
{    
    protected $params;
    protected $state;
    protected $hotel;
   
    public function display($tpl = null)
    { 
    	$app  = JFactory::getApplication();
    	
		$this->state       = $this->get('State');
        $this->params      = $this->state->get('params');    	
    	$this->user 	   = JFactory::getUser();
    	
        if( SFSAccess::isHotel( $this->user ) )
    	{    	
    		$this->hotel = $this->get('Hotel');    		
    		if( ! $this->hotel->isRegisterComplete() || ($this->hotel->block == 1) ) {	    		
	    		$app->redirect( JRoute::_('index.php?option=com_sfs&view=dashboard&Itemid=103',false) );
	    		return false;
    		}	    		
    	} else {
    		$url = JRoute::_('index.php?option=com_sfs&view=home',false);
    		$app->redirect( $url );
    		return false;
    	}    	
    			
        $this->rooms_prices 	= $this->get('RoomsPrices');
        $this->contractedRates 	= $this->get('AirlineContractedRates');
        
        $this->allowRunRanking = false;
        $i = 0;
        
        foreach ($this->rooms_prices as $roomPrice)
        {        	
        	if( $i == 0 && is_object($roomPrice) )
        	{
        		$this->allowRunRanking = true;
        		break;
        	}
        	$i++;
        }
        
        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode('<br />', $errors));
            return false;
        }
        //Escape strings for HTML output
        $this->pageclass_sfx = htmlspecialchars($this->params->get('pageclass_sfx'));      
        $this->prepareDocument();
        
        parent::display($tpl); 
    }
    
    
    protected function prepareDocument()
    {
        $app        = JFactory::getApplication();
        $menus      = $app->getMenu();
        $title      = null;

        // Because the application sets a default page title,
        // we need to get it from the menu item itself
        $menu = $menus->getActive();
        if ($menu) {
            $this->params->def('page_heading', $this->params->get('page_title', $menu->title));
        } else {
            $this->params->def('page_heading', JText::_('COM_SFS_HOTEL_PROFILE'));
        }

        $title = $this->params->get('page_title', '');
        if (empty($title)) {
            $title = $app->getCfg('sitename');
        }
        elseif ($app->getCfg('sitename_pagetitles', 0)) {
            $title = JText::sprintf('JPAGETITLE', $app->getCfg('sitename'), $title);
        }
        $this->document->setTitle($title);
    }
    
}

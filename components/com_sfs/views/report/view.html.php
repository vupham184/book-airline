<?php
defined('_JEXEC') or die;
jimport('joomla.application.component.view');

class SfsViewReport extends JView
{    
    protected $params;
    protected $state, $check_userkey;

    public function display($tpl = null)
    {
    	$app  = JFactory::getApplication();
        // Get the view data.                              
        $this->state       = $this->get('State');
        $this->params      = $this->state->get('params');         
        $this->user		   = JFactory::getUser();
		$this->check_userkey = $this->get('CheckUserkey');
		//lchung check userkey
		//http://sfs.dev/index.php?option=com_sfs&view=report&layout=airline&Itemid=122&uk=YeZSTUxYSzMMSN1uz8qxXTVqh1LcLk5s
		if ( !count( $this->check_userkey ) || JRequest::getVar('uk') == '' ) {
			
			if( SFSAccess::isHotel( $this->user ) )
			{    		
				$this->hotel 	   = SFactory::getHotel();
				if( ! $this->hotel->isRegisterComplete() || $this->hotel->isBlock() ) {	    		
					$app->redirect( JRoute::_('index.php?option=com_sfs&view=dashboard&Itemid=103',false) );
					return false;
				}	    		
				//do not nothing at this time    			
			} else if ( SFSAccess::isAirline($this->user) ) {
				if( SFSAccess::check($this->user, 'gh') ) {
					$airline = SFactory::getAirline();
						
					if( (int)$airline->iatacode_id == 0 ) {
						$app->redirect( JRoute::_('index.php?option=com_sfs&view=airlineprofile&layout=changeairline&Itemid=128',false) );
						return false;	
					}	
				}    		
			} else {
				$app->redirect( JRoute::_('index.php?option=com_sfs&view=home&Itemid='.JRequest::getInt('Itemid'),false) );
				return false;
			}  
		   
		}//End lchung check userkey
        
       	$this->processLayout( $this->getLayout() );	   
       	 
        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode('<br />', $errors));
            return false;
        }
        

        $this->pageclass_sfx = htmlspecialchars($this->params->get('pageclass_sfx')); 
             
        $this->prepareDocument();
        
        parent::display($tpl);
    }

 
    protected function processLayout($layout){
    	switch ($layout) {
    		case 'market':
    			$this->_market();
    		case 'hotel':
    			$this->hotel  = SFactory::getHotel();    			
    			$this->data_count = $this->get('DataCount');
    			if ($this->data_count !== FALSE && !$this->data_count) {
					$app = JFactory::getApplication();
    				$app->enqueueMessage(JText::_('COM_SFS_REPORT_NO_DATA'), 'warning');    				
    			}    					
			default:			
				break;			
    	}
    }
    
    protected function _market(){    
    		  
    	$this->hotels = $this->get('MarketHotels');   
    	  
    	$market_calendar = JRequest::getVar('market_calendar');
    	
    	$selected = '';
    	
    	if ( $market_calendar && strlen($market_calendar) == 10 ) {
    		$selected = $market_calendar;
    	} else {
    		$now = JFactory::getDate()->toSql();
    		$selected = substr($now,0,10);    		
    	}
    	
    	$attributes = array();
    	$attributes['size'] = 10;    	     	  	
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

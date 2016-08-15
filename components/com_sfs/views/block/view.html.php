<?php
defined('_JEXEC') or die;
jimport('joomla.application.component.view');

class SfsViewBlock extends JView
{    
    protected $params;
    protected $state;
    
    public function display($tpl = null)
    {    	
    	$app = JFactory::getApplication();    			    
    	$this->user		   = JFactory::getUser();
    	
    	//check permision
    	if( SFSAccess::isHotel( $this->user ) )
    	{    		       		                      
			$this->hotel 	   = SFactory::getHotel();
			if( ! $this->hotel->isRegisterComplete() || ($this->hotel->block == 1) ) {    		
	    		$app->redirect( JRoute::_('index.php?option=com_sfs&view=dashboard&Itemid=103',false) );
	    		return false;
    		}	         	          		
			//do not nothing at this time
    	} else {
    		$url = JRoute::_('index.php?option=com_sfs&view=home&Itemid='.JRequest::getInt('Itemid'),false);
    		$app->redirect( $url );	
    		return false;
    	} 

		$this->state       = $this->get('State');
        $this->params      = $this->state->get('params');    	
    	
				
		if( $this->getLayout()=='approved' || $this->getLayout()=='print') 
		{	
								
			$this->reservation  = $this->get('Reservation');			
			$this->airline 		= $this->get('Airline');		

			if( $this->reservation->status == 'A' || $this->reservation->status =='R' || $this->reservation->status =='T' ) {								
				$this->contact    = $this->airline->contact_details;
				$this->passengers = $this->reservation->getPassengers();		

				$this->guaranteeVoucher = $this->get('GuaranteeVoucher');
				
				$this->picked_rooms  = $this->reservation->getPickedRooms();
				
				$this->initial_rooms = $this->reservation->getInitialRooms();
				
				$this->total_room_charge = $this->reservation->getTotalRoomCharge();
				
				if( !empty($this->guaranteeVoucher) )
	        	{
	        		$this->total_room_charge += (int) $this->guaranteeVoucher->issued * $this->reservation->sd_rate;
	        	}
				
				$this->picked_mealplans  = $this->reservation->calculateTotalMealplan();
				$this->picked_breakfasts = $this->reservation->calculateTotalBreakfast();
				$this->picked_lunchs 	 = $this->reservation->calculateTotalLunch();
				
				$this->total_mealplan_charge = $this->reservation->getTotalMealplanCharge() + ($this->picked_breakfasts * $this->reservation->breakfast)+ ($this->picked_lunchs * $this->reservation->lunch);
				
				$this->total_invoice_charge = $this->total_room_charge + $this->total_mealplan_charge;
				
	
				$this->messages = $this->reservation->getMessages();
				
				$this->hotel->currency = $this->hotel->getTaxes()->currency_name;
				
				
			}
		}
		
		if($this->getLayout()=='tentative') 
		{
			$this->reservation 	= $this->get('Reservation');			
			$this->airline 		= $this->get('Airline');
			
			if( $this->reservation->status == 'T' || $this->reservation->status =='C' ) {
								
				$this->contact    = $this->airline->contact_details;
				$this->passengers = $this->reservation->getPassengers();	

				$this->guaranteeVoucher = $this->get('GuaranteeVoucher');
				
				$this->picked_rooms  = $this->reservation->getPickedRooms();
				$this->initial_rooms = $this->reservation->getInitialRooms();
				
				$this->total_room_charge = $this->reservation->getTotalRoomCharge();
				
				if( !empty($this->guaranteeVoucher) )
	        	{
	        		$this->total_room_charge += (int) $this->guaranteeVoucher->issued * $this->reservation->sd_rate;
	        	}
				
				$this->picked_mealplans  = $this->reservation->calculateTotalMealplan();
				$this->picked_breakfasts = $this->reservation->calculateTotalBreakfast();
				$this->picked_lunchs 	 = $this->reservation->calculateTotalLunch();
				
				$this->total_mealplan_charge = $this->reservation->getTotalMealplanCharge()+ ($this->picked_breakfasts *$this->reservation->breakfast) + ($this->picked_lunchs * $this->reservation->lunch);
				
				$this->total_invoice_charge = $this->total_room_charge + $this->total_mealplan_charge;
				
				$this->messages = $this->reservation->getMessages();
				
				$this->hotel->currency = $this->hotel->getTaxes()->currency_name;				
			}						
		} 
		
		if($this->getLayout()=='default') 
		{
			$this->blocks 	   = $this->get('Reservations');				
			$this->block_count = $this->get('BlockCount');
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

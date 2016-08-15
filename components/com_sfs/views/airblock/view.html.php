<?php
defined('_JEXEC') or die;
jimport('joomla.application.component.view');

class SfsViewAirblock extends JView
{    
    protected $params;
    protected $state;
    
    public function display($tpl = null)
    {    	
    	$app = JFactory::getApplication();    			    
    	$this->user		   = JFactory::getUser();
    	
    	//check permision
    	if( ! SFSAccess::check($this->user, 'a.admin') )
    	{    		       		                            	          		
			$url = JRoute::_('index.php?option=com_sfs&view=home&Itemid='.JRequest::getInt('Itemid'),false);
    		$app->redirect( $url );	
    		return false;
    	}
    	
		$this->airline     = SFactory::getAirline();
		$this->state       = $this->get('State');
        $this->params      = $this->state->get('params');

    	if( SFSAccess::check($this->user, 'gh') ) {							
			if( (int)$this->airline->iatacode_id == 0 ) {
				$app->redirect( JRoute::_('index.php?option=com_sfs&view=airlineprofile&layout=changeairline&Itemid=128',false) );
            	return false;	
			}	
		}
        
		$detail = 'detail';
		if( SFSAccess::isAirlineAccounting($this->user) ) {
			$detail = 'detail2';
			///$this->setLayout('detail2');
		}
		elseif ( $this->getLayout() == 'detail2'){
			$this->setLayout('detail');
		}
		elseif ( $this->getLayout() == 'comment'){
			$this->setLayout('notaccess');
		}

		if ( $this->getLayout() == 'default' ) {
			$this->blocked_rooms   = $this->get('Reservations');
			$this->pagination = $this->get('Pagination');					
		} else if ( $this->getLayout() == $detail || $this->getLayout() == 'pending' ||  $this->getLayout() == 'print' ) {
				
			$this->contact  = $this->get('HotelContact');			
			$this->hotel = $this->get('Hotel');
			
			$this->reservation = $this->get('Reservation');	
			
			$this->initial_rooms = $this->reservation->getInitialRooms();
			
			$this->guaranteeVoucher = $this->get('GuaranteeVoucher');

			$this->passengers = $this->get("Passengers");
					
			if( $this->reservation->status != 'O' && $this->reservation->status !='P' ) {				
								
				//$this->passengers = $this->reservation->getPassengers();
				
				$this->picked_rooms  = $this->reservation->getPickedRooms();
								
				$this->total_room_charge = $this->reservation->getTotalRoomCharge();
				
				if( !empty($this->guaranteeVoucher) )
	        	{
	        		$this->total_room_charge += (int) $this->guaranteeVoucher->issued * $this->reservation->sd_rate;
	        	}

	        	$this->picked_mealplans  = $this->reservation->calculateTotalMealplan();
				$this->picked_breakfasts = $this->reservation->calculateTotalBreakfast();
				$this->picked_lunchs 	 = $this->reservation->calculateTotalLunch();
				
				$this->total_mealplan_charge = $this->reservation->getTotalMealplanCharge() + ($this->picked_breakfasts * $this->reservation->breakfast) + ($this->picked_lunchs * $this->reservation->lunch);
				
				$this->total_invoice_charge = $this->total_room_charge + $this->total_mealplan_charge;
																
				//get challenge messages
				$this->messages = $this->reservation->getMessages();
			} 
			
		} else if ($this->getLayout() == 'challenge') {
			$this->hotel = $this->get('Hotel');			
			$this->reservation = $this->get('Reservation');
			$this->contact  = $this->get('HotelContact');
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
            $this->params->def('page_heading', JText::_(''));
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

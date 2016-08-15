<?php
defined('_JEXEC') or die;
jimport('joomla.application.component.view');

class SfsViewHotelProfile extends JView
{    
    protected $params;
    protected $state;
    protected $hotel;
    

    public function display($tpl = null)
    {    	
    	$app = JFactory::getApplication();
    	
		$this->state       = $this->get('State');
        $this->params      = $this->state->get('params');    	
    	$this->user		   = JFactory::getUser();
    	
    	//check permision
    	if( SFSAccess::isHotel( $this->user ) )
    	{    		       		                      
        	$this->hotel 	   = $this->get('Hotel');            
        	
    		if( (int)$this->hotel->block==1 ) {	    		
	    		$app->redirect( JRoute::_('index.php?option=com_sfs&view=dashboard&Itemid=103',false) );
	    		return false;
    		}
        	
    	} else {
    		$url = JRoute::_('index.php?option=com_sfs&view=home&Itemid='.JRequest::getInt('Itemid'),false);
    		$app->redirect( $url );        		
    		return false;    		
    	}     	
    	    	
       	$this->processLayout( $this->getLayout() );	    
       	
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

 
    protected function processLayout($layout){
    	switch ($layout) {
    		case 'airports':
    		case 'formairports':    			
    			$this->_airports();    	
				if($layout=='airports' && empty($this->airports)) {
		    		$app = JFactory::getApplication();
					$url = JRoute::_('index.php?option=com_sfs&view=hotelprofile&layout=formairports&Itemid='.JRequest::getInt('Itemid'),false);
		    		$app->redirect( $url );        		
		    		return false;        		
		    	}      					
    			break;
			case 'room':			
			case 'taxes':
			case 'formtaxes':
    			$this->_taxes();
    			break;
			case 'mealplans':
			case 'formmealplans':	
    			$this->_mealplans();
    			break;
			case 'transport':
			case 'formtransport':									
    			$this->_transport();     			  			       			    			
    			break;      			
			case 'terms':
			case 'thank':	
    			$this->_terms();    
    			break;
			case 'contactdetail':					
    			$this->_contactdetail();     			  			       			    			
    			break;         				
			default:
				if($this->hotel->isRegisterComplete()) {
					$this->_taxes();
					$this->_mealplans();
					$this->_airports();
					$this->_transport();
					$this->_contactdetail(); 
				} else {
					$this->stepRedirect();
				}
				break;		
    	}
    }
    
    protected function _airports(){    	     	
    	$this->airports  = $this->get('ServicingAirports');    	      	     	
    }
    
	protected function _room(){    	     	
    	$this->room = $this->hotel->getRoomDetail();
    }
    
	protected function _taxes(){     
    	$this->taxes		 = $this->hotel->getTaxes();
    	$this->merchant_fee  = $this->get('MerchantFee');    
    	$layout = $this->getLayout(); 
		if($layout=='taxes' && empty($this->taxes)) {
    		$app = JFactory::getApplication();
			$url = JRoute::_('index.php?option=com_sfs&view=hotelprofile&layout=formtaxes&Itemid='.JRequest::getInt('Itemid'),false);
    		$app->redirect( $url );        		
    		return false;        		
    	}          
    }
    
    protected function _mealplans(){    	  
    	$this->mealplan = $this->get('Mealplan');   
    	$layout = $this->getLayout(); 
    	if( $this->hotel->step_completed >= 3 ) {
	   		if($layout=='mealplans' && empty($this->mealplan)) {
	    		$app = JFactory::getApplication();
				$url = JRoute::_('index.php?option=com_sfs&view=hotelprofile&layout=formmealplans&Itemid='.JRequest::getInt('Itemid'),false);
	    		$app->redirect( $url );        		
	    		return false;        		
	    	}    
    	} else {
    		$this->stepRedirect();    		
    	} 
    }  
 	protected function _transport(){    	  
    	$this->transport = $this->get('Transport');  
    	$layout = $this->getLayout();     	 	   
    	if( $this->hotel->step_completed >= 4 ) {
	   		if($layout=='transport' && empty($this->transport)) {
	    		$app = JFactory::getApplication();
				$url = JRoute::_('index.php?option=com_sfs&view=hotelprofile&layout=formtransport&Itemid='.JRequest::getInt('Itemid'),false);
	    		$app->redirect( $url );        		
	    		return false;        		
	    	}   
    	} else {
    		$this->stepRedirect();    		
    	}      	 	        
    }            
    
	protected function _terms(){    	  
    	$this->hotel_admin = $this->get('HotelAdmin');       	 	        
    }  
    
    protected function _contactdetail(){    	  
    	$this->contacts = $this->hotel->getContacts();       	 
    	$this->billing	= $this->hotel->getBillingDetail();	 
    	if( $this->hotel->step_completed >= 5 ) {
			$layout = $this->getLayout();     	 	   
	   		if($layout=='contactdetail' && empty($this->billing)) {
	    		$app = JFactory::getApplication();
				$url = JRoute::_('index.php?option=com_sfs&view=hotelregister&layout=registerdetail&Itemid='.JRequest::getInt('Itemid'),false);
	    		$app->redirect( $url );        		
	    		return false;        		
	    	}    
    	} else {
    		$this->stepRedirect();    		
    	}    	
    }  
    
    protected function stepRedirect()
    {
    	$app = JFactory::getApplication();
    	$url = '';
    	$msg = '';
    	switch ( (int) $this->hotel->step_completed ) {
    		case 1:
    			$url = JRoute::_('index.php?option=com_sfs&view=hotelprofile&layout=formtaxes&Itemid=112', false);
    			break;
    		case 2:
    			$url = JRoute::_('index.php?option=com_sfs&view=hotelprofile&layout=formairports&Itemid=113', false);
    			break;    		
    		case 3:
    			$url = JRoute::_('index.php?option=com_sfs&view=hotelprofile&layout=formmealplans&Itemid=111', false);
    			break;	
    		case 4:
    			$url = JRoute::_('index.php?option=com_sfs&view=hotelprofile&layout=formtransport&Itemid=130', false);
    			break;
    		case 5:
    			$url = JRoute::_('index.php?option=com_sfs&view=hotelregister&layout=registerdetail&Itemid=110', false);
    			break;	
    		case 6:
    			$url = JRoute::_('index.php?option=com_sfs&view=hotelprofile&layout=terms', false);
    			break;		
    		default:
    			break;				
    	}
    	
    	if($url)
    	{
    		if((int)$this->hotel->step_completed > 1)
    			$msg = 'Please finish all steps in sign up process to use full SFS services';
    		$app->redirect( $url, $msg );	
    	}
    	
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
    
    function progressBar( $current=0 )
    {
        $current = ($current < 0) ? 0 : $current;
        $html = '<div id="hotel_step">';        
        $i = 0;
        while ($i <= 6) {
        	$html .= '<span class="hotel_step hotel_step_' . $i . ( ($i <= $current) ? ' active' : '' ) . '">';
			$html .= JText::_('COM_SFS_HOTEL_STEP_'.$i);
            $html .= '</span>';        		
        	$i++;
        }        
        $html .= '<br style="clear:both;"/></div>';
        return $html;
    }    
    
}


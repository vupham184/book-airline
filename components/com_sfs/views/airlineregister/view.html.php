<?php
// No direct access to this file
defined('_JEXEC') or die();

jimport('joomla.application.component.view');

class SfsViewAirlineregister extends JView
{
	protected $params;
	protected $state;
	
	function display($tpl = null) 
	{			    	    	
		$this->state       = $this->get('State');
        $this->params      = $this->state->get('params');   		
		
		$app = JFactory::getApplication();
		
		$user = JFactory::getUser();
        
        if( $user->get('id'))
        {
        	$app->redirect( JRoute::_('index.php?option=com_sfs&view=home&Itemid='.JRequest::getInt('Itemid'),false) );
        }
		
		$session = JFactory::getSession();
		
		$airline = $session->get('airline');
		$contacts = $session->get('airContacts');
		$airlineMainContactName= $session->get('airlineMainContactName');
						
		switch ( $this->getLayout() ) {
			case 'contacts':
				if( ! isset($airline) ) {
					$app->redirect( JRoute::_('index.php?option=com_sfs&view=airlineregister&Itemid='.JRequest::getInt('Itemid'),false) );
					return;
				}				
				break;
			case 'confirm':			
				if( ! count( $contacts ) ) {
					$app->redirect( JRoute::_('index.php?option=com_sfs&view=airlineregister&layout=contacts&Itemid='.JRequest::getInt('Itemid'),false) );
					return;
				}					
				break;
			case 'thankyou':			
				if( trim($airlineMainContactName) == '' ) {
					$app->redirect( JRoute::_('index.php?option=com_sfs&view=airlineregister&Itemid='.JRequest::getInt('Itemid'),false) );
					return;
				}			
				break;				
			default:
				$this->options['airline_code']		= SfsHelperField::getAirlineField('iatacode_id', 0);					
				$this->options['office_country']	= SfsHelperField::getCountryField('country_id','0');
				$this->options['office_state'] 		= SfsHelperField::getStateField( 'state_id' , 0 , 0 );
				$this->options['billing_country']	= SfsHelperField::getCountryField('billing[country_id]','0');
				$this->options['billing_state'] 	= SfsHelperField::getStateField( 'billing[state_id]' , 0 , 0 );
				$this->options['airport_code']      = SfsHelperField::getAirportField('airport_id', 0, 0 , 'class="validate-custom-required emptyValue:0"');
				/*$airport_codes = $this->get('Airportcode');
				$airportList[]	= JHTML::_('select.option',  '0', JText::_( 'Select Airport Code' ), 'value', 'text' );
				$airportList	= array_merge( $airportList, $airport_codes );				
				$this->options['airport_code'] = JHTML::_('select.genericList', $airportList,'airport_id','class="validate-custom-required emptyValue:0"', 'value', 'text', '');*/									
				break;	
		} 
		
        		
	    // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode('<br />', $errors));
            return false;
        }        
        
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
?>
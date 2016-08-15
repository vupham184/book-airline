<?php
defined('_JEXEC') or die();

class SfsViewTaxiregister extends JViewLegacy
{
	protected $params;
	protected $state;
	
	function display($tpl = null) 
	{			    	    	
		$this->state       = $this->get('State');
        $this->params      = $this->state->get('params');   		
		
		$app = JFactory::getApplication();
		
		$user = JFactory::getUser();
		
		$isGuest = $user->get('guest');
        
        if( !$isGuest )
        {
        	$app->redirect( JRoute::_('index.php?option=com_sfs&view=home&Itemid='.JRequest::getInt('Itemid'),false) );
        }
        
        $this->taxidetails 		= $app->getUserState('taxi.register.data.taxidetails', null);
		$this->accountdetails 	= $app->getUserState('taxi.register.data.accountdetails', null);	

		$country_selected		= isset($this->taxidetails) && $this->taxidetails['country_id'] ? $this->taxidetails['country_id'] : 0;
		$country_selected2		= isset($this->taxidetails) && $this->taxidetails['billing_country_id'] ? $this->taxidetails['billing_country_id'] : 0;
		
		$this->options['country_id'] 		 = SfsHelperField::getCountryField('taxidetails[country_id]',$country_selected);
		$this->options['billing_country_id'] = SfsHelperField::getCountryField('taxidetails[billing_country_id]',$country_selected2);
				        		
	    // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode('<br />', $errors));
            return false;
        }        
        
        if( $this->getLayout() == 'confirm' )
        {
        	if( empty($this->taxidetails) || empty($this->accountdetails) )
        	{
        		$this->setLayout('default');
        	}
        }
        
        $this->prepareDocument();        
		parent::display($tpl);
	}
		
	protected function prepareDocument()
    {
        $title = 'Taxi sign up';        
        $this->document->setTitle($title);
    }
		   
}

?>
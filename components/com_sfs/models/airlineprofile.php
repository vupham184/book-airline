<?php
defined('_JEXEC') or die;
jimport('joomla.application.component.model');

class SfsModelAirlineProfile extends JModel
{	
    protected function populateState()
    {
        // Get the application object.
        $app    = JFactory::getApplication();        
        $params    = $app->getParams('com_sfs');   		
        // Load the parameters.
        $this->setState('params', $params);
    }   
    
    public function saveAirline()
    {    	    	
    	$post = JRequest::get('post');
    	
    	$post['telephone'] = SfsHelper::getPhoneString($post['phone_code'],$post['phone_number']);
    	
   		$airline = new SAirline();	
   			
		if ( ! $airline->bind( $post ) ) 
		{ 
			$this->setError('Unable bind airline data');
			return false;
		}		
		$skipCheck = false;
		
		if( $airline->id && $airline->grouptype==3 ) $skipCheck = true; 
		
		if( ! $airline->save($skipCheck) ) {
			// will work with this later
			$this->setError('Unable save airline data');
			return false;
		}
		
    	// Save billing		
		$billingData  = JRequest::getVar('billing',array(),'post','array');						
		$billingData['id'] = $airline->billing_id;
		$airline->saveBillingDetail( $billingData, true);	

		// Save voucher comment
		
		if( $airline->id && $airline->grouptype==3 ){
			$voucherComment  = JRequest::getVar('vouchercomments',array(),'post','array');	
		} else {
			$voucherComment  = JRequest::getString('vouchercomment');	
		}
		
		$airline->saveVoucherComment($voucherComment);	
		
    	return true;    	    	
    }
     
    public function saveContacts()
    {
    	$contacts = JRequest::getVar('contact',array(),'array');    	
    	if( count($contacts) ) {
    		$contactModel = JModel::getInstance('Contact','SfsModel');
    		foreach ($contacts as $key => $value ) {
    			$value['id'] = $key;
    			$contactModel->save($value);
    		}    	
    	}    	
    	return true;
    }
    
    
	public function addContact($contact){
		if(empty($contact)) return false;
		
		$contactModel = JModel::getInstance('Contact','SfsModel');
		
		$result = $contactModel->save($contact);

		return $result;
	}
	
	public function changeAirline()
	{
		$post = JRequest::get('post');
    	$session = JFactory::getSession();
    	$session->set('airline_id',$post['airline_id']);
        $session->set("airport_current_id", null);
		return true;
	}
	
}

<?php
defined('_JEXEC') or die;

class SfsControllerTaxi extends JControllerLegacy
{

	public function __construct($config = array())
	{
		parent::__construct($config);
	}
	
	public function apply()
	{
		JRequest::checkToken() or die(JText::_('JINVALID_TOKEN'));
		
		$model	= $this->getModel('Taxi','SfsModel');
		
		$result = $model->save();
		
		$msg = '';
		
		if( ! $result )
		{
			$msg = $model->getError();			
		} 
		
		$link = 'index.php?option=com_sfs&view=taxi&layout=edit&airline_id='.JRequest::getInt('airline_id').'&tmpl=component';
		
		$this->setRedirect($link,$msg);	
		
		return $this;
	}	
	
	public function save()
	{
		JRequest::checkToken() or die(JText::_('JINVALID_TOKEN'));
		
		$model	= $this->getModel('Taxi','SfsModel');
		
		$result = $model->save();
		
		$msg = '';
		
		if( ! $result )
		{
			$msg = $model->getError();
			$link = 'index.php?option=com_sfs&view=taxi&layout=edit&airline_id='.JRequest::getInt('airline_id');
		} else {
			$link = 'index.php?option=com_sfs&view=taxilist';
		}
		
		$this->setRedirect($link,$msg);	
		
		return $this;
	}
	
	public function saveRate()
	{
		JRequest::checkToken() or die(JText::_('JINVALID_TOKEN'));
		
		$model	= $this->getModel('Taxi','SfsModel');
		
		$result = $model->saveRate();
		
		$msg = '';
		
		if( ! $result )
		{
			$msg = $model->getError();		
		}
		
		$link ='index.php?option=com_sfs&view=taxi&layout=rate&airline_id='.JRequest::getInt('airline_id').'&taxi_id='.JRequest::getInt('taxi_id').'&tmpl=component';
		
		$this->setRedirect($link,$msg);	
		
		return $this;
	}
	
	public function cancel()
	{
		JRequest::checkToken() or die(JText::_('JINVALID_TOKEN'));
		
		$this->setRedirect('index.php?option=com_sfs&view=taxilist');
		
		return $this;
	}
	
	public function saveCompany()
	{
		JRequest::checkToken() or die(JText::_('JINVALID_TOKEN'));
		
		$model	= $this->getModel('Taxi','SfsModel');
		
		$result = $model->saveCompany();
		$msg = '';
		
		if( ! $result )
		{
			$msg = $model->getError();		
		}
		
		$taxi_id = JRequest::getInt('taxi_id', 0);
		
		if( !$taxi_id && $result) {
			$taxi_id = (int)$result;
		}
		
		$link = 'index.php?option=com_sfs&view=taxi&layout=company&airline_id='.JRequest::getInt('airline_id').'&taxi_id='.$taxi_id.'&tmpl=component';
		
		if($result)
		{
			$link = 'index.php?option=com_sfs&view=close&reload=1';
		}
		
		$this->setRedirect($link,$msg);	
		
		return $this;
	}
	
	
	public function applyTaxi()
	{
		JRequest::checkToken() or die(JText::_('JINVALID_TOKEN'));
		$airport_id = JRequest::getVar('airport_id');
		$model	= $this->getModel('Taxi','SfsModel');
		
		$result = $model->saveTaxi();
		
		$msg = '';
		
		if( ! $result )
		{
			$msg = $model->getError();			
		} 
		if ($airport_id) {
			$link = 'index.php?option=com_sfs&view=taxi&layout=edittaxiairport&taxi_id='.JRequest::getInt('taxi_id');
		}
		else{
			$link = 'index.php?option=com_sfs&view=taxi&layout=edittaxi&taxi_id='.JRequest::getInt('taxi_id');
		}
		
		$this->setRedirect($link,$msg);	
		
		return $this;
	}	
	
	public function saveTaxi()
	{
		JRequest::checkToken() or die(JText::_('JINVALID_TOKEN'));
		
		$model	= $this->getModel('Taxi','SfsModel');
		
		$result = $model->saveTaxi();
		
		$msg = '';
		
		if( ! $result )
		{
			$msg = $model->getError();
			$link = 'index.php?option=com_sfs&view=taxi&layout=edittaxi&taxi_id='.JRequest::getInt('taxi_id');
		} else {
			$link = 'index.php?option=com_sfs&view=taxilist';
		}
		
		$this->setRedirect($link,$msg);	
		
		return $this;
	}
	
	public function approve()
	{
		JRequest::checkToken() or die(JText::_('JINVALID_TOKEN'));
		$id = JRequest::getInt('taxi_id',0);
		if( $id )
		{			
			$db = JFactory::getDbo();
			
			$query = 'UPDATE #__sfs_taxi_companies SET approved=1,published=1 WHERE id='.$id;
			$db->setQuery($query);
			$db->query();
					
			$query = 'SELECT created_by FROM #__sfs_taxi_companies WHERE id='.$id;
			$db->setQuery($query);
			$created_by = $db->loadResult();
			
			$user = JUser::getInstance($created_by);
			
			if( empty($user) ) 
			{						
				return false;
			}

			$query = 'UPDATE #__users SET block=0,activation='.$db->quote('').' WHERE id='.(int)$user->id;
			$db->setQuery($query);
			$db->query();
				
				
			$config = JFactory::getConfig();
			$data = array();								
			$data['email']		= $user->email;	
			$data['username']	= $user->username;	
			$data['fromname']	= $config->get('fromname');
			$data['mailfrom']	= $config->get('mailfrom');
			$data['sitename']	= $config->get('sitename');
			$data['siteurl']	= JUri::root();	
			$data['loginurl']	= JUri::root().'index.php?option=com_sfs&view=login&Itemid=104';	
							
			$emailSubject	= JText::_('COM_SFS_TAXI_APPROVED_SUBJECT');		
					
			$emailBody = JText::sprintf(
				'COM_SFS_TAXI_APPROVED_BODY',
				$user->name,					
				$data['siteurl'],
				$data['username'],
				$data['loginurl']
			);					
					
			$return = JUtility::sendMail($data['mailfrom'], $data['fromname'], $data['email'], $emailSubject, $emailBody);							
		
			$link = 'index.php?option=com_sfs&view=taxi&layout=edittaxi&taxi_id='.$id;
		} else {
			$link = 'index.php?option=com_sfs&view=taxilist';
		}
		$this->setRedirect($link);	
		return $this;
	}
	/*begin CPhuc*/
	public function saveTaxiAirport(){
		JRequest::checkToken() or die(JText::_('JINVALID_TOKEN'));
		$taxiDetails  	= JRequest::getVar('taxiDetails', array() , 'post' , 'array');
		$billingDetails	= JRequest::getVar('billingDetails', array() , 'post' , 'array');
		$airportID 		= JRequest::getVar('airport_id');
		
		$profile = new stdClass();
		$profile->profile_type 	= 'taxiAirport';
		$profile->name 			= $taxiDetails['nameTaxi'];
		$profile->contact_name 	= $taxiDetails['contactTaxi'];
		$profile->email 		= $taxiDetails['emailTaxi'];
		$profile->address 		= $taxiDetails['addressTaxi2'];
		$profile->address2 		= $taxiDetails['nameTaxi'];
		$profile->city 	 		= $taxiDetails['cityTaxi'];
		$profile->zipcode 		= $taxiDetails['zipcodeTaxi'];
		$profile->country_id 	= $taxiDetails['countryTaxi'];
		$profile->telephone 	= $taxiDetails['phoneTaxi'];
		$profile->mobile_phone 	= $taxiDetails['mobilePhoneTaxi'];
		$profile->fax 			= $taxiDetails['faxTaxi'];

		$profile->billing_registed_name = $billingDetails['nameContactBilling'];
		$profile->billing_address 		= $billingDetails['billingAddress'];
		$profile->billing_address_name1 = $billingDetails['address'];
		$profile->billing_address_name2 = $billingDetails['address2'];
		$profile->billing_city 			= $billingDetails['billingCity'];
		$profile->billing_zipcode 		= $billingDetails['billingZipcode'];
		$profile->billing_country_id 	= $billingDetails['billingCountry'];
		$profile->billing_vat_number  	= $taxiDetails['vatTaxi'];
		$profile->billing_telephone 	= $billingDetails['phoneTaxi'];
		$profile->billing_mobile_phone 	= $billingDetails['mobilePhoneTaxi'];		
		$profile->billing_fax 			= $billingDetails['faxTaxi'];
		$profile->billing_mail 			= $billingDetails['emailTaxi'];

		$model	= $this->getModel('Taxi','SfsModel');
		$result = $model->saveTaxiAirport($profile,$airportID);
		$msg = '';
		
		if($result)
		{
			$link = 'index.php?option=com_sfs&view=close&reload=1';
		}
		
		$this->setRedirect($link,$msg);	
		
		return $this;
	}

	/*end CPhuc*/
}


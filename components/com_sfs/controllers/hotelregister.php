<?php
defined('_JEXEC') or die();    
jimport('joomla.application.component.controller');
jimport('joomla.filter.filteroutput');
 
class SfsControllerHotelRegister extends JController
{
	
	public function save()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));

		// Initialise some variables
		$app		= JFactory::getApplication();
		$SiteName	= $app->getCfg('sitename');		
		
		$data	    = JRequest::getVar('contact', array(), 'post', 'array');
		
		$data['hotel_name']  = JRequest::getVar('hotel_name');		
		$data['chain_id'] 	 = JRequest::getInt('chain_id' , 0);
		
		$model = $this->getModel('HotelRegister','SfsModel');
				
		// Attempt to save the data.
		$return	= $model->register($data);
		
		// Check for errors.
		if ($return === false) {
			// Save the data in the session.
			$error = (string)$model->getError();
			if( JString::strpos($error, 'User name') ) {
				$data['username'] = '';
			}
			if( JString::strpos($error, 'email') ) {
				$data['email'] = '';
				$data['email2'] = '';
			}
			$app->setUserState('com_sfs.hotelregister.data', $data);

			// Redirect back to the edit screen.
//			$this->setMessage( $model->getError(), 'warning');
			$this->setRedirect(JRoute::_('index.php?option=com_sfs&view=hotelregister', false));
			return false;
		}

		// Flush the data from the session.
		$app->setUserState('com_sfs.hotelregister.data', null);
		
		$this->setMessage(JText::_('COM_SFS_REGISTRATION_WAIT_ACTIVATE'));
		$this->setRedirect(JRoute::_('index.php?option=com_users&view=login&Itemid=104', false));
	}
	
	public function savehotel()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));
		// Initialise some variables
		$app		= JFactory::getApplication();

		$post = JRequest::get('post');		
											
		$model = $this->getModel('HotelRegister','SfsModel');
		
		$result = $model->saveHotel($post);					
		
		if ($result) {								
			$app->setUserState('com_sfs.hotelregister.registerdetail.data', null);	
			$hotel = SFactory::getHotel();
			if( $hotel->isRegisterComplete() ) {
				$this->setRedirect(JRoute::_(SfsHelperRoute::getSFSRoute('hotelprofile') , false) );				
			} else{
				$this->setRedirect(JRoute::_('index.php?option=com_sfs&view=hotelprofile&layout=terms&Itemid=172',false) );
			}			
			return true;			
		}		
		$app->setUserState('com_sfs.hotelregister.registerdetail.data', $post);		
//		$this->setMessage( $model->getError() );
		$this->setRedirect( JRoute::_(SfsHelperRoute::getSFSRoute('hotelregister','registerdetail'), false ));				
	}
	
	public function addContact()
	{		
		// Check for request forgeries
		JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));				
		$hotel = SFactory::getHotel();
		
		if ( SFSAccess::check(JFactory::getUser(), 'h.admin') && $hotel->id ) {
			$contact = JRequest::get('post');								
			$contact['contact_type'] = 4;			
			$contact['telephone'] = SfsHelper::getPhoneString( $contact['tel_int'], $contact['tel_num'] );				
			$contact['fax'] = SfsHelper::getPhoneString($contact['fax_int'], $contact['fax_num']);				
			$contact['mobile'] 	= SfsHelper::getPhoneString($contact['mobile_int'], $contact['mobile_num']);		
			$session = JFactory::getSession();
			
			$tmpHotelContact = $session->get('tmpHotelContact');
						
			if( !isset($tmpHotelContact) ){
				$tmpHotelContact = array();
			}
			
			$tmpHotelContact[] = $contact;
			
			$session->set('tmpHotelContact',$tmpHotelContact);			
		}			
		$this->setRedirect(JRoute::_('index.php?option=com_sfs&view=close&tmpl=component', false));						
	}	
	
}


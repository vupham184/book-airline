<?php
defined('_JEXEC') or die;
jimport('joomla.application.component.controller');

class SfsControllerContact extends JController
{
	public function save() 
	{
		JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));
		
		$user = JFactory::getUser();
		
		if( ! $user->id  ) {		
			return;					
		}

		$id = JRequest::getInt('id');
		
		if( $id == 0 ) return;
		
		$contact = JRequest::getVar('contact', array() , 'post' , 'array' );
		
		$table = JTable::getInstance('SFSContact','JTable');
		$table->load($id);
				
		$table->job_title	= $contact['job_title'];
		$table->name 		= $contact['name'];
		$table->surname 	= $contact['surname'];
		$table->gender 		= $contact['gender'];
		$table->telephone   = SfsHelper::getPhoneString( $contact['phone_code'], $contact['phone_number']);
		$table->fax 		= SfsHelper::getPhoneString( $contact['fax_code'], $contact['fax_number']);
		$table->mobile 		= SfsHelper::getPhoneString( $contact['mobile_code'], $contact['mobile_number']);
		
		$msg = '';
		
		if( ! $table->store() ) {
			$msg = 'can not store contact data';
		}
	
		$user->name = $table->name.' '.$table->surname ;		
		$user->email = 	trim($contact['email']);				
		
		if( ! $user->save() ) {
			$msg = 'E-Mail in use';
		}		
		
		$url = JRoute::_('index.php?option=com_sfs&view=contact&Itemid='.JRequest::getInt('Itemid'), false);
		$this->setRedirect($url,$msg);		
	}	
}


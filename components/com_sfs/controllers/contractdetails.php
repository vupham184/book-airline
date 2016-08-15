<?php
defined('_JEXEC') or die;

class SfsControllerContractdetails extends JControllerLegacy
{	
	
	public function save()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));
		
		$db = JFactory::getDbo();
		$user 	= JFactory::getUser();
		$airline = SFactory::getAirline();
		//echo $airline->id;
		
		if( SFSAccess::check( $user, 'a.admin') ) {
			
			$text 		= JRequest::getString('contractdetails');
			$airlineId  = JRequest::getInt('airline_id');
			
			if( (int) $airline->id > 0 && $airlineId > 0 )
			{				
				$query = 'UPDATE #__sfs_groundhandlers_airlines SET contract_details='.$db->quote($text);
				$query .=' WHERE ground_id='.(int)$airline->id.' AND airline_id='.(int)$airlineId;
				
				$db->setQuery($query);
				$db->query();			
			}
			
		}
		$this->setRedirect( JRoute::_('index.php?option=com_sfs&view=contractdetails&Itemid='.JRequest::getInt('Itemid'),false));
	}
	
}
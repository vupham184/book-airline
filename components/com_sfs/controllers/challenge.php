<?php
defined('_JEXEC') or die;

class SfsControllerChallenge extends JControllerLegacy
{
	public function send()
	{			
		JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));
		
		$post 	 = JRequest::get('post');
		$airline = SFactory::getAirline();
		
		$id = (int) $post['block_id'];
		
		if ( $id <= 0 ) {
			JFactory::getApplication()->close();
		}
		
		$challenge_type = (int) $post['challenge_type'];
		
		if( $challenge_type < 1 || $challenge_type  > 2 ) 
		{
			JFactory::getApplication()->close();
		}
				
		$reservation = SReservation::getInstance($id);
		
		if( isset($reservation) && $reservation->id ) 
		{
			
			// this task for the airline only
			if( $challenge_type==1 && $reservation->status == 'T' ) 
			{
				$db = JFactory::getDbo();
				$db->setQuery('UPDATE #__sfs_reservations SET status='.$db->quote('C').' WHERE id='.$id);
				if( !$db->query() )
				{
					throw new JException( 'Can not change status to Challenge' );
				}
				if( (int) $reservation->association_id > 0 )
				{
					$association = SFactory::getAssociation($reservation->association_id);
					$assocQuery  = 'UPDATE #__sfs_reservation_airport_map SET status='.$db->quote('C');
					$assocQuery .= ' WHERE reservation_id='.$reservation->id.' AND airport='.$association->db->quote($airline->airport_code);
					$association->db->setQuery($assocQuery);
					$association->db->query();
				}				
										
				//email to hotel				
				SEmail::airlineChangeStatusTo('C', $reservation);												
			}		
						
			//insert message
			$message = JRequest::getVar('message');
			$user = JFactory::getUser();
			$tmp = new stdClass();
			
			$tmp->block_id 	 = $id;
			$tmp->from     	 = $user->id;
			$tmp->type    	 = $challenge_type;
			$tmp->from_name  = $user->name;
			$tmp->posted_date = JFactory::getDate()->toSQL();
			$tmp->body = str_replace(array("\n"), '<br />',$message);
			
			if( ! $db->insertObject('#__sfs_messages', $tmp) ) {
				throw new JException( $db->getErrorMsg() );
			}
			
			SEmail::messageToHotel('C', $reservation, str_replace(array("\n"), '<br />',$message));
			
			if( $challenge_type == 2 ) {
				$link = JRoute::_('index.php?option=com_sfs&view=rooming&layout=challenge&code='.$reservation->blockcode.'&Itemid='.$post['Itemid']);
				$this->setRedirect($link);
				return;
			}
			
		}		
		$link = JRoute::_('index.php?option=com_sfs&view=close&closetype=closechallenge&tmpl=component&id='.$id.'&Itemid='.JRequest::getInt('Itemid'), false);
		$this->setRedirect($link);		
	}	
}


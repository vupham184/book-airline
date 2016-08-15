<?php
// No direct access
defined('_JEXEC') or die;

class SfsuserControllerLogin extends JControllerLegacy
{	
	public function check()
	{				
		$session = JFactory::getSession();
		
		$username = JRequest::getVar('username', '', 'method', 'username');
		$password = JRequest::getString('password', '', 'method', JREQUEST_ALLOWRAW);
		
		$session->set('sfsUsername',$username);
		$session->set('sfsPassword',$password);
		
		$db = JFactory::getDbo();
		
		$query = $db->getQuery(true);

		$query->select('s.time, s.client_id, u.id, u.name, u.username');
		$query->from('#__session AS s');
		$query->leftJoin('#__users AS u ON s.userid = u.id');
		$query->where('s.guest = 0');
		$query->where('u.username = '.$db->quote($username));
		$db->setQuery($query);
		
		$results = $db->loadObjectList();
		
		if( count($results) ) 
		{
			$response = array(
				'status' => '0'							
			);
		} else {
			$response = array(
				'status' => '1'							
			);
		}
		
		echo json_encode($response);
		
		JFactory::getApplication()->close();
	}
}

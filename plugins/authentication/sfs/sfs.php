<?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

class plgAuthenticationSfs extends JPlugin
{
	
	function onUserAuthenticate($credentials, $options, &$response)
	{				
		$response->type = 'Sfs';

		if ($credentials['secret_key'])
		{		
			// Get a database object
			$db		= JFactory::getDbo();
			$query	= $db->getQuery(true);
			
			$query->select('a.secret_key,u.id,u.username,u.name,u.password,u.email');
			$query->from('#__sfs_contacts AS a');
			$query->innerJoin('#__users AS u ON u.id=a.user_id');	
			$query->where('a.secret_key='.$db->quote($credentials['secret_key']));
			$query->where('u.block=0');
	
			$db->setQuery($query);
			$result = $db->loadObject();
			
			if($result){
				$user = JUser::getInstance($result->id); // Bring this in line with the rest of the system
				$response->email = $user->email;
				$response->fullname = $user->name;
				if (JFactory::getApplication()->isAdmin()) {
					$response->language = $user->getParam('admin_language');
				}
				else {
					$response->language = $user->getParam('language');
				}
				
				$response->status			= JAUTHENTICATE_STATUS_SUCCESS;
				$response->error_message	= '';
				return true;		
			} else {
				$response->status			= JAUTHENTICATE_STATUS_FAILURE;
				$response->error_message	= 'Could not authenticate. Your secret key was invalid';
				return false;
			}
		}
		else
		{
			$response->status			= JAUTHENTICATE_STATUS_FAILURE;
			$response->error_message	= 'Could not authenticate. Your secret key was invalid';
			return false;
		}
	}
}

<?php
defined('_JEXEC') or die;

class SfsuserController extends JControllerLegacy
{

	public function display($cachable = false, $urlparams = false)
	{
		$cachable = false;

		// Set the default view name and format from the Request.
		$vName		= JRequest::getCmd('view', 'login');
		JRequest::setVar('view', $vName);
		
		$uk = JRequest::getVar('uk');
		$return_link = JRequest::getVar('return_link');
		
		if($uk)
		{			
			$this->login($uk,$return_link);
		}
		
		return parent::display($cachable, array('Itemid'=>'INT'));
	}
	
	
	private function login($uk,$return_link=null)
	{
		$app  = JFactory::getApplication();
		$db   = JFactory::getDbo();		
		$user = JFactory::getUser();
		
		if($user->get('guest'))
		{
			$query = $db->getQuery(true);
			$query->select('a.secret_key,a.return_url,u.id,u.username,u.name,u.password');
			$query->from('#__sfs_contacts AS a');
			$query->innerJoin('#__users AS u ON u.id=a.user_id');
			
			$query->where('a.secret_key='.$db->quote($uk));
			$query->where('u.block=0');
				
			$db->setQuery($query);
			$sfsUser = $db->loadObject();
			
			if($sfsUser && $sfsUser->username && $sfsUser->id)
			{		
				$query->clear();
				$query->select('s.time, s.client_id, u.id, u.name, u.username');
				$query->from('#__session AS s');
				$query->leftJoin('#__users AS u ON s.userid = u.id');
				$query->where('s.guest = 0');
				$query->where('u.username = '.$db->quote($sfsUser->username));
				$db->setQuery($query);
				
				$userSess = $db->loadObject();
				
				if($userSess) 
				{		
					$error = $app->logout($userSess->id);			
				}				
				
				$data = array();
				
				if($sfsUser->return_url){
					$data['return'] = $sfsUser->return_url;
				} else {
					$data['return'] = 'index.php?option=com_sfs&view=dashboard&Itemid=103';	
				}
				
				if($return_link !== null)
				{
					$data['return'] = base64_decode($return_link);
				}
				
				$data['username'] = $sfsUser->username;
				$data['password'] = $sfsUser->password;	

				// Set the return URL in the user state to allow modification by plugins
				$app->setUserState('users.login.form.return', $data['return']);
				
				// Get the log in options.
				$options = array();
				$options['remember']   = JRequest::getBool('remember', false);
				$options['return']     = $data['return'];
						
				// Get the log in credentials.
				$credentials = array();
				$credentials['username']   = $data['username'];
				$credentials['password']   = $data['password'];
				$credentials['secret_key'] = $sfsUser->secret_key;
				
				// Perform the log in.
				$ok = $app->login($credentials, $options);
				
				if (true === $ok ) {
					// Success
					$app->setUserState('users.login.form.data', array());
					$app->redirect(JRoute::_($app->getUserState('users.login.form.return'), false));
					
				} else {
					$app->redirect(JRoute::_('index.php?option=com_sfsuser&view=login&Itemid=104', false));									
				}
								
			} else {
				$app->redirect(JRoute::_('index.php?option=com_sfsuser&view=login&Itemid=104', false),'Could not authenticate. Your secret key was invalid');
			}
		} else {
			$app->logout();
			$redirectUrl = 'index.php?option=com_sfsuser&uk='.$uk;
			if($return_link !== null)
			{
				$redirectUrl .= '&return_link='.$return_link;
			}
			$app->redirect(JRoute::_($redirectUrl, false));		
		}		
	}
	
}

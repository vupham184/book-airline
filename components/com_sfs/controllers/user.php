<?php
defined('_JEXEC') or die;

require_once JPATH_COMPONENT.'/controllers/sfscontroller.php';

jimport('joomla.user.helper');

class SfsControllerUser extends SFSController
{		
	public function __construct($config = array())
	{
		parent::__construct($config);						
	}		
		
	public function login() 
	{
		JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));
		// Initialise some variables
		$app		= JFactory::getApplication();	

		$post = JRequest::get('post');
		
		$falseUrl = JRoute::_('index.php?option=com_sfs&view=login',false);
		
		//check old username and old pass
		if (empty($post['old_password'])) {			
			return false;
		}
		if (empty($post['old_username'])) {			
			return false;
		}
		
		$post['old_username'] = trim($post['old_username']);
		
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);

		$query->select('id, password');
		$query->from('#__users');
		$query->where('username=' . $db->Quote($post['old_username']));

		$db->setQuery($query);
		$result = $db->loadObject();
		
		if ($result) {
			
			$parts	= explode(':', $result->password);
			$crypt	= $parts[0];
			$salt	= @$parts[1];
			$testcrypt = JUserHelper::getCryptedPassword( $post['old_password'] , $salt);

			if ($crypt == $testcrypt) {				
				if (empty($post['new_username'])) {		
					$this->setRedirect($falseUrl,'New username is invalid');	
					return false;
				}				
				if (empty($post['new_password'])) {	
					$this->setRedirect($falseUrl,'New password is invalid');		
					return false;
				}
				if (empty($post['new_password2'])) {
					$this->setRedirect($falseUrl,'Re-type password is invalid');			
					return false;
				}								
				if( trim($post['new_password']) != trim($post['new_password2']) ) {
					$this->setRedirect($falseUrl,'The password does not match');			
					return false;
				}			

				
				$query	= $db->getQuery(true);
				
				$post['new_username'] = trim($post['new_username']);
				
				if( $post['old_username'] != $post['new_username'] ){
					$query->select('COUNT(*)');
					$query->from('#__users');
					$query->where('username=' . $db->Quote($post['new_username']));
			
					$db->setQuery($query);
					$count_user = (int) $db->loadResult();
					
					if( $count_user > 0) {
						$this->setRedirect($falseUrl,'New Username in use. Please retry');			
						return false;					
					}
				}
				
				
				//update new username pass
								
				$salt = JUserHelper::genRandomPassword(32);
				$crypt = JUserHelper::getCryptedPassword($post['new_password'], $salt);
				$newPassword = $crypt.':'.$salt;				
				
				$query = 'UPDATE #__users SET username='.$db->Quote($post['new_username']).',password='.$db->Quote( $newPassword );
				$query .= ' WHERE id='.$result->id;
				$db->setQuery($query);
										
				if( ! $db->query() ) {
					throw new Exception( $db->getErrorMsg() );
				}				
				
				// Get the log in credentials.
				$credentials = array();
				$credentials['username'] = $post['new_username'];
				$credentials['password'] = $post['new_password'];
		
				// Perform the log in.
				$options = array();
				$options['remember'] = JRequest::getBool('remember', false);							
				$options['return'] = JRoute::_('index.php?option=com_sfs&view=home',false);			
				
				$error = $app->login($credentials, $options);
				
				// Check if the log in succeeded.
				if (!JError::isError($error)) {					
					$app->redirect( JRoute::_($options['return'], false) );				
				} else {		
					$data['remember'] = (int)$options['remember'];					
					$app->redirect(JRoute::_('index.php?option=com_users&view=login', false));
				}				
				
				$this->setRedirect( JRoute::_('index.php?option=com_users&view=login' , false), 'successfully modified' );
				return true;
			} else {
				$msg = 'Received password is not correct';		
				$this->setRedirect($falseUrl,$msg);
			}
		} else {
			$msg = 'Received username is not correct';
			$this->setRedirect($falseUrl,$msg);
			return false;
		}		
		
	}	
	
	public function timezone()
	{
		JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));
		// Initialise some variables
		$app		= JFactory::getApplication();	

		$post = JRequest::get('post');
		
		$user = JFactory::getUser();
		$user->setParam('timezone',$post['time_zone']);
		
		$user->save(true);
		
		$this->setRedirect( JRoute::_('index.php?option=com_sfs&view=timezone&Itemid='.$post['Itemid'],false) );		
		return true;		
	}
	
}


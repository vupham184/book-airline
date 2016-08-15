<?php
defined('_JEXEC') or die;

class SfsuserControllerUser extends JControllerLegacy
{
	/**
	 * Method to log in a user.
	 *
	 */
	public function login()
	{
        $app = JFactory::getApplication();
        if(!JSession::checkToken('post')) {
            $app->redirect(JRoute::_('index.php?option=com_content&view=featured&Itemid=101', false));
        }
        $user = JFactory::getUser();
		if( ! $user->get('guest') ) {
            $redirectUrl = JURI::base().'index.php';
            JFactory::getDocument()->addScriptDeclaration('
			window.parent.location.href="'.$redirectUrl.'";
			window.parent.SqueezeBox.close();
		    ');
            return;
		}
		$session = JFactory::getSession();
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		// Populate the data array:
		$data = array();
		$data['return'] = base64_decode(JRequest::getVar('return', '', 'POST', 'BASE64'));
		$data['username'] = JRequest::getVar('username', '', 'method', 'username');
		$data['password'] = JRequest::getString('password', '', 'method', JREQUEST_ALLOWRAW);

		$is_popup 		  = JRequest::getInt('is_popup', 0);

		if( (int)$is_popup == 1 ) {
			$data['password'] = base64_decode($data['password']);
		}

		$query->select('s.time, s.client_id, u.id, u.name, u.username');
		$query->from('#__session AS s');
		$query->leftJoin('#__users AS u ON s.userid = u.id');
		$query->where('s.guest = 0');
		$query->where('u.username = '.$db->quote($data['username']));
		$db->setQuery($query);

		$userSess = $db->loadObject();

		// Set the return URL if empty.
		if (empty($data['return'])) {
			$data['return'] = 'index.php?option=com_sfs&view=dashboard&Itemid=103';
		}

		// Set the return URL in the user state to allow modification by plugins
		$app->setUserState('users.login.form.return', $data['return']);

		// Get the log in options.
		$options = array();
		$options['remember'] = JRequest::getBool('remember', false);
		$options['return'] = $data['return'];

		// Get the log in credentials.
		$credentials = array();
		$credentials['username'] = $data['username'];
		$credentials['password'] = $data['password'];

		// Check password
		jimport('joomla.user.helper');
		$query->clear();
		$query->select('id, password');
		$query->from('#__users');
		$query->where('username=' . $db->Quote($credentials['username']));

		$db->setQuery($query);
		$result = $db->loadObject();

		$ok = false;

		if ($result) {
			$parts	= explode(':', $result->password);
			$crypt	= $parts[0];
			$salt	= @$parts[1];
			$testcrypt = JUserHelper::getCryptedPassword($credentials['password'], $salt);
			if ($crypt == $testcrypt) {
				$ok = true;
			}
		}

		if(!$ok) {
			// Password Incorect!
			$data['remember'] = (int)$options['remember'];
			$app->setUserState('users.login.form.data', $data);
			$app->setUserState('sfsLoginStatus', 1);
			if( (int)$is_popup == 1 ) {

				$app->redirect(JRoute::_('index.php?option=com_sfsuser&view=close&tmpl=component&loginerror=1', false));
			} else {
				$app->redirect(JRoute::_('index.php?option=com_content&view=featured&Itemid=101', false));
			}
			return;
		}

		if($userSess)
		{
			$error = $app->logout($userSess->id);
		}

		// Perform the log in.
		$ok = $app->login($credentials, $options);

		if (true === $ok ) {
			// Success
			$app->setUserState('users.login.form.data', array());
			if( (int)$is_popup == 1 ) {
				$app->redirect(JRoute::_('index.php?option=com_sfsuser&view=close&tmpl=component&loginerror=0', false));
			} else {
				$app->redirect(JRoute::_($app->getUserState('users.login.form.return'), false));
			}

		} else {
			// Login failed !
			$data['remember'] = (int)$options['remember'];
			$app->setUserState('users.login.form.data', $data);
			$app->setUserState('sfsLoginStatus', 1);
			if( (int)$is_popup == 1 ) {
				$app->redirect(JRoute::_('index.php?option=com_sfsuser&view=close&tmpl=component&loginerror=1', false));
			} else {
				$app->redirect(JRoute::_('index.php?option=com_content&view=featured&Itemid=101', false));
			}
		}
	}

}

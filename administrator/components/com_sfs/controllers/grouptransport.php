<?php
defined('_JEXEC') or die;

class SfsControllerGrouptransport extends JControllerLegacy
{

	public function __construct($config = array())
	{
		parent::__construct($config);
	}
		
	public function save()
	{
		JRequest::checkToken() or die(JText::_('JINVALID_TOKEN'));
		
		$model	= $this->getModel('Grouptransport','SfsModel');
		
		$result = $model->save();
		
		$msg = '';
		
		if( ! $result )
		{
			$msg = $model->getError();		
		}
		
		$id = JRequest::getInt('id', 0);
		
		if( !$id && $result) {
			$id = (int)$result;
		}
		
		$link = 'index.php?option=com_sfs&view=grouptransport&layout=edit&id='.$id;
		
		if($result)
		{
			$link = 'index.php?option=com_sfs&view=grouptransportlist';
		}
		
		$this->setRedirect($link,$msg);	
		
		return $this;
	}
	
	public function apply()
	{
		JRequest::checkToken() or die(JText::_('JINVALID_TOKEN'));
		
		$model	= $this->getModel('Grouptransport','SfsModel');
		
		$result = $model->save();
		
		$msg = '';
		
		if( ! $result )
		{
			$msg = $model->getError();		
		}
		
		$id = JRequest::getInt('id', 0);
		
		if( !$id && $result) {
			$id = (int)$result;
		}
		
		$link = 'index.php?option=com_sfs&view=grouptransport&layout=edit&id='.$id;
						
		$this->setRedirect($link,$msg);	
		
		return $this;
	}
	
	
	public function saveRate()
	{
		JRequest::checkToken() or die(JText::_('JINVALID_TOKEN'));
		
		$model	= $this->getModel('Grouptransport','SfsModel');
		
		$result = $model->saveRate();
		
		$msg = '';
		
		if( ! $result )
		{
			$msg = $model->getError();		
		}
		
		$id = JRequest::getInt('id');
		$group_type_id = JRequest::getInt('group_transportation_type_id');
		
		$link = 'index.php?option=com_sfs&view=grouptransport&layout=rates&id='.$id.'&type_id='.$group_type_id.'&tmpl=component';		
		
		$this->setRedirect($link,$msg);	
		
		return $this;
	}

	public function saveRateFixed()
	{
		JRequest::checkToken() or die(JText::_('JINVALID_TOKEN'));
		
		$model	= $this->getModel('Grouptransport','SfsModel');
		
		$result = $model->saveRateFixed();
		
		$msg = '';
		
		if( ! $result )
		{
			$msg = $model->getError();		
		}
		
		$id = JRequest::getInt('id');
		$group_type_id = JRequest::getInt('group_transportation_type_id');
		
		$link = 'index.php?option=com_sfs&view=grouptransport&layout=rates_fixed&id='.$id.'&type_id='.$group_type_id.'&tmpl=component';		
		
		$this->setRedirect($link,$msg);	
		
		return $this;
	}

	public function saveRateFixededit()
	{
		//JRequest::checkToken() or die(JText::_('JINVALID_TOKEN'));
		
		$model	= $this->getModel('Grouptransport','SfsModel');
		
		$result = $model->saveRateFixededit();
		
		$msg = '';
		
		if( ! $result )
		{
			$msg = $model->getError();		
		}
		
		$id = JRequest::getInt('id');
		$group_type_id = JRequest::getInt('group_transportation_type_id');
		
		$link = 'index.php?option=com_sfs&view=grouptransport&layout=rates_fixed&id='.$id.'&type_id='.$group_type_id.'&tmpl=component';		
		
		$this->setRedirect($link,$msg);	
		
		return $this;
	}
	
	public function approve()
	{
		JRequest::checkToken() or die(JText::_('JINVALID_TOKEN'));
		$id = JRequest::getInt('id');
		if( $id )
		{			
			$db = JFactory::getDbo();
			$query = 'UPDATE #__sfs_group_transportations SET approved=1,published=1 WHERE id='.$id;
			$db->setQuery($query);
			$db->query();
			
			$query = 'SELECT created_by FROM #__sfs_group_transportations WHERE id='.$id;
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
							
			$emailSubject	= JText::_('COM_SFS_BUS_APPROVED_SUBJECT');		
					
			$emailBody = JText::sprintf(
				'COM_SFS_BUS_APPROVED_BODY',
				$user->name,					
				$data['siteurl'],
				$data['username'],
				$data['loginurl']
			);					
					
			$return = JUtility::sendMail($data['mailfrom'], $data['fromname'], $data['email'], $emailSubject, $emailBody);
			$link = 'index.php?option=com_sfs&view=grouptransport&layout=edit&id='.$id;
		} else {
			$link = 'index.php?option=com_sfs&view=grouptransportlist';
		}
		$this->setRedirect($link);	
		return $this;
	}
	
	public function cancel()
	{
		$this->setRedirect('index.php?option=com_sfs&view=grouptransportlist',$msg);
		return $this;	
	}
	
	public function newUser() 
	{
		JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));		
		$model	= $this->getModel('Grouptransport','SfsModel');		
		if( $model->newUser() ) {
			$this->setRedirect('index.php?option=com_sfs&view=grouptransport&layout=newuser&tmpl=component&id='.JRequest::getInt('id'));	
		} else {
			$msg = $model->getError();
			$this->setRedirect('index.php?option=com_sfs&view=grouptransport&layout=newuser&tmpl=component&id='.JRequest::getInt('id'),$msg);
		}		
		
		return true;
	}	

	public function addType(){		
		JRequest::checkToken() or die(JText::_('JINVALID_TOKEN'));
		
		$model	= $this->getModel('Grouptransport','SfsModel');
		
		$result = $model->addType();
		
		$msg = '';
		
		if( ! $result )
		{
			$msg = $model->getError();		
		}
		
		$id = JRequest::getInt('id');
		$group_type_id = JRequest::getInt('group_transportation_type_id');
		
		$link = 'index.php?option=com_sfs&view=grouptransport&layout=types&id='.$id.'&tmpl=component';		
		
		$this->setRedirect($link,$msg);	
		
		return true;
	}

	public function addTypeFixed(){		
		JRequest::checkToken() or die(JText::_('JINVALID_TOKEN'));
		
		$model	= $this->getModel('Grouptransport','SfsModel');
		
		$result = $model->addTypeFixed();
		
		$msg = '';
		
		if( ! $result )
		{
			$msg = $model->getError();		
		}
		
		$id = JRequest::getInt('id');
		$group_type_id = JRequest::getInt('group_transportation_type_id');
		
		$link = 'index.php?option=com_sfs&view=grouptransport&layout=types_fixed&id='.$id.'&tmpl=component';		
		
		$this->setRedirect($link,$msg);	
		
		return true;
	}

}


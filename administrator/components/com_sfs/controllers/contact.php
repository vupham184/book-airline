<?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');


class SfsControllerContact extends JController
{

	function __construct($config = array())
	{
		parent::__construct($config);	
	}

	public function edit() {
		// Initialise variables.
		$app		= JFactory::getApplication();
		
		$model		= $this->getModel('Contact','SfsModel');
		
		$id = $model->getState('contact.id');
		
		$this->setRedirect('index.php?option=com_sfs&view=contact&layout=edit&id='.$id);							
	}
	
	public function cancel() 
	{
		JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));		
		$this->setRedirect('index.php?option=com_sfs&view=contacts');							
	}

	public function save() 
	{		
		JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));	
		
		$post = JRequest::get('post');
		
		require_once JPATH_ROOT.'/components/com_sfs/tables/sfscontact.php';
		
		$table = JTable::getInstance('Sfscontact','JTable');
		
		if( ! $table->bind($post) ) {
			throw new Exception($table->getError());
		}
		if( ! $table->check() ) {
			throw new Exception($table->getError());
		}
		if( ! $table->store() ) {
			throw new Exception($table->getError());
		}		
		$user = JUser::getInstance($post['user_id']);
		$userData = array();
		$userData['name'] =  $post['name'].' '.$post['surname'];
		$userData['email'] = $post['email'];
		
		if( ! $user->bind($userData) ) {
			throw new Exception($user->getError());
		}
		if( ! $user->save(true) ) {
			throw new Exception($user->getError());
		}
		
		$tmpl = JRequest::getVar('tmpl');
		
		if($tmpl=='component')
		{
			$this->setRedirect('index.php?option=com_sfs&view=close&reload=1');	
		} else {
			$this->setRedirect('index.php?option=com_sfs&view=contacts');	
		}
		
		return $this;							
	}			
	
}


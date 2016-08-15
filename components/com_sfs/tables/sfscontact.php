<?php
// No direct access
defined('JPATH_BASE') or die;

jimport('joomla.database.table');

class JTableSFSContact extends JTable
{	
	var $id = null;
	var $grouptype = null;
	var $group_id = null;	 
	var $user_id = null;
	var $is_admin = null;
	var $gender = null;
	var $name = null;
	var $surname = null;
	var $contact_type = null;
	var $job_title = null;
	var $telephone = null;
	var $fax = null;
	var $mobile = null;
	var $systemEmails = null;
	var $secret_key = null;
	var $return_url = null;
	
	public function __construct(&$db)
	{
		parent::__construct('#__sfs_contacts', 'id', $db);
	}
	
	public function check()
	{						
		if ( trim($this->name) == '' ) {
			$this->setError(JText::_('SFSContact: Name is invalid'));
			return false;
		}		
		return true;
	}
	
}

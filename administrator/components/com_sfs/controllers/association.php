<?php
defined('_JEXEC') or die;

class SfsControllerAssociation extends JControllerLegacy
{

	public function __construct($config = array())
	{
		parent::__construct($config);
	}
		
	public function save()
	{
		JRequest::checkToken() or die(JText::_('JINVALID_TOKEN'));
		
		$name = JRequest::getVar('name');
		$code = JRequest::getVar('code');
		$airport_host = JRequest::getVar('airport_host');
		$airport_user = JRequest::getVar('airport_user');
		$airport_password = JRequest::getVar('airport_password');
		$airport_database = JRequest::getVar('airport_database');
		
		$enabled = JRequest::getInt('state',0);
		
		$msg  = '';
	
		$option = array(); //prevent problems
 
		$option['driver']   = 'mysql';            // Database driver name
		$option['host']     = $airport_host;      // Database host name
		$option['user']     = $airport_user;      // User for database authentication
		$option['password'] = $airport_password;  // Password for database authentication
		$option['database'] = $airport_database;  // Database name
		$option['prefix']   = 'jos_';             // Database prefix (may be empty)
		 
		$edb = JDatabase::getInstance( $option );
				
		$test = $edb->connected();
		
		if(!$test)
		{
			$msg = 'Can not connect to the server';
		} else {						
			$msg = 'Successfully';
			$edb->setQuery('SELECT COUNT(*) FROM #__sfs_hotel');
			$result = $edb->loadResult();
			if( $edb->getErrorNum() ) {
				$msg = 'Can not connect to the database';
			} else {
				// Everything is ok. 
				unset($edb);
				$db = JFactory::getDbo();
				
				$row = new stdClass();
				
				$row->name = $name;
				$row->code = $code;
				$row->airport_host = $airport_host;
				$row->airport_user = $airport_user;
				$row->airport_password = base64_encode($airport_password);
				$row->airport_database = $airport_database;
				$row->state = $enabled;
				
				$id = JRequest::getInt('id');
				
				if( $id )
				{
					$row->id  = $id;
					$db->updateObject('#__sfs_airport_associations', $row,'id');
				} else {
					$db->insertObject('#__sfs_airport_associations', $row);
				}
				
			}
		}
		
		$link = 'index.php?option=com_sfs&view=association&layout=edit&id='.JRequest::getInt('id').'&tmpl=component';				
		$this->setRedirect($link,$msg);	
		
		return $this;
	}	

}


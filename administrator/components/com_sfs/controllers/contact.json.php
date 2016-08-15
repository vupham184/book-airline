<?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');


class SfsControllerContact extends JController
{

	public function __construct($config = array())
	{
		parent::__construct($config);
		
		$this->registerTask('generatesk', 'generateSecretKey');
	}


	public function generateSecretKey()
	{
		jimport('joomla.user.helper');
		
		$db = JFactory::getDbo();	
											
		$secretkey = '';
		
		while(true)
		{
			$secretkey = JUserHelper::genRandomPassword(32);
			
			$query = 'SELECT COUNT(*) FROM #__sfs_contacts WHERE secret_key='.$db->quote($secretkey);
			$db->setQuery($query);
			
			$count = $db->loadResult();
			
			if( (int) $count == 0 )
			{
				break;
			}
		}
		
		$response = array(
			'success' => '1',
			'secretkey' => $secretkey									
		);
		
		echo json_encode($response);		
		JFactory::getApplication()->close();
	}
	
}


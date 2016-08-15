<?php
defined('_JEXEC') or die();

class SfsControllerUpdate extends JControllerLegacy
{	
	
	public function __construct($config = array())
	{
		parent::__construct($config);
	}	
	
	public function update()
	{		
		JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		jimport('joomla.filesystem.file');
		jimport('joomla.filesystem.folder');
		jimport('joomla.filesystem.archive');
		
		$db		= JFactory::getDbo();		
		$model	= $this->getModel('Update','SfsModel');
		$msg 	= '';
		
		$query = $db->getQuery(true);
		
		$latestVersion = $model->getCurrentDBVersion();

		$path = JPATH_ROOT;
		
		$array = JString::str_split($path,1);
		
		$i = count($array) - 1;
		
		while($i > 0)
		{
			if( $array[$i] == DS )
			{
				break;	
			}
			$i = $i - 1;
		}
		
		$updatePath = '';
		
		for ($j=0;$j<=$i;$j++)
		{
			$updatePath .= (string)$array[$j] ;
		}

		$updatePath = $updatePath.'updates';
				
		$updateFileName = 'com_sfs-'.$latestVersion.'.zip';
		
		if( ! file_exists($updatePath.DS.$updateFileName) )
		{
			$msg = 'Update package was not found';
		} else {
			$result = $model->extractPackage($updatePath,$updateFileName);
			if($result === false)
			{
				$msg = 'Can not extract update package';
			} else {
								
				$tmpFolder = $result;
				
				$result = $model->updateFiles($tmpFolder);		
					
				if(!$result)
				{
					$msg = 'Copping files error';
				} else {
					$msg = 'Successfully updated to latest version';
				}
				
			}
		}
		
		$url = JRoute::_('index.php?option=com_sfs&view=update',false);		
		$this->setRedirect($url,$msg);
	}
	
	
}

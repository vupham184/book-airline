<?php
defined('_JEXEC') or die;

class SfsModelUpdate extends JModelLegacy
{

	protected function populateState()
	{
        $app = JFactory::getApplication('administrator');
	}
	
	public function getCurrentXMLVersion()
	{
		$version = null;
		
		$xmlFile = JPATH_COMPONENT.DS.'sfs.xml';
		
		$xml = simplexml_load_file($xmlFile);
		
		if($xml->version)
		{
			$version = $xml->version;
		}
		
		return $version;	
	}
	
	public function getCurrentDBVersion()
	{
		$version = null;
		
		$db = $this->getDbo();		
		$query = 'SELECT manifest_cache FROM #__extensions WHERE element='.$db->quote('com_sfs');		
		$db->setQuery($query);
		
		$manifest_cache = $db->loadResult();
		
		if($manifest_cache)
		{
			$registry = new JRegistry();
			$registry->loadString($manifest_cache);
			
			$version = $registry->get('version',null);
		}
		return $version;
	}	
	
	public function extractPackage($updatePath,$updateFileName)
	{
		$tmpFolder = JPATH_ROOT.DS.'tmp'.DS.'sfsupdates';
		
		if( ! JFolder::exists($tmpFolder) )
		{
			JFolder::create($tmpFolder);
		}
		
		$tmpFolder = $tmpFolder.DS.$updateFileName;		
		$tmpFolder = JString::str_ireplace('.zip', '', $tmpFolder);		
		
		if( JArchive::extract($updatePath.DS.$updateFileName, $tmpFolder) )
		{
			return $tmpFolder;
		}
		
		return false;
	}
	
	public function updateFiles($tmpFolder)
	{
		// admin
		$adminDest = JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_sfs';		
				
		JFolder::copy($tmpFolder.DS.'admin', $adminDest,'',true, true);
		
		JFile::copy($tmpFolder.DS.'sfs.xml', $adminDest.DS.'sfs.xml');
		JFile::copy($tmpFolder.DS.'script.php', $adminDest.DS.'script.php');
		
		// frontend
		$frontDest = JPATH_ROOT.DS.'components'.DS.'com_sfs';				
		JFolder::copy($tmpFolder.DS.'site', $frontDest,'',true, true);
		
		return true;
	}
	
}




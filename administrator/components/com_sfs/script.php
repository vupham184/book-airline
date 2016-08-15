<?php
defined('_JEXEC') or die();

class com_sfsInstallerScript
{

	function install($parent)
	{

	}

	function uninstall($parent)
	{

	}

	function update($parent)
	{
		echo '<p>Successfully updated to latest version ' . $parent->get('manifest')->version . '</p>';
	}

	function preflight( $type, $parent )
	{
		$jversion = new JVersion();

		$this->release = $parent->get( "manifest" )->version;

		$this->minimum_joomla_release = $parent->get( "manifest" )->attributes()->version;

		echo '<p>Installing component manifest file version = ' . $this->release;
		echo '<br />Current manifest cache commponent version = ' . $this->getParam('version');		
		echo '<br />Current Joomla version = ' . $jversion->getShortVersion();

		if( version_compare( $jversion->getShortVersion(), $this->minimum_joomla_release, 'lt' ) ) 
		{
			Jerror::raiseWarning(null, 'Cannot install SFS component in a Joomla release prior to '.$this->minimum_joomla_release);
			return false;
		}

		if ( $type == 'update' ) 
		{
			$oldRelease = $this->getParam('version');
			$rel = $oldRelease . ' to ' . $this->release;
			
			if ( version_compare( $this->release, $oldRelease, 'le' ) ) 
			{
				Jerror::raiseWarning(null, 'Incorrect version sequence. Cannot upgrade ' . $rel);
				return false;
			}
		}	
	}

	function postflight($type, $parent)
	{
			
	}

	function getParam( $name ) 
	{
		$db = JFactory::getDbo();
		$db->setQuery('SELECT manifest_cache FROM #__extensions WHERE name = "sfs"');
		$manifest = json_decode( $db->loadResult(), true );
		return $manifest[ $name ];
	}

}



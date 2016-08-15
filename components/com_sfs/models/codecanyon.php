<?php
// No direct access
defined('_JEXEC') or die;

/**
 * SFactory class
 *
 */
abstract class SCodeCanyOn
{
		
	public function getCommnetCode($type)
	{print_r("expression"); die();
		
		$db = JFactory::getDbo();
		
		$query = $db->getQuery(true);
		
		$query->select('*');
		$query->from('#__sfs_codecanyon');					
		
		$query->where("type='" . $type . "'");
		
		$db->setQuery($query);
		
		if( $error = $db->getErrorMsg() ) {
			throw new Exception($error);
		}			
		
		$results = $db->loadObject();

		print_r($results->comment); die();

		$html = "<div id='demos'><span id='demo-html' title='" .$results. "'>Hover</span></div>";			
		return  $html;
	}	
  
    
}






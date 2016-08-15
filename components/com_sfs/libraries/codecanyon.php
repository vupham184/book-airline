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
	{
		
		$db = JFactory::getDbo();
		
		$query = $db->getQuery(true);
		
		$query->select('*');
		$query->from('#__sfs_codecanyon');					
		
		$query->where("type='". $type . "'");
		
		$db->setQuery($query);
		
		if( $error = $db->getErrorMsg() ) {
			throw new Exception($error);
		}			
		
		$results = $db->loadObject();

		$img = '<img src="'.JURI::root().'codecanyon/images/'.$results->image.'" width="80" height="80" /><br />';
		$img .= '<div>'.$results->comment.'</div>';

		$data = $results->image . "-" . $results->comment;
		$html = "<div id='demos'><span id='demo-html' >Hover</span></div>";

	    $html .="<script type='text/javascript'>";
	    $html .="jQuery(document).ready(function($) {";
	    $html .="$('#demo-html').tooltipster({";
	    $html .="content: $('$img') ,";
	    $html .="minWidth: 300,maxWidth: 300,position: 'right',";
	    $html .="animation: 'fall',multiple: true,theme: 'tooltipster-shadow',interactive: true";

	    $html .="});";
	    $html .="});";
	    $html .="</script>";


		return $html;
	}	
  
    
}






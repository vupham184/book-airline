<?php
// No direct access
defined('_JEXEC') or die;

class SfsModelStarttotalstayhotelsearches extends JModelLegacy
{
	var $_data = null;
	var $_total = null;
	var $_pagination = null;
	var $_allowSearch = true;

	function __construct()
	{
		parent::__construct();
	}
	
	public function getAirportLocation( ){
		$url = $this->httphost();
		$myUrl = explode("administrator", $url ); 
		$myUrl = $myUrl[0];
		$AirportLocation = file_get_contents( $myUrl . "index.php?option=com_sfs&task=search.getAirportLocation");
		$is_listArr = json_decode($AirportLocation);
		return $is_listArr;
	}
	
	public function httphost(){
		$protocol = isset($_SERVER['HTTPS']) ? 'https' : 'http';
		$server = $_SERVER['HTTP_HOST'];
		$port = $_SERVER['SERVER_PORT'] != 80 ? ":{$_SERVER['SERVER_PORT']}" : '';
		$path = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\') . '/';
		return $weburl = "$protocol://$server$port$path";
	}



}


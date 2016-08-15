<?php
// no direct access
defined('_JEXEC') or die;
jimport('joomla.application.component.controller');

class SfsControllerGetchangeairport extends JController
{	
	
	public function __construct()
	{
		parent::__construct();
	}
	
	public function shoinfo(){
		$ses = session_id();
		$c = file_get_contents(JPATH_ROOT . '/tmp/changeAirport/info.log');
		if( $c != '' ) {
			$js = json_decode( $c );
			echo json_encode( $js->$ses );
			exit;
		}
		echo json_encode( array('id'=>'', 'code' => '') );;
		exit;
	}
	
	public function updateinfo(){
		$ses = session_id();
		$filename = JPATH_ROOT . '/tmp/changeAirport/info.log';
		$response = array($ses=> array('id'=>'', 'code' => '') );
		$fp = fopen($filename, 'w');
		fwrite($fp, json_encode($response));
		fclose($fp);
		exit;
	}
	
}
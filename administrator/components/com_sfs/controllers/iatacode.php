<?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');


class SfsControllerIatacode extends JControllerForm
{

	function __construct($config = array())
	{
		parent::__construct($config);
	}

	protected function allowAdd($data = array())
	{
		return true;
	}

	protected function allowEdit($data = array(), $key = 'id')
	{
		if ($_POST)
			$this->setValueSetup( $data );//lchung
		return true;
	}
	
	
	/*
		*Auth: lchung
		*Save content setup 
	 */
	public function setValueSetup( $vars ){
		$vars['airport'] = $vars['code'];
		
		$p = explode('administrator', dirname(__FILE__) );
		$path = $p[0] . 'media/sfs'; 
		$key_code_airport = '';
		$key_code_airport = strtolower( $vars['airport'] );
		if ( $vars['site_suffix'] == '' ) {
			$vars['site_suffix'] = $key_code_airport;
		}

		$setupairport = trim( $this->getContent(  $path . '/setupairport.txt' ) );
		if ( $setupairport == '' ) {
			$arrayNew = array($key_code_airport => $vars);
			$this->fwriteJson( $path . '/setupairport.txt', json_encode( $arrayNew ) );
		}
		else {
			$content = (array)json_decode( $setupairport );

			$contentOld = array();
			if ( array_key_exists( $key_code_airport, $content) ) {//Huy array old khi trung code airport
				$content[$key_code_airport] = (array)$content[$key_code_airport] = $vars;
			}
			
			foreach ( $content as $vk => $v ) {
				$v = (array)$v;
				if ( $v['site_suffix'] == $vars['site_suffix'] || $v['airport'] == $vars['airport'] && $v['airport'] != '' ) {
					unset( $content[$vk] );
				}
				else 
					$contentOld[$vk] = $v;
			}
			$contentNew = array_merge($contentOld, array( $key_code_airport => $vars ) );
			$this->fwriteJson( $path . '/setupairport.txt', json_encode( $contentNew ) );
			//update lai session
			$setup_airport = (array)JFactory::getSession()->get('setup_airport');
			if ( array_key_exists('airport', $setup_airport ) && $setup_airport['airport'] ==  $vars['airport'] ) {
				$setup_airport['fax_communication'] = $vars['fax_communication'];
				$setup_airport['enabel_https'] = $vars['enabel_https'];
				$setup_airport['enabel_send_sms'] = $vars['enabel_send_sms'];
				$setup_airport['time_zone'] = $vars['time_zone'];
				$setup_airport['site_suffix'] = $vars['site_suffix'];
				$setup_airport['system_smails'] = $vars['system_smails'];
				$setup_airport['enabel_25rule'] = $vars['enabel_25rule'];
				$setup_airport['rule25'] = $vars['rule25'];
				$setup_airport['hours_on_match_page'] = $vars['hours_on_match_page'];
				$setup_airport['airport_current_id'] = $vars['airport_current_id'];
				$session->set('setup_airport', (object)$setup_airport);
			}
		}
	}
	
	/*
		*Auth: lchung
		*file get contents
		*String $file
		*return String 
	 */
	 public function getContent( $file ) {
		 return file_get_contents( $file );
	 }
	
	/*
		*Auth: lchung
		*Write File Json
		*String $filename
		*String $response
	*/
	public function fwriteJson( $filename = '', $response = '' ){
 		$fp = fopen($filename, 'w');
 		fwrite($fp, $response );
 		fclose($fp);
 	}
}
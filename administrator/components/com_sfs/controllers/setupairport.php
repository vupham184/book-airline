<?php
// No direct access
defined('_JEXEC') or die; 

jimport('joomla.application.component.controllerform');


class SfsControllerSetupairport extends JControllerForm
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
		return true;
	}
	
	/*
		*Auth: lchung
		*Save content setup 
	 */
	public function save( ){

		$vars	= JRequest::getVar('jform', array(), 'post', 'array');
		$path = explode('administrator', dirname(__FILE__) )[0] . 'media/sfs'; 
		$key_code_airport = '';
		if ( $vars['airport'] == '0' || $vars['airport'] == '' ) {
			$this->setRedirect('index.php?option=com_sfs&view=setupairport&layout=setup&error=');
			return false;
		}
		else {
			$key_code_airport = strtolower( $vars['airport'] );
		}
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
		$this->setRedirect('index.php?option=com_sfs&view=setupairport&layout=setup&suss=' . $vars['airport'] );	
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
		*Readfile line
		*String $file 
	 */	
	 public function getLinefile( $file ) {
	
		$dataArr = array();
		$handle = fopen( $file , "r");
		if ($handle) {
			while (($line = fgets($handle)) !== false) {
				if ( trim($line) != '' && substr( trim($line), 0, 2 ) != '--' ) {
					$dataArr[] = trim($line);
				}
			}
			fclose($handle);
	
		} else {
			// error opening the file.
		}
	
		return $dataArr;
	
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
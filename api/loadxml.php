<?php
class Api_Loadxml{
	public $pathFile = '';
	
	public function __construct() {
	}
	
	public function LoadXml()
	{
		$xml_content = file_get_contents( $this->pathFile );
		$xml = new SimpleXMLElement($xml_content);
		
		$method = $xml->method;
		$unique = $xml->unique;
		$data = $xml->data->passengers->passenger;
		foreach ( $data as $vk => $v ) {
			echo( $v->first_name ).'<br>';
		}
		//print_r( $data );
	}
}

$load = new Api_Loadxml();
$load->pathFile = "xml/test-1446536162.xml";
$load->LoadXml();
?>

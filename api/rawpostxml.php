<?php
class Api_Rawpostxml{

	protected $_method = 'passenger/push';
	protected $_unique = '';
	
	public function __construct() {
		//$this->_method = '';
	}
	
	private function _headerXML()
	{
		$this->_unique = uniqid();
		
		$str = '<?xml version="1.0" encoding="UTF-8"?>';
		$str .= "\n<request>\n";
		$str .= "\t<method>" . $this->_method . "</method>\n";
		$str .= "\t<unique>" . $this->_unique . "</unique>\n";
		$str .= "\t<data>\n";
		$str .= "\t\t<passengers>\n";
		return $str;
	}
	
	public function dataXML( $data = array() )
	{
		$str = "";
		foreach ( $data as $vk => $v ) {
		$str .="
				<passenger>
				\t<first_name>$v->first_name</first_name>
				\t<last_name>$v->last_name</last_name>
				\t<title>$v->title</title>
				\t<iatacode>$v->iatacode</iatacode>
			\t</passenger>\n";
		}
		return $str ."\n";
	}
	
	private function _endXML()
	{
		$str = "\t\t</passengers>\n";
		$str .= "\t</data>\n";
		$str .= "</request>";
		return $str;
	}
	
	public function rawpostxml( $data = array () )
	{
		$str = self::_headerXML();
		$str .= self::dataXML( $data );
		$str .= self::_endXML();
		return $str;
	}
	
	public function fwriteFile( $filename = '', $response ){
		$fp = fopen($filename, 'w');
		fwrite($fp, $response);
		fclose($fp);
	}
}

$data = new stdClass();
$data->first_name = 'test 1';
$data->last_name = 'test';
$data->title = 'Mr';
$data->iatacode = '123';

$dataArr[] = $data;

$data = new stdClass();
$data->first_name = 'test 2';
$data->last_name = 'test 2';
$data->title = 'Mr';
$data->iatacode = '456';

$dataArr[] = $data;

$data = new stdClass();
$data->first_name = 'test 3';
$data->last_name = 'test 3';
$data->title = 'Mr';
$data->iatacode = '789';

$dataArr[] = $data;

$rawpostxml = new Api_Rawpostxml();
$strXML = $rawpostxml->rawpostxml( $dataArr );
header("Content-type: text/xml");
echo $strXML;
//self::fwriteFile("xml/test-" . time() .".xml", $str);
?>
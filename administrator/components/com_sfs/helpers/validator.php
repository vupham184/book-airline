<?php
// No direct access to this file
defined('_JEXEC') or die;

/*
 * Class for validating input
 * Le Duc Anh
 * leanh@anhld.com
 */
class validator
{
	protected $option    = array();
	protected $message   = array();
	protected $label     = '';
	protected $value     = '';
	protected $error     = array();
	public    $global_error = 0;
	
	/*
	 * Method to construct
	*/
	public function __construct($label='', $value='', $option=array(), $message=array())
	{
		$this->label 	= $label;
		$this->value 	= $value;
		$this->option 	= $this->parse_option($option);
		$this->message 	= $this->parse_message($message);
	}
	
	/*
	 * Method to validate
	*/
	public function validate($label='', &$value='', $option=array(), $message=array())
	{
		$this->label 	= $label;
		$this->value 	= $value;
		$this->option 	= $this->parse_option($option);
		$this->message 	= $this->parse_message($message);
		$error          = array();
		$failed         = array();
		
		#Return true if no rules to validate
		if( empty($this->option) ){
			return true;
		}
		if( (!$this->option['required']) && trim($this->value) == '' ){
			return true;
		}
		
		foreach( $this->option as $key => $val )
		{
			if($val)
			{
				$function_name = 'valid_'.$key;
				
				if( method_exists($this, $function_name) )
				{
					if( !$this->$function_name($this->value, $val) )
					{
						$failed[] = 1;
						$error[]  = isset($this->message[$key]) ? sprintf($this->message[$key], $this->label) : "$this->label is invalid";
					}
				}
				else
				{
					$failed[] = 1;
					$error[]  = "$function_name is not exist";
				}
			}
		}
		
		if( array_sum($failed) > 0 )
		{
			if( (bool)$this->global_error ){
				$this->error = ( !empty($this->error) ? array($this->error, $error) : $error );
			}else{
				$this->error = $error;
			}
			return false;
		}
		return true;
	}
	
	/*
	 * Method to parse options
	 */
	protected function parse_option($option)
	{
		if( empty($option) ) return array();
		
		$out = array();
		
		#Options
		$out['required']   = ( isset($option['required']) && $option['required'] == true ) ? true : false;
		
		###Number
		$out['digit']      = ( isset($option['digit']) && $option['digit'] == true ) ? true : false;
		$out['numeric']    = ( isset($option['numeric']) && $option['numeric'] == true ) ? true : false;
		$out['unsigned']   = ( isset($option['unsigned']) && $option['unsigned'] == true ) ? true : false;
		
		###Letter
		$out['alpha']      		= ( isset($option['alpha']) && $option['alpha'] == true ) ? true : false;
		$out['alpha_digit']     = ( isset($option['alpha_digit']) && $option['alpha_digit'] == true ) ? true : false;
		$out['alpha_digit_underscore']  = ( isset($option['alpha_digit_underscore']) && $option['alpha_digit_underscore'] == true ) ? true : false;
		
		###Date time
		$out['date']         = ( isset($option['date']) && $option['date'] == true ) ? true : false;
		$out['datetime']     = ( isset($option['datetime']) && $option['datetime'] == true ) ? true : false;
		$out['timestr']	 	 = ( isset($option['timestr']) && $option['timestr'] == true ) ? true : false;
		$out['hour_minute']  = ( isset($option['hour_minute']) && $option['hour_minute'] == true ) ? true : false;
		
		###Networks
		$out['url']         = ( isset($option['url']) && $option['url'] == true ) ? true : false;
		$out['http_url']    = ( isset($option['http_url']) && $option['http_url'] == true ) ? true : false;
		$out['ftp_url']     = ( isset($option['ftp_url']) && $option['ftp_url'] == true ) ? true : false;
		$out['email']       = ( isset($option['email']) && $option['email'] == true ) ? true : false;
		$out['ipv4']       = ( isset($option['ipv4']) && $option['ipv4'] == true ) ? true : false;
		$out['ipv6']        = ( isset($option['ipv6']) && $option['ipv6'] == true ) ? true : false;
		
		###Length
		$out['max_length']  = ( isset($option['max_length']) && (float)$option['max_length'] > 0 ) ? (float)$option['max_length'] : false;
		$out['min_length']  = ( isset($option['min_length']) && (float)$option['min_length'] >= 0 ) ? (float)$option['min_length'] : false;
		
		###Value
		$out['max'] = ( isset($option['max']) && (float)$option['max'] > 0 ) ? (float)$option['max'] : false;
		$out['min'] = ( isset($option['min']) && (float)$option['min'] >= 0 ) ? (float)$option['min'] : false;
		
		###Others
		$out['base64']   = ( isset($option['base64']) && $option['base64'] == true ) ? true : false;
		$out['regex']    = isset($option['regex']) ? base64_encode($option['regex']) : false;
		
		return $out;
	}
	
	/*
	 * Method to parse messages
	 */
	protected function parse_message($message)
	{
		$out = array();
		
		###Required
		$out['required']   = isset($message['required']) ? $message['required'] : "%s is required";
		
		###Number
		$out['digit']      = isset($message['digit']) ? $message['digit'] : "%s is not digitals";
		$out['numeric']    = isset($message['numeric']) ? $message['numeric'] : "%s is not numberic";
		$out['unsigned']   = isset($message['unsigned']) ? $message['unsigned'] : " %s is not unsigned numberic ";
		
		###Letter
		$out['alpha']      		= isset($message['alpha']) ? $message['alpha'] : "%s is not alphabet chart";
		$out['alpha_digit']     = isset($message['alpha_digit']) ? $message['alpha_digit'] : "%s is not alphabet & digitals chart";
		$out['alpha_digit_underscore']  = isset($message['alpha_digit_underscore']) ? $message['alpha_digit_underscore'] : " %s is not safe string";
		
		###Date time
		$out['date']         = isset($message['date']) ? $message['date'] : "%s is invalid date format (YYYY-MM-DD) ";
		$out['datetime']     = isset($message['datetime']) ? $message['datetime'] : "%s is invalid date time format (YYYY-MM-DD h:m:s)";
		$out['timestr']	 	 = isset($message['timestr']) ? $message['timestr'] : "%s is not hour-minute-seconds string (h:m:s)";
		$out['hour_minute']  = isset($message['hour_minute']) ? $message['hour_minute'] : "%s is not hour-minute string (h:m)";
		
		###Networks
		$out['url']         = isset($message['url']) ? $message['url'] : "%s is not valid web address";
		$out['http_url']    = isset($message['http_url']) ? $message['http_url'] : "%s is not valid HTTP address";
		$out['ftp_url']     = isset($message['ftp_url']) ? $message['ftp_url'] : "%s is not valid FTP address";
		$out['email']       = isset($message['email']) ? $message['email'] : "%s is not valid email address";
		$out['ipv4']       	= isset($message['ipv4']) ? $message['ipv4'] : "%s is not valid IPv4 address";
		$out['ipv6']        = isset($message['ipv6']) ? $message['ipv6'] : "%s is not valid IPv6 address";
		
		###Length
		$out['max_length']  = isset($message['max_length']) ? $message['max_length'] : "%s is too long";
		$out['min_length']  = isset($message['min_length']) ? $message['min_length'] : "%s is too short";
		
		###Value
		$out['max'] = isset($message['max']) ? $message['max'] : "%s is too big";
		$out['min'] = isset($message['min']) ? $message['min'] : "%s is too small";
		
		###Others
		$out['base64']   = isset($message['base64']) ? $message['base64'] : "%s is not a base64 string";
		$out['regex']   = isset($message['regex']) ? $message['regex'] : "%s is invalid";
		
		return $out;
	}
	
	/*
	 * Method to check string is set or not
	*/
	protected function valid_required(&$str, $val=''){
		if( isset($str) && trim($str) == '' ){
			return false;
		}
		return isset($str);
	}
	
	/*
	 * Method to check string is digitals or not
	*/
	protected function valid_digit($str, $val=''){
		return (bool) preg_match( '/^[0-9]+$/', $str);
	}
	
	/*
	 * Method to check string is numberic or not
	*/
	protected function valid_numeric($str, $val=''){
		return (bool) is_numeric($str);
	}
	
	/*
	 * Method to check string is unsigned number or not
	*/
	protected function valid_unsigned($str, $val=''){
		return (bool) preg_match("/^\+?[0-9]+$/", $str);
	}
	
	/*
	 * Method to check string is alphabet string or not
	*/
	protected function valid_alpha($str, $val=''){
		return (bool) preg_match("/^([a-z])+$/i", $str);
	}
	
	/*
	 * Method to check string is alphabet-numberic string or not
	*/
	protected function valid_alpha_digit($str, $val=''){
		return (bool) preg_match("/^([a-z0-9])+$/i", $str);
	}
	
	/*
	 * Method to check string is alphabet-numberic-underscore string or not
	*/
	protected function valid_alpha_digit_underscore($str, $val=''){
		return (bool) preg_match("/^([a-z0-9_])+$/i", $str);
	}
	
	/*
	 * Method to check string is date ISO or not (YYYY-MM-DD)
	*/
	protected function valid_date($str, $val=''){
		return (bool) preg_match("/^(([1]{1}[9]{1}[9]{1}\d{1})|([2-9]{1}\d{3}))[\/-][0,1]?\d{1}[\/-](([0-2]?\d{1})|([3][0,1]{1}))$/i", $str);
	}
	
	/*
	 * Method to check string is datetime ISO or not (YYYY-MM-DD h:m:s)
	*/
	protected function valid_datetime($str, $val=''){
		return (bool) preg_match("/^(([1]{1}[9]{1}[9]{1}\d{1})|([2-9]{1}\d{3}))[\/-][0,1]?\d{1}[\/-](([0-2]?\d{1})|([3][0,1]{1}))(\s)(([0-1][0-9])|(2[0-3])):([0-5][0-9]):([0-5][0-9])$/i", $str);
	}
	
	/*
	 * Method to check string is time string (h:m:s) or not
	*/
	protected function valid_timestr($str, $val=''){
		return (bool) preg_match("/^(([0-1][0-9])|(2[0-3])):([0-5][0-9]):([0-5][0-9])$/i", $str);
	}
	
	/*
	 * Method to check string is hour & minute string (h:m) or not
	*/
	protected function valid_hour_minute($str, $val=''){
		return (bool) preg_match("/^(([0-1][0-9])|(2[0-3])):([0-5][0-9])$/i", $str);
	}
	
	/*
	 * Method to check string is internet url or not ( HTTP & FTP )
	*/
	protected function valid_url($str, $val=''){
		$regex  =  '^(https?|s?ftp\:\/\/)|(mailto\:)';
		$regex .= '([a-z0-9\+!\*\(\)\,\;\?&=\$_\.\-]+(\:[a-z0-9\+!\*\(\)\,\;\?&=\$_\.\-]+)?@)?';
		$regex .= "[a-z0-9\+\$_\-]+(\.[a-z0-9+\$_\-]+)+";
		$regex .= '(\:[0-9]{2,5})?';
		$regex .= '(\?[a-z\+&\$_\.\-][a-z0-9\;\:@\/&%=\+\$_\.\-]*)?';
		$regex  = '/'.$regex.'/i';
		
		return (bool) preg_match($regex, $str);
	}
	
	/*
	 * Method to check string is valid email address or not ( name@hostname.ext )
	*/
	protected function valid_email($str, $val=''){
		return (bool) preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $str);
	}
	
	/*
	 * Method to check string is IPv4 or not
	*/
	protected function valid_ipv4($str, $val=''){
		return (bool) preg_match("/^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/i", $str);
	}
	
	/*
	 * Method to check string is IPv6 or not
	*/
	protected function valid_ipv6($str, $val=''){
		return (bool) preg_match("/^((([0-9A-Fa-f]{1,4}:){7}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){6}:[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){5}:([0-9A-Fa-f]{1,4}:)?[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){4}:([0-9A-Fa-f]{1,4}:){0,2}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){3}:([0-9A-Fa-f]{1,4}:){0,3}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){2}:([0-9A-Fa-f]{1,4}:){0,4}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){6}((\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b)\.){3}(\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b))|(([0-9A-Fa-f]{1,4}:){0,5}:((\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b)\.){3}(\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b))|(::([0-9A-Fa-f]{1,4}:){0,5}((\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b)\.){3}(\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b))|([0-9A-Fa-f]{1,4}::([0-9A-Fa-f]{1,4}:){0,5}[0-9A-Fa-f]{1,4})|(::([0-9A-Fa-f]{1,4}:){0,6}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){1,7}:))$/i", $str);
	}
	
	/*
	 * Method to check maximum length of string
	*/
	protected function valid_max_length($str, $val=''){
		if( !is_numeric($val) ){
			return FALSE;
		}
		
		return ( strlen($str) <= $val );
	}
	
	/*
	 * Method to check mminium length of string
	*/
	protected function valid_min_length($str, $val=''){
		if( !is_numeric($val) ){
			return FALSE;
		}

		return ( strlen($str) >= $val  );
	}
	
	/*
	 * Method to check maximun value
	*/
	protected function valid_max($str, $val=''){
		if( !is_numeric($val) ){
			return FALSE;
		}
		return ($str <= $val);
	}
	
	/*
	 * Method to check minuum value
	*/
	protected function valid_min($str, $val=''){
		if( !is_numeric($val) ){
			return FALSE;
		}
		return ($str >= $val);
	}
	
	/*
	 * Method to check string is base64 string or not
	*/
	protected function valid_base64($str, $val=''){
		return (bool) ! preg_match('/[^a-zA-Z0-9\/\+=]/', $str);
	}
	
	/*
	 * Method to check with regular expression
	*/
	protected function valid_regex($str, $val=''){
		return (bool) preg_match(base64_decode($val), $str);
	}
	
	/*
	 * Method to get error messages
	 */
	public function get_error(){
		return $this->array_flatten($this->error);
	}
	
	/*
	 * Method to flating an array
	 */
	protected function array_flatten($array, $return=array())
	{
		for($x = 0; $x <= count($array); $x++)
		{
			if( isset($array[$x]) )
			{
				if(is_array($array[$x]))
				{
					$return = $this->array_flatten($array[$x],$return);
				}
				else
				{
					if($array[$x])
					{
						$return[] = $array[$x];
					}
				}
			}
		}
		return $return;
	}
}
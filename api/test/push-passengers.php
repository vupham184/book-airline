<?php
/*echo '<?xml version="1.0" encoding="UTF-8"?>
<request>
	<method>passenger/push</method>
	<api_token>RKZ6Rs9PQjDYwSrWpgcQfZNWpYTiOTi2</api_token>
	<data>
		<passengers>

			<passenger>
				<first_name>John</first_name>
				<last_name>Does</last_name>
				<title>Mr</title>
				<flight_code>VN2269</flight_code>
				<flight_iatacode>123</flight_iatacode>
			</passenger>
			
			<passenger>
				<first_name>John</first_name>
				<last_name>Does</last_name>
				<title>Mr</title>
				<flight_code>VN2269</flight_code>
				<flight_iatacode>123</flight_iatacode>
			</passenger>
			
			<passenger>
				<first_name>John</first_name>
				<last_name>Does</last_name>
				<title>Mr</title>
				<flight_code>VN2269</flight_code>
				<flight_iatacode>123</flight_iatacode>
			</passenger>
			
			<passenger>
				<first_name>John</first_name>
				<last_name>Does</last_name>
				<title>Mr</title>
				<flight_code>VN2269</flight_code>
				<flight_iatacode>123</flight_iatacode>
			</passenger>

		</passengers>
	</data>
</request>';
die;*/


/*
$codes_texts = [
	0  => 'OK',
	1  => 'UNSUPPORTED_PROTOCOL',
	2  => 'FAILED_INIT',
	3  => 'URL_MALFORMAT',
	4  => 'URL_MALFORMAT_USER',
	5  => 'COULDNT_RESOLVE_PROXY',
	6  => 'COULDNT_RESOLVE_HOST',
	7  => 'COULDNT_CONNECT',
	8  => 'FTP_WEIRD_SERVER_REPLY',
	9  => 'FTP_ACCESS_DENIED',
	10 => 'FTP_USER_PASSWORD_INCORRECT', 
	11 => 'FTP_WEIRD_PASS_REPLY', 
	12 => 'FTP_WEIRD_USER_REPLY', 
	13 => 'FTP_WEIRD_PASV_REPLY', 
	14 => 'FTP_WEIRD_227_FORMAT', 
	15 => 'FTP_CANT_GET_HOST', 
	16 => 'FTP_CANT_RECONNECT', 
	17 => 'FTP_COULDNT_SET_BINARY', 
	18 => 'PARTIAL_FILE', 
	19 => 'FTP_COULDNT_RETR_FILE',
	20 => 'FTP_WRITE_ERROR', 
	21 => 'FTP_QUOTE_ERROR', 
	22 => 'HTTP_NOT_FOUND', 
	23 => 'WRITE_ERROR', 
	24 => 'MALFORMAT_USER', 
	25 => 'FTP_COULDNT_STOR_FILE', 
	26 => 'READ_ERROR',
	27 => 'OUT_OF_MEMORY', 
	28 => 'OPERATION_TIMEOUTED', 
	29 => 'FTP_COULDNT_SET_ASCII', 
	30 => 'FTP_PORT_FAILED', 
	31 => 'FTP_COULDNT_USE_REST', 
	32 => 'FTP_COULDNT_GET_SIZE', 
	33 => 'HTTP_RANGE_ERROR', 
	34 => 'HTTP_POST_ERROR', 
	35 => 'SSL_CONNECT_ERROR', 
	36 => 'FTP_BAD_DOWNLOAD_RESUME', 
	37 => 'FILE_COULDNT_READ_FILE', 
	38 => 'LDAP_CANNOT_BIND', 
	39 => 'LDAP_SEARCH_FAILED', 
	40 => 'LIBRARY_NOT_FOUND', 
	41 => 'FUNCTION_NOT_FOUND', 
	42 => 'ABORTED_BY_CALLBACK', 
	43 => 'BAD_FUNCTION_ARGUMENT', 
	44 => 'BAD_CALLING_ORDER', 
	45 => 'HTTP_PORT_FAILED', 
	46 => 'BAD_PASSWORD_ENTERED', 
	47 => 'TOO_MANY_REDIRECTS', 
	48 => 'UNKNOWN_TELNET_OPTION', 
	49 => 'TELNET_OPTION_SYNTAX', 
	50 => 'OBSOLETE', 
	51 => 'SSL_PEER_CERTIFICATE', 
	52 => 'GOT_NOTHING', 
	53 => 'SSL_ENGINE_NOTFOUND', 
	54 => 'SSL_ENGINE_SETFAILED', 
	55 => 'SEND_ERROR', 
	56 => 'RECV_ERROR', 
	57 => 'SHARE_IN_USE', 
	58 => 'SSL_CERTPROBLEM', 
	59 => 'SSL_CIPHER', 
	60 => 'SSL_CACERT', 
	61 => 'BAD_CONTENT_ENCODING', 
	62 => 'LDAP_INVALID_URL', 
	63 => 'FILESIZE_EXCEEDED', 
	64 => 'FTP_SSL_FAILED', 
];
*/
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Test push passengers</title>
</head>
<?php 
$myUrl = explode("api", $_SERVER['PHP_SELF'] ); 
$myUrl = $myUrl[0];
?>
<body>
	<form action="<?php echo $myUrl . 'index.php?option=com_sfs&task=api.loadxml';?>" method="post" enctype="multipart/form-data" >

    <div class="sfs-main-wrapper" style="padding:0 1px 0 1px ; margin-bottom:15px;">
        <div class="sfs-orange-wrapper">
            <div class="sfs-white-wrapper">
                <table cellspacing="0" cellpadding="0" border="0" align="center">
                    <tr valign="top">
                        <td style="vertical-align: top;">
                        	<h1>Upload file xml to Test API push passengers </h1>
                            <div class="frm-left">
                            <input type="file" name="passenger_push"  />
                            </div>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td>
                        <div class="frm-right">
                            <div class="floatbox">
                            <button type="submit" class="btn orange lg">Submit</button>
                            </div>
                        </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    </form>
</body>
</html>
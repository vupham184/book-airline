<?php 
// set gzip handler
if (extension_loaded('zlib') && !ini_get('zlib.output_compression')) @ob_start('ob_gzhandler');
header("Content-type: text/css; charset: UTF-8");
header("Cache-Control: must-revalidate");
$offset = 60 * 60 ;
$ExpStr = "Expires: " .gmdate("D, d M Y H:i:s",time() + $offset) . " GMT";
header($ExpStr);

include_once dirname(__FILE__) . '/style.css';
include_once dirname(__FILE__) . '/validate.css';
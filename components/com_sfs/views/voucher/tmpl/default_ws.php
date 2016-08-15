<?php
defined('_JEXEC') or die();
$document = JFactory::getDocument();
$document->addStylesheet( JURI::base().'components/com_sfs/assets/css/print.css', 'text/css' , 'print' );
$isWS = !empty($this->wsBooking);

$isSeparate = JRequest::getInt('separatevoucher', 0 );

if($isSeparate){
	$template = 'separate';
} 
else 
{
	if( $this->individualVoucher )
	{
		$template = 'individual';			
	} 
	else 
	{
		$template = 'single';
		if($isWS) {
			$template .= '_ws';
		}
	}	
}
//lchung
if ( JRequest::getVar('pvcard') != '' ) {
	$template = $template . '_' . JRequest::getVar('pvcard');
}
//End lchung

echo $this->loadTemplate($template);
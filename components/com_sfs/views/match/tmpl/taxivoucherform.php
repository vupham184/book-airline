<?php
defined('_JEXEC') or die;
$printTaxiUrl = 'index.php?option=com_sfs&view=taxi&layout=voucher&tmpl=component&is_return=0&taxi_id='.JRequest::getInt('taxi_id').'&taxi_voucher_id='.JRequest::getInt('taxi_voucher_id').'&reprint=1';
$printTaxiUrl2 = 'index.php?option=com_sfs&view=taxi&layout=voucher&tmpl=component&is_return=1&taxi_id='.JRequest::getInt('taxi_id').'&taxi_voucher_id='.JRequest::getInt('taxi_voucher_id').'&reprint=1';
?>
<script type="text/javascript">	
<!--
function sfsPopupCenter2(pageURL,title,w,h) {
	var left = (screen.width/2)-(w/2);
	var top = (screen.height/2)-(h/2);
	win = window.open (pageURL, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);	
}
-->	
</script>
<h2>Reprint Taxi Voucher(s)</h2>

<div class="form-group btn-group">
	<a href="javascript:sfsPopupCenter2('<?php echo $printTaxiUrl;?>','Print Taxi Voucher',845,690);" class="btn orange sm">Print airp to hotel taxivoucher</a>	
	<a href="javascript:sfsPopupCenter2('<?php echo $printTaxiUrl2;?>', 'Print Return Taxi Voucher',845,690);" class="btn orange sm">Print hotel to airp taxivoucher</a>	
</div>

<div class="clear"></div>
<a onclick="window.parent.SqueezeBox.close();" class="btn orange xl">Close</a>



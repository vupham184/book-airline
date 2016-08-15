<?php
defined('_JEXEC') or die;
$voucherCode = JRequest::getString('voucher');
if( !empty($voucherCode) ) :
?>
<script type="text/javascript">
<!--
	window.print();
//-->
</script>
<img src="index.php?option=com_sfs&task=ajax.drawvoucher&format=raw&voucher=<?php echo $voucherCode;?>" border="0" />
<?php endif;?>

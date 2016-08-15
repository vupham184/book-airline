<?php
defined('_JEXEC') or die();
?>
<script>
function CloseParent(){//closeVoucherPrintForm
	///window.parent.location.reload( true );
	
	if( self.parent.document.getElementById("closeVoucherPrintForm") == null ){
		self.parent.document.getElementById("closeGroupVoucherForm").click();
	}
	else {
		self.parent.document.getElementById("closeVoucherPrintForm").click();
	}
	window.parent.SqueezeBox.close();
}
</script>
<div id="sfs-print-wrapper" class="fs-14">
<div class="htmlvoucher">
	<div class="heading-buttons">
		<?php
		$rePrint = JRequest::getInt('reprint',0); 
		if( $rePrint == 0 ) :
		?>			
		<!--<a onclick="window.parent.SqueezeBox.close();" class="sfs-button float-right">Close</a>-->
        <a onclick="CloseParent();" class="sfs-button float-right">Close</a>
		<a onclick="window.print();if(document.body.style.MozTransform==undefined){window.parent.SqueezeBox.close();};" class="sfs-button float-right">Print</a>
		<?php else :?>
		<!--<a onclick="self.close();" class="sfs-button float-right">Close</a>-->
        <a onclick="CloseParent();" class="sfs-button float-right">Close</a>
		<a onclick="window.print();self.close()" class="sfs-button float-right">Print</a>
		<?php endif;?>
	</div>
<?php
///$individualVouchers = $this->voucher->getIndividualVouchers();
foreach ($this->individualVouchers as $v)
{
	$this->voucher_room_type = $v->room_type;
	$this->voucher_seats 	 = $v->room_type;
	$this->individual_voucher_code = $v->voucher_code;
	$this->trace_passengers = $v->trace_passengers;
	if( count($v->trace_passengers) )
	{
		$this->voucher_names = implode(', ', $v->trace_passengers );
	} else {
		$this->voucher_names = '';
	}	
	echo $this->loadTemplate('separate_item');
}

?>
</div>
</div>
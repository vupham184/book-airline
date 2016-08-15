<?php 
defined('_JEXEC') or die;
$comment = $this->airline->getVoucherComment();
?>

<div class="sfs-white-wrapper floatbox">
	<fieldset>
		<div class="fieldset-title float-left">
			<span>General voucher comment</span>
		</div>
	
		<div class="fieldset-fields float-left" style="width: 584px; padding-top: 35px;">
			<textarea name="vouchercomment" style="width: 400px; height:100px"><?php if($comment) echo $comment; ?></textarea>		
		</div>
	
	</fieldset>
</div>

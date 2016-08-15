<?php
defined('_JEXEC') or die;
?>
<div class="correspondence">
<div class="sfs-above-main">
	<h3>Correspondence About Block Code</h3>
</div>

<div class="sfs-main-wrapper" style="padding:0px 1px 1px 1px;">
    <div class="sfs-white-wrapper floatbox">       
        <?php 
        $prev = null; 
        $floatd = 0;            
        foreach ( $this->messages as $message ): 
			if(isset($prev) && $prev != $message->type ){
				$floatd = 1 - $floatd;
			}
			?>
			<div class="message-block <?php echo ($floatd==1)? 'float-right':'float-left';?>">
				<p class="sendby">
					Sent by <?php echo $message->type==1 ? 'you' : $message->from_name  ;?> on: <span class="datesend"><?php echo JHTML::_('date', $message->posted_date ,JText::_('DATE_FORMAT_LC2') );?></span>
				</p>
				<div class="message-block-body">
                    <div class="message-subject">RE: challenge for block code <?php echo $this->reservation->blockcode;?></div>
                    <?php echo $message->body; ?>                    
				</div>
			</div>
			<div class="clear"></div>
			<?php
			$prev =  $message->type;
        endforeach;
        ?>   
       
    </div>
</div>

<div class="sfs-below-main" style="padding:10px 20px 10px 10px;">
	<a href="<?php echo JRoute::_('index.php?option=com_sfs&view=message&tmpl=component&bookingid='.$this->reservation->id)?>" rel="{handler: 'iframe', size: {x: 675, y: 500}}" class="modal small-button float-right">Send Message</a>

</div>

</div>
<?php
defined('_JEXEC') or die;
JHTML::_('behavior.modal');
?>
<div class="heading-block clearfix">
    <div class="heading-block-wrap">
        <h3><?php echo $this->hotel->name?>: Details name loading</h3>
    </div>
</div>
<div class="main">
    <div class="form-group clearfix">
        <?php
        $printUrl = 'index.php?option=com_sfs&view=block&layout=print&blockid='.$this->reservation->id;
        if( $this->state->get('block.airport') )
        {
            $printUrl .= '&airport='.$this->state->get('block.airport');
        }
        $printUrl .= '&tmpl=component&print=1';
        ?>
        
        <a href="<?php echo $printUrl?>" class="btn orange sm pull-right modal" rel="{handler: 'iframe', size: {x: 860, y: 600}}" data-step="1" data-intro="<?php echo SfsHelper::getTooltipTextEsc('button_print', $text, 'hotel'); ?>">Print</a>
    </div>
	
    <div class="sfs-white-wrapper orange-top-border">
    
        <div class="ec-left float-left"> 
        	<div class="floatbox pd">       
	            <?php echo $this->loadTemplate('airline');?>            
                <?php echo $this->loadTemplate('rooms');?>
            </div>
        </div>
        
        <div class="ec-right float-right">
        	<div class="floatbox pd">       
                <?php echo $this->loadTemplate('estimate');?>        	         
            </div>
        </div>
    </div>    
        
    <?php 		
		echo $this->loadTemplate('vouchers');		
	?>
	<?php
	$sendMsgLink = 'index.php?option=com_sfs&view=message&tmpl=component&mlayout=tentative&bookingid='.$this->reservation->id.'&airport='.JRequest::getVar('airport'); 
	?>
	<div class="form-group btn-group text-right">
		<?php if($this->reservation->status=='T'):?>		
			<a href="<?php echo JRoute::_('index.php?option=com_sfs&view=block&Itemid='.JRequest::getInt('Itemid'));?>" class="btn orange lg">Close</a>		
		<?php else :?>		
			<a href="<?php echo JRoute::_('index.php?option=com_sfs&view=rooming&code='.$this->reservation->blockcode.'&Itemid='.JRequest::getInt('Itemid'));?>" class="btn orange lg pull-left">Edit</a>		
		<?php endif;?>

			<a href="<?php echo JRoute::_($sendMsgLink)?>" rel="{handler: 'iframe', size: {x: 675, y: 500}}" class="btn orange lg modal" data-step="8" data-intro="<?php echo SfsHelper::getTooltipTextEsc('button_send_message', $text, 'hotel'); ?>">Send Message</a>		
	</div>

	<div class="clear"></div>
    
	<span class="width100 float-right"  style="display:none;text-align:right" id="sfstime"><?php echo date("d F Y, H:i:s",time()); ?></span>    
    
    <?php if ( count($this->messages) ) :?>
        <div class="correspondence">
        <div class="sfs-above-main">
            <h3>Correspondence About Block Code</h3>
        </div>
        
        <div class="sfs-main-wrapper">
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
                        Sent by <?php echo $message->type==2 ? 'you' : $message->from_name  ;?> on: <span class="datesend"><?php echo JHTML::_('date', $message->posted_date ,JText::_('DATE_FORMAT_LC2') );?></span>
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
        
        <a href="<?php echo JRoute::_($sendMsgLink)?>" rel="{handler: 'iframe', size: {x: 675, y: 500}}" class="btn orange lg modal">Send Message</a>

        </div>
    <?php endif;?>
</div>
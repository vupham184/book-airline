<?php
defined('_JEXEC') or die;
JHTML::_('behavior.modal');

?>
<script type="text/javascript">
window.addEvent('domready', function(){
	var messageForm = document.id('roomingForm');
	// Validation.
	new Form.Validator(messageForm);
});
</script>
<div class="heading-block descript clearfix">
    <div class="heading-block-wrap">
        <h3><?php echo JText::_('COM_SFS_BLOCK_DETAILED_SEARCH_CRITERIA');?></h3>
        <div class="descript-txt"><?php echo JText::_('COM_SFS_BLOCK_NOTE');?></div>
    </div>
</div>

<div id="sfs-wrapper" class="main">


	<form name="roomingForm" id="roomingForm" action="<?php echo JRoute::_('index.php?option=com_sfs');?>" method="post">

        <div class="sfs-above-main">
		    <h2>Roomblock details</h2>
	    </div>

        <div class="sfs-main-wrapper bottom-border-radius" style="padding:0 1px 10px 1px;">
        <div class="sfs-yellow-wrapper orange-top-border">

	        <div class="sfs-white-wrapper floatbox" style="margin-bottom:30px;">
	        	<div style="font-size:16px;">
	        	<p>Airline: <?php echo $this->airline->code.', '.$this->airline->name;?></p>
	        	<p>
	        		Roomblock code: <?php echo isset($this->block_code) ? $this->block_code->blockcode : JRequest::getVar('code');?>
	        	</p>
	        	<p>
	        		Status: <span style="color:red;">Challenged</span>
	        	</p>
	        	</div>
	        </div>

	        <div class="sfs-white-wrapper floatbox">
	        	<div style="font-size:16px;">Below is an overview of the inserted voucher numbers corresponding with the roomblock</div>
	            <div style="padding:20px;">

	   	        	<div style="font-size:15px;">

	                    <div style="padding:0 100px 0 100px;">
							<table cellpadding="0" cellspacing="0" width="100%" class="roomingtable">
	                        	<tr>
	                            	<th>#</th><th>First name</th><th>Last name</th><th>Voucher number</th>
	                            </tr>
	                            <?php
	                            $i = 0;
	                            if(count($this->items)) :
		                            foreach ($this->items as $item) :
		                            ?>
			                          	<tr class="<?php echo ($i%2) ? 'odd':'even';?>">
			                            	<td>
			                            		<?php echo $i+1;?>
			                            	</td>
			                                <td><?php echo $item->first_name;?></td>
			                                <td><?php echo $item->last_name;?></td>
			                                <td><?php echo $item->voucher_number;?></td>
			                            </tr>
			                            <?php
			                            $i++;
		                            endforeach;
	                            endif;?>
	                        </table>
	                        <br />
	                        <div>
	                        	<?php
	                        	$editLink = 'index.php?option=com_sfs&view=rooming&code='; 
	                        	if($this->block_code)
	                        	{
	                        		$editLink .= $this->block_code->blockcode;
	                        	} else {
	                        		$editLink .= JRequest::getVar('code');
	                        	}
	                        	
	                        	$editLink .= '&airport='.$this->state->get('rooming.airport');
	                        	$editLink .= '&Itemid='.JRequest::getVar('code');
	                        	?>
	                        	<a href="<?php echo $editLink;?>" class="small-button">Edit</a>
	                        </div>

	                    </div>

	                </div>
	            </div>
	        </div>



        </div>
        </div>


    	<div class="correspondence">

            <div class="sfs-above-main" style="margin-top:15px;">
                <h2>Correspondence about block code</h2>
            </div>

       		<div class="sfs-main-wrapper" style="padding:0px 1px 1px 1px;">
            <div class="sfs-white-wrapper floatbox">

                <?php if ( count($this->messages) ) :?>
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
                <?php endif;?>

            </div>
            </div>
            <div class="sfs-below-main" style="padding:10px 20px 10px 10px !important;">
            	<?php $airport = JRequest::getVar('airport','')?>
                <a href="<?php echo JRoute::_('index.php?option=com_sfs&view=message&tmpl=component&mlayout=challenge&bookingid='.$this->block_code->id.'&airport='.$airport)?>" rel="{handler: 'iframe', size: {x: 675, y: 500}}" class="modal small-button float-left">
                    Send Message
                </a>
                <a href="<?php echo JRoute::_( 'index.php?option=com_sfs&view=block&Itemid='.JRequest::getInt('Itemid') );?>" class="small-button float-right">Close</a>
       		</div>
        </div>






        <input type="hidden" name="task" value="challenge.send" />
        <input type="hidden" name="block_id" value="<?php echo $this->block_code->id;?>" />
        <input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid');?>" />
        <?php echo JHtml::_('form.token'); ?>

    </form>



</div>



<?php
defined('_JEXEC') or die;
JHtml::_('behavior.mootools');
?>

<script type="text/javascript">
window.addEvent('domready', function(){	
	var messageForm = document.id('messageForm');
	// Validation.
	new Form.Validator(messageForm);
});
</script>

<form id="messageForm" action="<?php echo JRoute::_('index.php?option=com_sfs'); ?>" method="post">

	<div style="width:100px; float:right; text-align:right" class="fs-14">
		<a onclick="window.parent.SqueezeBox.close();" style="cursor:pointer;text-decoration:underline">Close</a>
	</div>
	
	<div id="sfs-wrapper" class="fs-14">
		<h3 class="fs-16">
			<?php
			if(isset($this->hotel)) {
				echo JText::sprintf('COM_SFS_MESSAGE_TITLE',$this->hotel->name);
			} else if ($this->airline) {				
				echo JText::sprintf('COM_SFS_MESSAGE_TITLE',(string)$this->airline->name );
			}	 
			?>	
		</h3>
		
		<p>
		<?php
			if($this->message_count) {
				echo JText::sprintf('COM_SFS_MESSAGE_RE_SUBJECT',$this->reservation->blockcode);			
			} else {
				echo JText::sprintf('COM_SFS_MESSAGE_SUBJECT',$this->reservation->blockcode);			
			}
			
		?>
		</p>
		
		<p>
		<?php
			$deartext = '';
			if( isset($this->contact) ) {
				$deartext =  'Dear '.$this->contact->gender.' '.$this->contact->name.',';
				echo $deartext;		
			} 
		?>
		</p>
				
		<textarea name="message" class="required" style="width:600px;height:180px;border: solid 1px #ccc; padding:5px;"></textarea>
    
	    <p>         
	    	 Please find a link to the concerning block code here                          
	    </p>
	    
	    <p>
	    	With best regards,
	    </p>
	    <p>
	    	<?php $user = JFactory::getUser(); echo $user->name;?>
	    </p>
	    
	    <p>    	
	    	<input type="submit" class="small-button" name="send" value="Send">    		
	    </p>
	    
	    <input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid');?>">  	    
	    <input type="hidden" name="bookingid" value="<?php echo $this->reservation->id;?>" />
	    <input type="hidden" name="airport" value="<?php echo $this->state->get('filter.airport');?>" />	                      
	    <input type="hidden" name="task" value="message.send" />
	    <input type="hidden" name="deartext" value="<?php echo $deartext;?>" />
	    <input type="hidden" name="mlayout" value="<?php echo JRequest::getVar('mlayout');?>" />
	    
	    
	    <?php echo JHtml::_('form.token'); ?>        
	</div>
</form>
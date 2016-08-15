<?php
defined('_JEXEC') or die;
JHtml::_('behavior.mootools');
?>
<script type="text/javascript">
window.addEvent('domready', function(){	
	var challengeForm = document.id('challengeForm');
	// Validation.
	new Form.Validator(challengeForm);
});
</script>

<div id="sfs-wrapper" class="main fs-14">
<form id="challengeForm" action="<?php echo JRoute::_('index.php?option=com_sfs'); ?>" method="post">

	<h3 class="fs-16">Message for accounting department of <?php echo $this->hotel->name;?></h3>
	
	<p>RE: challenge for block code: <?php echo $this->reservation->blockcode;?></p>
	
	<p>Dear <?php echo $this->contact->gender.' '.$this->contact->name?>,</p>

	
	<textarea name="message" class="required" style="width:600px;height:180px;"></textarea>
    
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
    
    <input type="hidden" name="block_id" value="<?php echo $this->reservation->id;?>" />
    <input type="hidden" name="challenge_type" value="1" />                    
    <input type="hidden" name="task" value="challenge.send" />
    
    <?php echo JHtml::_('form.token'); ?>        
                
</form>

</div>
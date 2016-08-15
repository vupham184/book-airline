<?php
defined('_JEXEC') or die;

// Load the tooltip behavior.
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
$editor =& JFactory::getEditor();
?>

<script type="text/javascript">
	Joomla.submitbutton = function(task) {		
		<?php 
			echo $editor->save('notes'); 
		?>
		Joomla.submitform(task, document.getElementById('note-form'));
	}
	removenote = function(noteid) {
		document.getElementById('note-form').noteid.value=noteid;
		Joomla.submitform('reservation.rnote', document.getElementById('note-form'));		
	}
</script>
<fieldset class="reservationNoteWrap">
		<div class="fltrt">
			<button onclick="window.parent.SqueezeBox.close();" type="button">Close</button>
		</div>
		
		<h3 style="margin:0;padding:5px 0 0 40px;color:#2c60c0;">Notes for Block: <?php echo $this->reservation->blockcode;?></h3>
		
</fieldset>


<form action="<?php echo JRoute::_('index.php?option=com_sfs'); ?>" method="post" name="adminForm" id="note-form">

	<?php if( count($this->notes) ):?>
	
		<?php foreach ($this->notes as $note): ?>
			<div class="reservation-note">
				<div class="note-text">
					<?php echo $note->notes;?>
				</div>				
				<div class="note-author">
					<?php echo $note->created;?> by <?php echo $note->author;?>
					<button type="button" onClick="removenote(<?php echo $note->id;?>)" class="button" style="float:right">Remove</button> 
				</div>				
			</div>
		<?php endforeach;?>
	
	<?php endif;?>

	<?php 	
		echo $editor->display('notes', null, '100%', '200px', '5', '30', false);
	?>
	
			 
	<div style="float:right">
		<button type="button" onClick="Joomla.submitbutton('reservation.anote')" class="button">Add Note</button>	 
	</div>
	
	
	<input type="hidden" name="reservation_id" value="<?php echo $this->reservation->id?>" />
	<input type="hidden" name="tmpl" value="component" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="noteid" value="" />					
	<?php echo JHtml::_('form.token'); ?>
</form>


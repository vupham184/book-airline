<?php
defined('_JEXEC') or die;
$read_only='';
?>
<script type="text/javascript">
window.addEvent('domready', function(){
	var uploadCsvForm = document.id('uploadCsvForm');
	// Validation.
	new Form.Validator(uploadCsvForm);	
});
</script>

<script type="text/javascript" language="javascript">
function checkfile(sender) {
    var validExts = new Array(".xlsx", ".xls", ".csv");
    var fileExt = sender.value;
    fileExt = fileExt.substring(fileExt.lastIndexOf('.'));
    if (validExts.indexOf(fileExt) < 0) {
      alert("Invalid file selected, valid files are of " +
               validExts.toString() + " types.");
      return false;
    }
    else return true;
}
</script>

<div class="clear floatbox">	
<form id="uploadCsvForm" action="<?php echo JRoute::_('index.php?option=com_sfs&view=rooming');?>" method="post" enctype="multipart/form-data">	
	<div style="padding:30px;">
	
	    <div class="fs-14"><?php echo JText::_('COM_SFS_ROOMING_UPLOAD_YOUR_ROOMING_LIST')?></div>
	    <div class="midpaddingleft fs-12"><?php echo JText::_('COM_SFS_ROOMING_UPLOAD_YOUR_ROOMING_LIST_NOTE')?></div>
	    <div class="verybigpaddingleft midpaddingtop fs-12">
	    	<?php echo JText::_('COM_SFS_ROOMING_CSV_EXAMPLE')?>
	    </div>
	    
	    <div class="hugepaddingtop floatbox">
	    	<div class="float-left choose-delimiter">
			    <div class="fs-14"><?php echo JText::_('COM_SFS_ROOMING_CHOOSE_DELIMITER')?></div>
			    <div class="midpaddingleft fs-12"><?php echo JText::_('COM_SFS_ROOMING_CHOOSE_DELIMITER_NOTE')?></div>
		    </div>
		    <div class="float-left choose-delimiter-field">
		    	<select name="csvtype" id="csvtype">
		    		<option value="2">,</option>
		    		<option value="1">;</option>		    		
		    		<option value="3">|</option>
		    	</select>
		    	<button type="button" class="hasTip" title="<?php echo JText::_('COM_SFS_ROOMING_CSVTYPE_TIP')?>">?</button>
		    </div>
	    </div>
	    
	    <div class="upload-blockcode hugepaddingtop fs-14">	 
	    	<div class="upload-blockcode-field">       
		        <?php echo JText::_('COM_SFS_BLOCK_CODE')?> : <input type="text" name="blockcode" <?php echo $read_only;?> value="<?php echo isset($this->block_code) ? $this->block_code->blockcode : JRequest::getVar('code');?>" class="inputbox required" />
		        <div class="midpaddingleft fs-12"><?php echo JText::_('COM_SFS_ROOMING_BLOCKCODE_NOTE')?></div>
	        </div>
	        <div>	
	        	<table border="0">
	        		<tr valign="middle">
	        			<td>
	        				<input type="file" name="csvfile" onchange="checkfile(this);" class="button required" style="background:#FFFFFF; border:solid 1px #999999;" />
	        			</td>
	        			<td>
	        				<button class="small-button" type="submit" name="upload" value="Upload" style="margin: 0 0 0 10px;"><?php echo JText::_('COM_SFS_UPLOAD');?></button>
	        			</td>
	        		</tr>
	        	</table>        	        	
	        </div>
	    </div>
	    
	</div>
	<input type="hidden" name="task" value="rooming.csvupload" />
	<input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid');?>" />        
	<?php echo JHtml::_('form.token'); ?>
</form>
</div>
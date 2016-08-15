<?php
// No direct access. 
defined('_JEXEC') or die;

// Load the tooltip behavior.
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');

$value = "";
$name = 'jform[airport]';
$db = JFactory::getDbo();			
$db->setQuery('SELECT id, code AS value, name AS text FROM #__sfs_iatacodes WHERE type=2 ORDER BY code ASC');

$rows = $db->loadObjectList();
/*
$airlinelist[]	= JHTML::_('select.option',  '0', JText::_( 'Select Airline Code' ), 'value', 'text' );
$airlinelist	= array_merge( $airlinelist, $rows);		
$html = JHTML::_('select.genericlist', $airlinelist, $name, $attribs, 'value', 'text', $value );
*/
$html = '<select id="idairport" onchange="getdataId(this);" name="' . $name . '" class="sel-airport"><option value="0">Select airport</option>';
foreach( $rows as $vk => $v ){
	$html .= '<option data-id="'. $v->id .'" value="' . $v->value . '">' . $v->text . '</option>';
}
$html .= '</select>';
?>

<script type="text/javascript">
	Joomla.submitbutton = function(task) {
		if (task == 'setupairport.cancel' ) {			
			document.location.reload(true);
		} else if (task == 'setupairport.save' ) {	
			Joomla.submitform(task, document.getElementById('item-form'));
		}
		else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}
	
	var getdataId = function( obj ){
		var id = obj.options[obj.selectedIndex].getAttribute('data-id');
		document.getElementById('airport_current_id').value = id;
	};
</script>
<style>
.adminformlist li{
	padding:7px 10px;
}
</style>
<form action="<?php echo JRoute::_('index.php?option=com_sfs&view=setupairport&layout=setup&id=0'); ?>" method="post" name="adminForm" id="item-form" class="form-validate">
	<div class="width-100">
    	<?php $app = &JFactory::getApplication(); 
		if ( isset( $_GET['error'] ) ) {			
			$app->enqueueMessage("Please choose Airport!","error");
		}
		elseif ( isset( $_GET['suss'] )) {
			$app = &JFactory::getApplication(); 
			$app->enqueueMessage("Setup (" . $_GET['suss'] . ") successfully!"); 
		}
		?>
		<fieldset class="adminform">
			<legend><?php echo JText::_('Setup'); ?></legend>
			<ul class="adminformlist">
            
            	<li><?php echo $this->form->getLabel('mail_communication'); ?>
				<?php echo $this->form->getInput('mail_communication'); ?></li>
                
				<li><?php echo $this->form->getLabel('fax_communication'); ?>
				<?php echo $this->form->getInput('fax_communication'); ?></li>

				<li><?php echo $this->form->getLabel('enabel_https'); ?>
				<?php echo $this->form->getInput('enabel_https'); ?></li>
                
                <li><?php echo $this->form->getLabel('enabel_send_sms'); ?>
				<?php echo $this->form->getInput('enabel_send_sms'); ?></li>
                
				<li><label>Time zone</label>
                    <?php echo SfsHelperField::getTimeZone($this->item->time_zone, 'jform[time_zone]');?>				
                </li>
                
                <li><?php echo $this->form->getLabel('site_suffix'); ?>
				<?php echo $this->form->getInput('site_suffix'); ?></li>
                
                <li>
                    <label>Airport</label>	
                    <?php echo $html;?>				
                </li>
                
                <li><?php echo $this->form->getLabel('sfs_system_currency'); ?>
				<?php echo $this->form->getInput('sfs_system_currency'); ?></li>
                
				<li><?php echo $this->form->getLabel('system_smails'); ?>
				<?php echo $this->form->getInput('system_smails'); ?></li>

				<li><?php echo $this->form->getLabel('enabel_25rule'); ?>
				<?php echo $this->form->getInput('enabel_25rule'); ?></li>

                <li><?php echo $this->form->getLabel('rule25'); ?>
                    <?php echo $this->form->getInput('rule25'); ?></li>

                <li><?php echo $this->form->getLabel('hours_on_match_page'); ?>
                    <?php echo $this->form->getInput('hours_on_match_page'); ?></li>
							
			</ul>			
		</fieldset>
	</div>
   
	<div class="clr"></div>
	<div>
		<input type="hidden" name="task" value="" />
        <input id="airport_current_id" type="hidden" name="jform[airport_current_id]" value="" />
        
		<input type="hidden" name="return" value="<?php echo JRequest::getCmd('return');?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>


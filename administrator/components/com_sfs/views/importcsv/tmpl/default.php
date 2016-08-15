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
$db->setQuery('SELECT code AS value, name AS text FROM #__sfs_iatacodes WHERE type=2 ORDER BY code ASC');

$rows = $db->loadObjectList();

$airlinelist[]	= JHTML::_('select.option',  '0', JText::_( 'Select Airline Code' ), 'value', 'text' );
$airlinelist	= array_merge( $airlinelist, $rows);		

$html = JHTML::_('select.genericlist', $airlinelist, $name, $attribs, 'value', 'text', $value );

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
</script>
<style>
.adminformlist li{
	padding:7px 10px;
}
</style>
<div style="margin:20px 30px; padding:20px;">
    <form action="<?php echo JRoute::_('index.php?option=com_sfs&view=importcsv'); ?>" method="post" name="adminForm" id="item-form" class="form-validate" enctype="multipart/form-data">
        <div class="width-100">
            <?php $app = &JFactory::getApplication(); 
            if ( isset( $_GET['error'] ) ) {
                
                $app->enqueueMessage("ERROR MOVING FILE!","error");
            }
            elseif ( isset( $_GET['suss'] )) {
                $app = &JFactory::getApplication(); 
                //print_r( $app );
                echo $app->enqueueMessage("Import passengers airplus data successfully!"); 
            }
            ?>
            
       <fieldset class="adminform">
        <legend>Import passengers airplus data </legend>
        <ul class="adminformlist">
        
            <li><label>Path File</label>
            <input name="path_file" type="file" />
            </li>
            
            <li>
             <input type="submit" value="Import CSV" />
            </li>

           
        </ul>			
    </fieldset>
       </div>
        <div class="clr"></div>
        <div>
           
            <input type="hidden" name="task" value="importcsv.import" />
            <input type="hidden" name="return" value="<?php echo JRequest::getCmd('return');?>" />
            <?php echo JHtml::_('form.token'); ?>
        </div>
    </form>
</div>

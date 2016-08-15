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
        Joomla.submitform(task, document.getElementById('item-form'));		
	}
</script>
<style>
.adminformlist li{
	padding:7px 10px;
}
</style>
<div style="margin:20px 30px; padding:20px;">
    <?php $app = &JFactory::getApplication(); 
        if ( isset( $_GET['error'] ) ) {
            
            $app->enqueueMessage("ERROR MOVING FILE!","error");
        }
        elseif ( isset( $_GET['suss'] )) {
            $app = &JFactory::getApplication(); 
            //print_r( $app );
            echo $app->enqueueMessage("You are create successfully!"); 
        }
    ?>
    <form action="" method="post" name="adminForm" id="item-form"  enctype="multipart/form-data">       
            
    <fieldset class="adminform">
        <legend>Codecanyon</legend>
        <ul class="adminformlist">
        
            <li><label>Image File</label>
                <input name="path_file" type="file" />
            </li>
           <!--  <li><label>Type</label>
                <input type="text" name="type" />
            </li> -->
            <li><label>Comment</label>
                <textarea name="textcomment" id="jform_articletext" cols="0" rows="0" style="width: 95%; height:100px;" class="mce_editable"></textarea>
            </li>            
        </ul>	        
    </fieldset>
       </div>
        <div class="clr"></div>
        <div>           
            <input type="hidden" name="task" value="codecanyon.save" />
            <input type="hidden" name="return" value="<?php echo JRequest::getCmd('return');?>" />
            <?php echo JHtml::_('form.token'); ?>
        </div>
    </form>

</div>

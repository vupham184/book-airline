<?php
// No direct access.
defined('_JEXEC') or die;

// Load the behavior.
JHtml::_('behavior.tooltip');
JHtml::_('behavior.keepalive');
JHtml::_('behavior.modal');

$month_array = array(
	1 => JText::_('JANUARY'),
	2 => JText::_('FEBRUARY'),
	3 => JText::_('MARCH'),
	4 => JText::_('APRIL'),
	5 => JText::_('MAY'),
	6 => JText::_('JUNE'),
	7 => JText::_('JULY'),
	8 => JText::_('AUGUST'),
	9 => JText::_('SEPTEMBER'),
	10 => JText::_('OCTOBER'),
	11 => JText::_('NOVEMBER'),
	12 => JText::_('DECEMBER')
);

$date_from	= JRequest::getVar('date_from');
$date_to 	= JRequest::getVar('date_to');

?>

<script type="text/javascript">

window.addEvent('domready', function(){
	var myForm = document.id('airlineReportForm'),
		myResult = document.id('airlineReportResult');

	// Validation.
	new Form.Validator.Inline(myForm);

	new Form.Request(myForm, myResult, {
		requestOptions: {
			'spinnerTarget': myForm
		},		
		resetForm : false
	});
});

</script>

<div class="width-100" style="margin-top:15px;">
	<form enctype="multipart/form-data" action="" method="post">  		
		<fieldset class="adminform">
        <legend>Import passengers airplus data </legend>
        <ul class="adminformlist">
        
            <li><label>Path File</label>
            <input name="fileName" type="file" />
            </li>
            
            <li>
             <input type="submit" value="Import CSV" />
             <input type="hidden" name="task" value="airplusreporting.importAirplus"/>	
			 <input type="hidden" name="option" value="com_sfs"/>		
			 <input type="hidden" name="format" value="raw"/>
            </li>

           
        </ul>			
    </fieldset>			
	</form>
	
</div>
<div class="clr"></div>

<div class="width-100" id="airlineReportResult">
	
	<?php $app = &JFactory::getApplication(); 
        if ( isset( $_GET['error'] ) ) {                
            $app->enqueueMessage("ERROR MOVING FILE!","error");
        }
        elseif ( isset( $_GET['suss'] )) {
            $app = &JFactory::getApplication();               
            echo $app->enqueueMessage("Import passengers airplus data successfully!"); 
        }
    ?>
</div>

<div class="clr"></div>


<?php
// No direct access
defined('_JEXEC') or die('Restricted access');

JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');

$params = $this->form->getFieldsets('params');


$db = JFactory::getDbo();

$query = 'SELECT a.id, b.name'
	. ' FROM  #__sfs_airline_details AS a'
	. ' INNER JOIN #__sfs_iatacodes AS b ON b.id = a.iatacode_id'
	. ' WHERE b.type = 1';

$db->setQuery($query);
$result = $db->loadObjectList();

?>

<style type="text/css">
	textarea{
		width: 90%;
		height: 200px;
	}
</style>
<form action="<?php echo JRoute::_('index.php?option=com_sfs&layout=edit&id='.(int) $this->item->id); ?>" 
     enctype="multipart/form-data" method="post" name="adminForm" id="exchangerate-form" class="form-validate">
	<div class="width-50 fltlft">
		<fieldset class="adminform">
			<legend><?php echo JText::_( 'COM_SFS_DETAILS_DESKTOP' ); ?></legend>
			
			<ul class="adminformlist">				
				<?php 
					foreach($this->form->getFieldset('details') as $field)
					{					
						if( ($field->name != 'jform[rules]') &&  ( ($field->name != 'jform[state]') || ($field->name == 'jform[state]' && $canDo->get('core.edit.state') == 1) ) )
						{
							echo '<li>';
							echo $field->label.$field->input;
							echo '</li>';
						}
					}
				?>						
				
			</ul>
			<label id="jform_color-lbl">Name Airline *</label><br />
			<select name="jform[name_airline]">
				<?php
					foreach ($result as $value) {
						if($value->id == $this->item->name_airline){
							echo "<option value='".$value->id."' selected>". $value->name . "</option>";
						}else{
							echo "<option value='".$value->id."'>". $value->name . "</option>";
						}						
					}
				?>
			</select>

		</fieldset>
	</div>

	<div class="width-50 fltlft">
		<fieldset class="adminform">
			<legend><?php echo JText::_( 'COM_SFS_DETAILS_MOBILE' ); ?></legend>
			
			<ul class="adminformlist">				
				<?php 
					foreach($this->form->getFieldset('detailsMobile') as $field)
					{					
						if( ($field->name != 'jform[rules]') &&  ( ($field->name != 'jform[state]') || ($field->name == 'jform[state]' && $canDo->get('core.edit.state') == 1) ) )
						{
							echo '<li>';
							echo $field->label.$field->input;
							echo '</li>';
						}
					}
				?>
				
			</ul>			

		</fieldset>
	</div>

	<div class="clr"></div>
	<div class="logo">
		<input type="hidden" value="<?php echo $this->item->logo_airline_desktop ?>" name="jform[logo_airline_desktop]">
	</div>
	<div class="header">
		<input type="hidden" value="<?php echo $this->item->header_airline_desktop ?>" name="jform[header_airline_desktop]">
	</div>
	<div class="header_mobile">
		<input type="hidden" value="<?php echo $this->item->logo_header_mobile ?>" name="jform[logo_header_mobile]">
	</div>
	<div class="voucher_mobile">
		<input type="hidden" value="<?php echo $this->item->logo_voucher_mobile ?>" name="jform[logo_voucher_mobile]">
	</div>
	<div class="creditcard_mobile">
		<input type="hidden" value="<?php echo $this->item->logo_creditcard_mobile ?>" name="jform[logo_creditcard_mobile]">
	</div>
	
	<div>
		<input type="hidden" name="task" value="managetemplate.edit" />
		<input type="hidden" name="jform[created]" value="<?php echo date("Y-m-d");  ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>


<script type="text/javascript">
	function listTemplateFun(){		
		var valSelect = jQuery("#listTemplate option:selected").text();
		if(valSelect == "Mobile"){
			jQuery('.templateMobile').css('display','block');
			jQuery('.templateBrowser').css('display','none');
		}else{
			jQuery('.templateMobile').css('display','none');
			jQuery('.templateBrowser').css('display','block');
		}		
	}
</script>

<script type="text/javascript" src="<?php echo JURI::root()."media/media/js/color_picker.min.js"; ?>"></script>
<script type="text/javascript">
	jQuery(function($){

		$('#jform_logo_airline_desktop').change(function() {
			var file_data = $('#jform_logo_airline_desktop').prop('files')[0];   
		    var form_data = new FormData();                  
		    form_data.append('file', file_data);
		   

		    $.ajax({
		        type: "POST",
		        dataType: "json",
		        data: form_data,
		        cache: false,
                contentType: false,
                processData: false,
		        url: "../includes/uploadFile.php",
		        success: function(data){
		        	$(".logo").empty();
		            var html = "<input type='hidden' value='"+data+"' name='jform[logo_airline_desktop]' >";
		            $('.logo').append(html);
		        }
		    });
		    return false;
		});

		$('#jform_header_airline_desktop').change(function() {
			var file_data = $('#jform_header_airline_desktop').prop('files')[0];   
		    var form_data = new FormData();                  
		    form_data.append('file', file_data);
		   

		    $.ajax({
		        type: "POST",
		        dataType: "json",
		        data: form_data,
		        cache: false,
                contentType: false,
                processData: false,
		        url: "../includes/uploadFile.php",
		        success: function(data){
		        	$(".header").empty();
		            var html = "<input type='hidden' value='"+data+"' name='jform[header_airline_desktop]' >";
		            $('.header').append(html);
		        }
		    });
		    return false;
		});

		$('#jform_logo_header_mobile').change(function() {
			var file_data = $('#jform_logo_header_mobile').prop('files')[0];   
		    var form_data = new FormData();                  
		    form_data.append('file', file_data);
		   

		    $.ajax({
		        type: "POST",
		        dataType: "json",
		        data: form_data,
		        cache: false,
                contentType: false,
                processData: false,
		        url: "../includes/uploadFile.php",
		        success: function(data){
		        	$(".header_mobile").empty();
		            var html = "<input type='hidden' value='"+data+"' name='jform[logo_header_mobile]' >";
		            $('.header_mobile').append(html);
		        }
		    });
		    return false;
		});

		$('#jform_logo_voucher_mobile').change(function() {
			var file_data = $('#jform_logo_voucher_mobile').prop('files')[0];   
		    var form_data = new FormData();                  
		    form_data.append('file', file_data);
		   

		    $.ajax({
		        type: "POST",
		        dataType: "json",
		        data: form_data,
		        cache: false,
                contentType: false,
                processData: false,
		        url: "../includes/uploadFile.php",
		        success: function(data){
		        	$(".voucher_mobile").empty();
		            var html = "<input type='hidden' value='"+data+"' name='jform[logo_voucher_mobile]' >";
		            $('.voucher_mobile').append(html);
		        }
		    });
		    return false;
		});

		$('#jform_logo_creditcard_mobile').change(function() {
			var file_data = $('#jform_logo_creditcard_mobile').prop('files')[0];   
		    var form_data = new FormData();                  
		    form_data.append('file', file_data);
		   

		    $.ajax({
		        type: "POST",
		        dataType: "json",
		        data: form_data,
		        cache: false,
                contentType: false,
                processData: false,
		        url: "../includes/uploadFile.php",
		        success: function(data){
		        	$(".creditcard_mobile").empty();
		            var html = "<input type='hidden' value='"+data+"' name='jform[logo_creditcard_mobile]' >";
		            $('.creditcard_mobile').append(html);
		        }
		    });
		    return false;
		});

		

		$('#jform_desk_color_wrapper,#jform_desk_color_MB, #jform_desk_color_MT, #jform_desk_color_MTO, #jform_desk_color_STO,'+
			' #jform_desk_color_MA, #jform_desk_color_MAO, #jform_desk_color_SB, #jform_desk_color_ST,' +
			' #jform_mobile_color_MB, #jform_mobile_color_MT, #jform_mobile_color_MVB, #jform_mobile_color_MVT, #jform_mobile_color_MBB').colorPicker();
	});

	
</script>
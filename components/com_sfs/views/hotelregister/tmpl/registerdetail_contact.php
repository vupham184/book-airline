<?php
defined('_JEXEC') or die;

$session = JFactory::getSession();
$session->clear('tmpHotelContact');
$contacts = array();
for ( $i = 0; $i < 3; $i++ ) {
	$contacts[$i+1] = null;	
}

if(isset($this->contacts) && count($this->contacts)) {
	$n = 0;
	foreach ($this->contacts as $contact) {
		if ( $contact->is_admin == 1 ) continue;
		if( $contact->contact_type < 3 ) {
			$contacts[$contact->contact_type] = $contact;
		} else {
			$contacts[4+$n] = $contact;
			$n++;
		}		                	
	}
}

?>

<script type="text/javascript">
window.addEvent('domready', function(){
	$('add-hotel-staff').addEvent('click',function(){	 
		SqueezeBox.initialize(); 
		SqueezeBox.open('<?php echo JRoute::_('index.php?option=com_sfs&view=hotelregister&layout=addcontact&tmpl=component');?>', {	
			handler: 'iframe', 						
			size: {x: 555, y: 420},
			onClose: function(){ 						 
			     var result = $('additional_contacts');	  	
                 result.addClass('block border orange clearfix');     
			     new Request.HTML({
			       url: '<?php echo JURI::root();?>index.php?option=com_sfs&format=raw&task=ajax.getAdditionalContact',
			       update: result,			       
			       onRequest: function(){
			    	   result.empty().addClass('ajax-loading'); 
			       },
			       onSuccess: function(responseTree, responseElements, responseHTML, responseJavaScript){
			    	   result.removeClass('ajax-loading'); 			    	
			       }
			     }).send();			
			}
		});	
	});
});
</script>

<div class="info-block bg light-blue">
    <span><?php echo JText::_("COM_SFS_CONTACT_PERSONS");?></span> 
    <div class="clear"></div>
    <?php echo SfsHelper::getIntroTextOfArticle($this->params->get('article_page_1_03')); ?>
</div>                                      

    <?php     	 
    	$n = count($contacts);
    	for ( $i = 1; $i <= $n; $i++ ) : 
	?>    
        <div class="block border orange clearfix">        
    	<legend><span class="text_legend">
    		<?php
            if( $i==1 ){
           		echo JText::_('COM_SFS_GENERAL_MANAGER');
            }else if( $i==2){
           		echo JText::_('COM_SFS_STAFF_POSITION_MAIN_REVENUE');			
            }else if( $i==3){
           		echo JText::_('COM_SFS_STAFF_POSITION_MAIN_FRONT_OFFICE');			
            }
            ?></span>
    	</legend>         
        <div class="col w80 pull-left p20">
            <div class="form-group">
                <label><?php echo JText::_('COM_SFS_JOB_TITLE');?>:</label>
                <div class="col w60">
                    <?php
                    if ( !empty($contacts[$i]) ) {
                    	$job_title = $contacts[$i]->job_title;
                    }else {
                    	if( $i==1 ){
                    		$job_title = JText::_('COM_SFS_JOB_TITLE_GENERAL_MANAGER');
                    	}else if( $i==2)
                    	{
                    		$job_title = JText::_('COM_SFS_JOB_TITLE_REVENUE_MANAGER');
                    	}
                    	else if( $i==3){
                    		$job_title = JText::_('COM_SFS_JOB_TITLE_NIGHT_MANAGER');
                    	}	
                        
                    }             
                    ?>
                    <input type="text" name="contacts[<?php echo $i;?>][job_title]" value="<?php echo $job_title;?>" />
                </div>
            </div>
            <div class="form-group">
                <label><?php echo JText::_('COM_SFS_NAME');?>:</label>
                <div class="col w60">
                    <input type="text" value="<?php echo !empty($contacts[$i]) ? $contacts[$i]->name : '';?>" name="contacts[<?php echo $i;?>][name]" class="required"  />
                </div>
            </div>
            <div class="form-group">
                <label><?php echo JText::_('COM_SFS_SURNAME');?>:</label>
                <div class="col w60">
                    <input type="text" value="<?php echo !empty($contacts[$i]) ? $contacts[$i]->surname : '';?>" name="contacts[<?php echo $i;?>][surname]" class="required" />
                </div>
            </div>
            <div class="form-group">
                <label><?php echo JText::_('COM_SFS_TITLE');?>:</label>
                <div class="col w60">
                    <select size="1" class="inputbox" name="contacts[<?php echo $i;?>][gender]">
                        <option value="<?php echo JText::_('COM_SFS_GENDER_MR');?>"><?php echo JText::_('COM_SFS_GENDER_MR');?></option>
                        <option value="<?php echo JText::_('COM_SFS_GENDER_MRS');?>"><?php echo JText::_('COM_SFS_GENDER_MRS');?></option>
                        <option value="<?php echo JText::_('COM_SFS_GENDER_MS');?>"><?php echo JText::_('COM_SFS_GENDER_MS');?></option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label><?php echo JText::_('COM_SFS_EMAIL');?>:</label>
                <div class="col w60">
                    <input type="text" value="<?php echo !empty($contacts[$i]) ? $contacts[$i]->email : '';?>" name="contacts[<?php echo $i;?>][email]" class="required validate-email" />
                </div>
            </div>        
            <div class="form-group">
                <label><?php echo JText::_('COM_SFS_DIRECT_OFFICE_TELEPHONE'); ?>:</label>
                <div class="col w60">
                    <div class="row r10 clearfix">
        	            <div class="col w20">
        	                <input type="text" class="validate-numeric required smaller-size" value="<?php echo !empty($contacts[$i]) ? SfsHelper::formatPhone($contacts[$i]->telephone,1) : '';?>" name="contacts[<?php echo $i;?>][tel_int]" />	                
        	            </div>
        				<div class="col w80">
        		            <input type="text" class="validate-numeric required short-size" value="<?php echo !empty($contacts[$i]) ? SfsHelper::formatPhone($contacts[$i]->telephone,2) : '';?>" name="contacts[<?php echo $i;?>][tel_num]">
        	            </div>    	            
                    </div>
                    <small class="help-block"><?php echo JText::_('COM_SFS_INT_CODE_EXAMPLE')?></small>
                </div>
            </div>
           
        </div>
		<input type="hidden" name="contacts[<?php echo $i;?>][contact_type]" value="<?php echo !empty($contacts[$i]) ? $contacts[$i]->contact_type : ($i < 3)? $i : 3;?>" />
        <input type="hidden" value="<?php echo !empty($contacts[$i]) ? $contacts[$i]->user_id : 0;?>" name="contacts[<?php echo $i;?>][user_id]">
        <input type="hidden" value="<?php echo !empty($contacts[$i]) ? $contacts[$i]->id : 0;?>" name="contacts[<?php echo $i;?>][id]">             
        </div>       
	<?php endfor;?>
	<div id="additional_contacts"></div>
	<button type="button" class="modal btn orange sm" id="add-hotel-staff"><?php echo JText::_('COM_SFS_ADD_HOTEL_STAFF')?></button>		        
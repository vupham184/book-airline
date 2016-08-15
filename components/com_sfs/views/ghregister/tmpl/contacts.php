<?php
defined('_JEXEC') or die;
JHtml::_('behavior.keepalive');
JHtml::_('behavior.modal');
JHtml::_('behavior.formvalidation');
?>

<script type="text/javascript">
window.addEvent('domready', function(){
	Joomla.submitbutton = function(task) {
		if ( task == 'ghregister.additional' ) {
			//alert('Enter the required fields first');
			SqueezeBox.initialize(); 
			SqueezeBox.open( '<?php echo JRoute::_('index.php?option=com_sfs&view=ghregister&layout=addcontact&tmpl=component&Itemid='.JRequest::getInt('Itemid'));?>', {	
				handler: 'iframe', 			
				size: {x: 670, y: 520},
				onClose: function(){ 						 
				     var result = $('additional_contacts');	  		     
				     new Request.HTML({
				       url: '<?php echo JURI::root();?>index.php?option=com_sfs&format=raw&task=ajax.getGhAdditionalContact',
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
		}
		if ( task == 'ghregister.savecontacts' ) {
			if (document.formvalidator.isValid(document.id('airlineContactRegisterForm'))) {					
				Joomla.submitform(task, document.getElementById('airlineContactRegisterForm'));				
			} 
		} 
	}
});	
</script>
<div class="heading-block descript clearfix">
	<div class="heading-block-wrap">
		<h3><?php echo JText::_('COM_SFS_AIRLINE_CONTACT_DETAILS_SIGN_UP')?></h3>
		<p class="descript-txt">
			<?php
			$text = SfsHelper::getIntroTextOfArticle((int)$this->params->get('article_page_2_02'));
			echo empty($text) ? JText::_('COM_SFS_AIRLINE_REGISTER_CONTACTS_DESC') : $text;
			?>
		</p>
	</div>
</div>
<div class="fs-14 main">
<div id="airline-registration">

    <form name="airlineContactRegisterForm" id="airlineContactRegisterForm" action="<?php echo JRoute::_('index.php?option=com_sfs'); ?>" method="post" class="form-validate sfs-form form-vertical register-form">
        <div class="block-group">
            <div class="block border orange">
                <fieldset>
                    <legend style="margin-bottom: 0"><?php echo JText::_('COM_SFS_AIRLINE_CONTACT_PERSONS')?></legend>
                    <div class="sfs-white-wrapper floatbox">
                        <?php
                        $text = SfsHelper::getIntroTextOfArticle((int)$this->params->get('article_page_2_03'));
                        echo empty($text) ? JText::_('COM_SFS_AIRLINE_CONTACT_PERSONS_DESC') : $text;
                        ?>
                    </div>
                </fieldset>
            </div>
        </div>



                                <?php
                                $session 		= JFactory::getSession();
                                $airContacts 	= $session->get('airContacts');

                                if(count($airContacts)) {
                                    $n = count($airContacts);
                                    foreach ($airContacts as $key => $value) {
                                        $this->contact_item = $value;
                                        $this->contact_index = $key;
                                        $this->main_contact = $value['main_contact'];
                                        echo $this->loadTemplate('fields');
                                    }

                                }  else {
                                    $n = 2;
                                    $i=0;
                                    while ( $i < $n ){
                                        $this->contact_item = null;
                                        if(isset($airContacts[$i])) $this->contact_item = $airContacts[$i];
                                        $this->contact_index = $i;

                                        if($i==0) {
                                            $this->main_contact = 1;
                                        } else {
                                            $this->main_contact = 0;
                                        }

                                        echo $this->loadTemplate('fields');
                                        $i++;
                                    }
                                }
                                ?>

                                <div id="additional_contacts"></div>

                                <div class="sfs-white-wrapper floatbox">
                                    <ul class="menu-command">
                                        <li>
                                            <button type="button" onClick="Joomla.submitbutton('ghregister.additional')") class="button float-left">
                                                <?php echo JText::_('COM_SFS_AIRLINE_ADDITIONAL_CONTACT_BUTTON')?>
                                            </button>
                                        </li>
                                    </ul>
                                </div>

                            </div>
                        </div>
                    </fieldset>
            </div>
        </div>
    
		<div class="sfs-below-main floatbox">
			<div class="airline-note">
				<?php echo JText::_('COM_SFS_AIRLINE_REGISTER_STEP2_NOTE')?>
			</div>        
            <div class="float-right">
            	<ul class="menu-command">
                	<li>
                		<button type="button" onClick="Joomla.submitbutton('ghregister.savecontacts')" class="button">
			            	<?php echo JText::_('COM_SFS_AIRLINE_SAVE_TO_LAST_BUTTON')?>
			            </button>
                	</li>
                </ul>  
            </div>       	                 
        </div>
        
       	<input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid');?>" />
        <input type="hidden" name="task" value="" />
        <?php echo JHtml::_('form.token'); ?>            
        
    </form>

</div>
</div>
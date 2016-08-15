<?php
defined('_JEXEC') or die();
JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
?>

<script type="text/javascript">
window.addEvent('domready', function(){
	var hotelRegisterForm = document.id('hotelRegistraionForm');
	hotelRegisterForm.getElements('[type=text], select').each(function(el){
    	new OverText(el);
	});
	new Form.Validator(hotelRegisterForm);
});
</script>


<div class="heading-block clearfix">
    <div class="heading-block-wrap">
        <h3><?php echo JText::_('COM_SFS_HOTEL_TEAM_REGISTRATION'); ?></h3>        
    </div>
</div>

<div class="main">  	
	  	<?php $text = SfsHelper::getIntroTextOfArticle($this->params->get('article_page_1_01'));		       
		     echo empty($text) ? JText::_('COM_SFS_HOTEL_REGISTER_DESC','class="info-block bg light-blue"') : $text;		       
		?>	

	<div id="hotel-registraion">    
        
	    <form id="hotelRegistraionForm" action="<?php echo JRoute::_('index.php?option=com_sfs'); ?>" method="post" class="sfs-form form-vertical register-form">
	        <div class="block-group">
                <div class="block border orange">

                    <fieldset>
						<legend><span class="text_legend"><?php echo JText::_('COM_SFS_HOTEL');?></span></legend>                        
                        <div class="col w80 pull-left p20">
                            <div class="form-group"> 
                                <label><?php echo JText::_('COM_SFS_HOTEL_NAME')?></label>
                                <div class="col w60">
                                	<input type="text" size="30" class="required" name="hotel_name" id="hotel_name" value="<?php echo count($this->data) ? $this->data['hotel_name'] : '';?>" />
                                </div>
                            </div>
                            <div class="form-group"> 
                                <label id="hotel_chain"><?php echo JText::_('COM_SFS_CHAIN_AFFILIANTION');?></label>
                                <div class="col w60">
                                	<?php echo SfsHelperField::getChainField(count($this->data) ? $this->data['chain_id'] : 0); ?>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </div>
                
                <div class="block border orange">
                    <fieldset>
                        <?php echo $this->loadTemplate('contact'); ?>
                    </fieldset>
                </div>
                
                <div class="block border orange">
                    <fieldset>
                        <?php echo $this->loadTemplate('account'); ?>
                    </fieldset>
                </div>	                	            
	        </div>
	        
	        <div class="form-group">
	            <div class="float-left register-bottom-left">
					<?php
					$text = SfsHelper::getIntroTextOfArticle($this->params->get('article_page_1_02'));
					echo empty($text) ? JText::_('COM_SFS_HOTEL_REGISTER_BOTTOM_NOTE') : $text;
					?>
	            </div>	            
	                
		        <button type="submit" class="btn orange lg pull-right"><?php echo JText::_('COM_SFS_SAVE_AND_REQUEST_CONTACT_LOGIN');?></button>
	                	            
	        </div>
	        <input type="hidden" name="option" value="com_sfs" />
	        <input type="hidden" name="task" value="hotelregister.save" />
	        <?php echo JHtml::_('form.token'); ?>
	    </form>
	    
	</div>

</div>
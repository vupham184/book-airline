<?php
defined('_JEXEC') or die;
JHtml::_('behavior.keepalive');
JHtml::_('behavior.modal');
?>

<script type="text/javascript">
<!--
window.addEvent('domready', function(){

	$('time_zone').addEvent('change',function(){
	     var result = $('time_display');

	     new Request.HTML({
	       url: '<?php echo JURI::root();?>index.php?option=com_sfs&format=raw&task=ajax.getTime&id='+this.value ,
	       update: result,
	       onRequest: function(){
	    	   result.empty().addClass('ajax-loading');
	       },
	       onSuccess: function(txt){
	    	   result.removeClass('ajax-loading');
	       }
	     }).send();
	});

	$('copy_address').addEvent('click',function(){
		$('billing_address').set('value', $('address').get('value'));
		$('billing_address1').set('value', $('address1').get('value'));
		$('billing_address2').set('value', $('address2').get('value') );
		$('billing_city').set('value', $('city').get('value') );
		$('billing_zipcode').set('value', $('zipcode').get('value') );


		var country = $('country_id').getSelected().get('value');

		for (var i = 0; i < $('billingcountry_id').options.length; i++)
			if ($('billingcountry_id').options[ i ].value == country) {
				$('billingcountry_id').options[ i ].selected='selected';
				break;
			}


	});

	var hotelRegisterForm = document.id('hotelRegisterForm');
	var hotelRegisterFormValidate = new Form.Validator(hotelRegisterForm);

	hotelRegisterFormValidate.add('validate-timezone-check', {
	    errorMsg: 'This field is required.',
	    test: function(field){
		    if(!field.checked) {
		    	$('timezone-error-msg').set('styles', {
		    	    display: 'block'
		    	});
		    	new Fx.Scroll(window).toElement($('hotel-address-fields'));
		    } else {
		    	$('timezone-error-msg').set('styles', {
		    	    display: 'none'
		    	});
		    }
	    	return !!field.checked;
	    }
	});

});
-->
</script>

<?php
	$title = 'Hotel register detail';
	$desc = '';
	if ($this->hotel->step_completed < 9 ) {
		$title = $this->hotel->name;
		$desc = SfsHelper::getIntroTextOfArticle($this->params->get('article_page_1_01'));
		$desc = empty($desc) ? JText::_('COM_SFS_HOTEL_DETAIL_REGISTER_DESC') : $desc;
	} else{
		$title = JText::_('COM_SFS_HOTEL_TEAM_DETAILS');
		$desc = JText::sprintf('COM_SFS_LABEL_WELCOME', $this->user->name);
		$desc .= '<br/>' . JText::_('COM_SFS_HOTEL_TEAM_DETAILS_TOP_NOTE');
	}
?>

<div class="heading-block clearfix">
    <div class="heading-block-wrap">
        <h3><?php echo JText::sprintf('COM_SFS_STEP', 5); ?> Users Registration</h3>
    </div>
</div>

<div class="main">
	<h1 class="page-title" style="text-align:center"><?php echo $this->hotel->name; ?></h1>

	<div id="hotel-registraion">
		<?php if ($this->hotel->step_completed < 9 ) : ?>

	       	<?php echo $this->progressBar(4); ?>

	        <h1 class="page-title"><?php echo JText::_('COM_SFS_HOTEL_TEAM_USERREGISTRATION'); ?></h1>

	    <?php else : ?>
	        <div class="clear"></div>
	    <?php endif; ?>

	    <form id="hotelRegisterForm" action="<?php echo JRoute::_('index.php?option=com_sfs&view=hotelregister'); ?>" method="post" class="form-validate sfs-form form-vertical register-form">

		<!-- <div class="sfs-above-main sfs-hotel-title">
		    <?php if ($this->hotel->step_completed < 6 ) : ?>
	        	 <h2><?php echo JText::_('COM_SFS_HOTEL_REGISTRATION_FORM'); ?> <?php echo JText::_('COM_SFS_ENGLISH_INPUT_ONLY');?></h2>
	        <?php else : ?>
	        	<h2><?php echo JText::_('COM_SFS_HOTEL_CONTACT_DETAILS'); ?></h2>
	         <?php endif; ?>
	    </div>
 -->
	    <div class="block-group">
            <div class="block border orange">
                <fieldset>
                    <legend><span class="text_legend"><?php echo JText::_('COM_SFS_HOTEL');?></span></legend>  
                    <div class="col w80 pull-left p20">
	                    <div class="form-group">
	                        <label><?php echo JText::_('COM_SFS_HOTEL_NAME');?> :</label> 
	                        <div class="col w60" style="padding-top: 10px;"><?php echo $this->hotel->name; ?></div>
	                    </div>
	                    <div class="form-group">
	                        <label><?php echo JText::_('COM_SFS_CHAIN_AFFILIATION');?> :</label>
	                        <div class="col w60" style="padding-top: 10px;"><?php echo SfsHelperField::getChainName($this->hotel->chain_id); ?></div>
	                    </div>                   
                    </div> 
                </fieldset>
            </div>

            <div class="block border orange" id="hotel-address-fields">
                <fieldset>
					<?php echo $this->loadTemplate('address'); ?>
                </fieldset>
            </div>

            <div class="block border orange">
                <fieldset>
					<?php echo $this->loadTemplate('billing'); ?>
                </fieldset>
            </div>    
			<?php echo $this->loadTemplate('contact'); ?>
          
	    </div>

	    <div class="form-group">
			<?php if ( ! $this->hotel->isRegisterComplete() ) : ?>
				<div class="float-left" style="width:550px">
					<?php echo JText::_('COM_SFS_HOTEL_DETAIL_REGISTER_BOTTOM_NOTE');?>
				</div>				
			    <button type="submit" class="btn orange sm validate pull-right"><?php echo JText::_('COM_SFS_SAVE_AND_NEXT')?></button>		    	
			<?php else :?>		    	
		    	<button type="submit" class="btn orange lg validate pull-right"><?php echo JText::_('COM_SFS_SAVE_AND_CLOSE')?></button>
	    	<?php endif;?>

	        <input type="hidden" name="task" value="hotelregister.savehotel" />
	        <input type="hidden" name="hotel_id" value="<?php echo $this->hotel->id;?>" />
	        <input type="hidden" name="user_id" value="<?php echo $this->user->id; ?>" />
	        <?php echo JHtml::_('form.token');?>
	    </div>

	  </form>
	</div>

</div>
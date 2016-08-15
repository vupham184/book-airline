<?php
defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
JHtml::_('behavior.modal');

$session = JFactory::getSession();
$contacts = $session->get('airContacts');
if( ! isset ($contacts[0]) ) return;
?>

<script type="text/javascript">
	window.addEvent('domready', function(){	
	    // The elements used.
		var airlineRegisterForm = document.id('airlineRegisterForm');
		  // 	Labels over the inputs.
		airlineRegisterForm.getElements('[type=text], [type=checkbox]').each(function(el){
	    	new OverText(el);
		});
		// Validation.
		new Form.Validator.Inline(airlineRegisterForm); 
	});
</script>

<div class="heading-block clearfix">
    <div class="heading-block-wrap">
        <h3><?php echo JText::_('COM_SFS_TERMS_AND_CONDITIONS')?></h3>      
    </div>
</div>

<div class="main">
    <form id="airlineRegisterForm" name="airlineRegisterForm" action="<?php echo JRoute::_('index.php?option=com_sfs'); ?>" method="post">    	    	        	
    	
    		<?php 
    			$text = SfsHelper::getIntroTextOfArticle((int)$this->params->get('article_page_2_04'));
    			echo empty($text) ? JText::_('COM_SFS_AIRLINE_REGISTER_CONFIRM_DESC') : $text;	
    		?>    	
        
        <div class="fieldset-fields" style="padding:40px;">
        
        	<?php 
			foreach ( $contacts as $contact) :  
				if( (int)$contact['main_contact'] ) :
				?>
					<div class="register-field clear floatbox">
						<label><?php echo JText::_('COM_SFS_FIRST_NAME')?>:</label>
						<?php echo $contact['name'];?>
					</div>             
					<div class="register-field clear floatbox">
						<label><?php echo JText::_('COM_SFS_SURNAME')?>:</label>
						<?php echo $contact['surname'];?>
					</div>             
					<div class="register-field clear floatbox">
						<label><?php echo JText::_('COM_SFS_JOB_TITLE')?>:</label>
						<?php echo $contact['job_title'];?>
					</div>      
				<?php 
					break;
				endif;
			endforeach; 
			?>
            
            <div class="floatbox">
                <input type="checkbox" name="confirm" value="1" /> <?php echo JText::_('COM_SFS_AIRLINE_REGISTER_CONFIRM_TEXT')?><br /><br />
            	<a href="index.php?option=com_sfs&view=article&id=<?php echo (int)$this->params->get('article_page_2_05')?>&tmpl=component" rel="{handler: 'iframe', size: {x: 675, y: 400}}" class="modal"><?php echo JText::_('COM_SFS_AIRLINE_REGISTER_CONFIRM_DETAIL_LINK')?></a>
            </div>
        </div>
        <div class="sfs-below-main">
        	<ul class="menu-command float-right" >
	        	<li>
	            	<button type="submit" class="btn orange lg"><?php echo JText::_('COM_SFS_SAVE_AND_FINISH_BUTTON')?></button>
	            </li>
            </ul>
        </div>        
        <input type="hidden" name="task" value="airlineregister.confirm" />
       	<input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid');?>" />        
        <?php echo JHtml::_('form.token'); ?>           
    </form>	
</div>
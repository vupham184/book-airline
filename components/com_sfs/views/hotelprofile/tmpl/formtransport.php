<?php
defined('_JEXEC') or die;

$tax = $this->hotel->getTaxes();
$this->hotel->currency_name = $tax->currency_name;

JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
JHtml::_('behavior.modal');

?>

<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (document.formvalidator.isValid(document.id('transport-form'))) {
			Joomla.submitform(task, document.getElementById('transport-form'));
		}
	}
</script>

<?php
$preview = JRequest::getInt('preview');
if( $preview == 1 ) :
?>
	<script type="text/javascript">
	window.addEvent('domready', function() {
		SqueezeBox.initialize();
		SqueezeBox.open( '<?php echo JURI::base()?>index.php?option=com_sfs&view=voucher&layout=sample&tmpl=component', {
			handler: 'iframe',
			size: {x: 723, y: 700}
		});
	});
	</script>
<?php endif;?>

    		<?php if($this->hotel->step_completed < 9) : ?>
					<?php $title = JText::sprintf('COM_SFS_STEP', 6) . JText::_('COM_SFS_LABLE_TRANSPORT'); ?>
            <?php else : ?>
                <?php $title = $this->hotel->name.' - '.JText::_('COM_SFS_LABLE_TRANSPORT'); ?>
             <?php endif; ?>
        

    <!-- <div class="heading-block-wrap">
        <h3><?php //echo $title?></h3>        
    </div>
</div> -->

<div class="heading-block descript clearfix">
    <div class="heading-block-wrap">
        <h3><?php echo $title?></h3>
        <p class="descript-txt"><?php echo @$text?></p>
    </div>
</div>

<div class="main">
	<div id="hotel-registraion">
	    <?php if( ! $this->hotel->isRegisterComplete()) :?>
	        <h1 class="page-title" style="text-align:center"><?php echo $this->hotel->name; ?></h1>
	        <?php echo $this->progressBar(3); ?>
	    <?php endif; ?>

	    <form action="<?php echo JRoute::_('index.php?option=com_sfs&view=hotelprofile'); ?>" method="post" name="transport-form" id="transport-form" class="form-validate sfs-form form-vertical register-form">
	        		    
	        <div class="block-group">
				<div class="block border orange clearfix">
	            	<?php echo $this->loadTemplate('detail');?>
	            </div>
	        </div>		    

		    <div class="wrap-col text-right">		    	
		        <?php if( ! $this->hotel->isRegisterComplete()) :?>
					<button type="button" class="btn orange lg" name="save_next" onclick="Joomla.submitbutton('hotelprofile.saveTransport')">Next step &gt;&gt;</button>
		        <?php else : ?>
		           	<button type="button" class="btn orange lg" name="save_close" onclick="Joomla.submitbutton('hotelprofile.saveTransport')"><?php echo JText::_('COM_SFS_SAVE_AND_CLOSE');?></button>
		        <?php endif; ?>		        
		    </div>

		    <input type="hidden" name="id" value="<?php echo is_object($this->transport) ? $this->transport->id : 0 ;?>" />
		    <input type="hidden" name="task" value="" />
		    <?php echo JHtml::_('form.token'); ?>
	    </form>
	</div>
</div>

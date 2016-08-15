<?php
defined('_JEXEC') or die;
JHTML::_('behavior.tooltip');
JHTML::_('behavior.modal');
JHtml::_('behavior.modal', 'button.modal');
$status = isset($this->block_code) ? $this->block_code->status : null;
?>
<script type="text/javascript">

	function roomingsubmit(pressbutton) {

		var roomingmessage = document.getElementById('roomingmessage');

		var form = document.roomingForm;
		form.task.value=pressbutton;
		if(roomingmessage){
			form.rooming_message.value=roomingmessage.value;
		}
		form.submit();
	}

	window.addEvent('domready', function() {
		$('sendBlock').addEvent('click', function(){
			var voucherLent = $$('.input-vouchernumber').length;
			var countVouchers = 0;
			$$('.input-vouchernumber').each(function(el){
			    if(el.get('value')){
			    	countVouchers++;
			    }
			});
			if( voucherLent == countVouchers) {
				SqueezeBox.open(
					$('send-rooming-list'),
					{handler: 'clone',size: {x: 675, y: 480},onOpen: function(){textArea = $('sbox-content').getElement('textarea');textArea.set('id','roomingmessage');}
				});
			} else {
				SqueezeBox.open($('send-rooming-list-warning'), {handler: 'clone',size: {x: 645, y: 460},onOpen: function(){textArea = $('sbox-content').getElement('textarea');textArea.set('id','roomingmessage');} });
			}
		});

		var vstatus = {
		    'true': 'open',
		    'false': 'close'
	    };

		var verticalSlide = new Fx.Slide('verticalSlide',{
			transition: Fx.Transitions.Cubic.easeInOut
		});
		verticalSlide.hide();

		$('vToggle').addEvent('click', function(event){
			event.stop();
			verticalSlide.toggle();
			$('vToggle').set('text',vstatus[verticalSlide.open]);
			if(verticalSlide.open){
			} else {
			}
		});
	});

</script>



<div class="heading-block descript clearfix">
    <div class="heading-block-wrap">
        <h3><?php echo JText::_('COM_SFS_ROOMING_PAGE_TITLE');?></h3>
		<?php if ( isset($this->block_code) && isset($this->airline) ) : ?>
        <div class="descript-txt"><?php echo JText::_('COM_SFS_ROOMING_YOU_WILL_BE_SENDING')?>: <?php echo $this->airline->name;?></div>
        <?php endif;?>
    </div>
</div>



<div class="main">

    <div class="sfs-yellow-wrapper orange-top-border">
        <div class="sfs-white-wrapper floatbox" style="margin-bottom:30px;">
        	<div class="fs-16"><?php echo JText::_('COM_SFS_ROOMING_OPTION_1')?></div>
        	<div id="verticalSlide">
            	<?php echo $this->loadTemplate('upload'); ?>
            </div>
            <a id="vToggle" class="float-right" href="#">Open</a>
        </div>

        <form name="roomingForm" action="<?php echo JRoute::_('index.php?option=com_sfs&view=rooming');?>" method="post">
            <?php echo $this->loadTemplate('manual'); ?>
            <input type="hidden" name="airport" value="<?php echo $this->state->get('rooming.airport');?>" />
            <input type="hidden" name="rooming_message" value="" />
            <input type="hidden" name="task" value="" />
            <input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid');?>" />
            <?php echo JHtml::_('form.token'); ?>
        </form>
    </div>
    <div class="main-bottom-block">
		<div class="float-left btn-group">

			<?php if ( !isset($this->block_code) ) : ?>
			<div class="s-button">
				<a href="index.php?option=com_sfs&view=dashboard&Itemid=103">
					<button class="btn orange sm">
						<?php echo JText::_('COM_SFS_BACK');?>
					</button>
				</a>
			</div>
			<?php endif;?>

				<?php if ( !isset($this->block_code) || (int)$this->block_code->get('minimum_guarantee_voucher',null) == 0 ) : ?>
					<div class="s-button">
						<button type="button" onclick="roomingsubmit('rooming.requestVoucher')" class="btn orange sm">
							<?php echo JText::_('COM_SFS_REQUEST_MINIMUM_GUARANTEE_VOUCHER') ?>
						</button>
					</div>
				<?php else : ?>
					<div class="float-left" >
						<?php echo JText::_('COM_SFS_MINIMUM_GUARANTEE_VOUCHER_REQUESTED')?>
					</div>
				<?php endif;?>
		</div>

		<div class="float-right btn-group">
			<?php if( $status === null || in_array($status, array('P', 'O')) ) : ?>
			<div class="s-button">
                <a href="<?php echo JRoute::_( 'index.php?option=com_sfs&view=block&Itemid='.JRequest::getInt('Itemid') );?>">
					<button class="btn orange sm">
						<?php echo JText::_('COM_SFS_CLOSE');?>
					</button>
                </a>
			</div>
			<div class="s-button">
                <button id="sendBlock" type="button" class="btn orange sm">
                    <?php echo JText::_('COM_SFS_ROOMING_SEND');?>
                </button>
			</div>
            <?php endif;?>
            <?php if( in_array($status, array('T', 'C')) ) :
                $link = JRoute::_('index.php?option=com_sfs&view=block&layout=tentative&blockid='.$this->block_code->id.'&Itemid='.JRequest::getInt('Itemid'));
            ?>
                <div class="s-button">
                    <a href="<?php echo $link;?>">
                        <button type="button" class="btn orange sm">
                            Back
                        </button>
                    </a>
                </div>
            <?php endif;?>
            <?php if( in_array($status, array('C')) ) : ?>
                <div class="s-button">
                    <button id="sendBlock" type="button" class="btn orange sm" style="margin:0 10px 0 20px;">
                        <?php echo JText::_('COM_SFS_ROOMING_SEND');?>
                    </button>
                </div>
            <?php endif;?>
		</div>
    </div>

</div>



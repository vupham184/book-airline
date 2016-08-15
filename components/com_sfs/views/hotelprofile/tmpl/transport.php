<?php
defined('_JEXEC') or die;
?>
<div class="heading-block descript clearfix">
	<div class="heading-block-wrap">
		<h3><?php echo $this->hotel->name.' - Transport Information'; ?></h3>
	</div>
</div>

<div id="sfs-wrapper" class="fs-14 main">
	<div class="">
		<div class="sfs-orange-wrapper ">

			<div class="sfs-white-wrapper sfs-white-wrapper-last floatbox">

				<div id="transport-information">

					<div style="margin-bottom: 20px;">Does your hotel provide
						transportation for the stranded passengers</div>

					<div class="clear"></div>

					<div class="ft-form-row">
						<div class="ft-form-label float-left">Transportation available</div>
						<div class="ft-form-field float-left">
						<?php
						switch ( (int)$this->transport->transport_available ) {
							case 1:
								echo 'Yes';
								break;
							case 2:
								echo  'Not necessary (walking distance)';
								break;
							default :
								echo  'No';
								break;
						}
						?>
						</div>
						<div class="clear"></div>
					</div>

					<div class="ft-form-row">
						<div class="ft-form-label float-left">Complimentary</div>
						<div class="ft-form-field float-left">
						<?php echo ( is_object($this->transport) && $this->transport->transport_complementary==1 ) ? ' Yes' : ' No'; ?>
						</div>
						<div class="clear"></div>
					</div>

					<div class="ft-form-row ft-form-row2">

						<div class="ft-form-label float-left">Operating hours of the
							transportation</div>
						<div class="ft-form-field float-left">
						<?php
						if($this->transport->operating_hour==0) {
							echo 'Not available';
						} elseif ($this->transport->operating_hour==1) {
							echo '24-24 for stranded';
						}else {
							echo 'From <strong>'.$this->transport->operating_opentime.'</strong> till <strong>'.$this->transport->operating_closetime.'</strong>';
						}
						?>
						</div>
					</div>

					<div class="ft-form-row">
						<div class="ft-form-label float-left">
							Frequency of the transport service
							<div class="fs-12">For instance every 30 minutes</div>
						</div>
						<div class="ft-form-field float-left">
							Every
							<?php echo $this->transport->frequency_service; ?>
							Minutes
						</div>
						<div class="clear"></div>
					</div>

					<div class="ft-form-row">
						<div class="ft-form-label float-left">
							Details of pickup
							<div class="fs-12" style="line-height: 13px;">Please mention
								where the customers can find a hotel phone or where they should
								report for an pick up</div>
						</div>
						<div class="ft-form-field float-left" style="width: 550px;">
						<?php echo $this->transport->pickup_details; ?>
						</div>
						<div class="clear"></div>
					</div>


				</div>


			</div>

		</div>
	</div>

	<div class="sfs-below-main">
		<div class="s-button float-left">
			<a
				href="<?php echo JRoute::_( SfsHelperRoute::getSFSRoute('hotelprofile') );?>"
				class="s-button"> <?php echo JText::_('COM_SFS_BACK');?> </a>
		</div>
		<div class="s-button float-right">
			<a
				href="<?php echo JRoute::_('index.php?option=com_sfs&view=hotelprofile&layout=formtransport&Itemid='.JRequest::getInt('Itemid'));?>"
				class="s-button"> <?php echo JText::_('COM_SFS_EDIT');?> </a>
		</div>

	</div>
	<div class="clear"></div>


</div>

<?php
defined('_JEXEC') or die;
$backendParams = $this->hotel->getBackendSetting();
$listCur = SfsHelper::getCurrencyMulti();
?>
<div class="heading-block descript clearfix">
	<div class="heading-block-wrap">
		<h3><?php echo $this->hotel->name.' - '.JText::_('COM_SFS_LABLE_TAXES'); ?></h3>
	</div>
</div>
<div id="sfs-wrapper" class="fs-14 main">
<div id="hotel-registraion" class="sfs-wrapper">

	<div class="sfs-main-wrapper-none">
		<div class="sfs-orange-wrapper">
		
			<div class="sfs-white-wrapper floatbox" style="margin-bottom: 25px;">
				<div class="fs-16">
					<?php echo JText::_('COM_SFS_LABLE_CURRENCY'); ?>
				</div>
				
				<div style="padding-left: 150px;" class="midpaddingtop">
					Currency:
					<!-- <?php echo $this->taxes->currency_name;?>-->
					<?php 
                        foreach ($listCur as $value) {
                        	if($value->id == $this->hotel->currency_id){
                        		echo $value->code; 
                        	}                          
                        }
                    ?> 
				</div>
			</div>

			<div class="sfs-white-wrapper floatbox">
			
				<div class="fs-16"><?php echo JText::_('COM_SFS_LABLE_TAXES'); ?></div>
				

				<div style="padding-left: 150px;" class="midpaddingtop">
					<div class="sfs-row">
						<div class="sfs-column-left" style="width: 60px;">
							<?php echo $this->taxes->percent_total_taxes;?>
						</div>
						<?php echo JText::_('COM_SFS_HOTEL_PERCENT_TOTAL_TAXES_PER_NIGHT')?>
					</div>
					<div class="sfs-row">
						<div class="sfs-column-left" style="width: 60px;">
							<?php echo $this->taxes->os_fee_per_night;?>
						</div>
						<?php echo JText::_('OS_FEE_PER_NIGHT')?>
					</div>
					<div class="sfs-row">
						<div class="sfs-column-left" style="width: 60px;">
							<?php echo $this->taxes->os_fee_per_stay;?>
						</div>
						<?php echo JText::_('OS_FEE_PER_STAY')?>
					</div>
				</div>

				<p>
				<?php //echo SfsHelper::getArticle(102, 1, 1); ?>
				</p>
			</div>
			
			
			<div class="sfs-white-wrapper floatbox">
			
				<div class="fs-16"><?php echo JText::_('COM_SFS_MERCHANT_FEE'); ?></div>
				

				<div style="padding-left: 150px;" class="midpaddingtop">
					<div class="sfs-row">
						<div class="sfs-column-left" style="width: 60px;">
							<?php echo (int)$this->merchant_fee->merchant_fee;?> <?php echo (int)$this->merchant_fee->merchant_fee_type==1? '%':$this->taxes->currency_name?>
						</div>
						<?php 
						if( (int)$this->merchant_fee->merchant_fee_type==1 ){
							echo JText::_('COM_SFS_COMMISION_PERCENTAGE_ROOMS');
						} else {
							echo 'Commision fixed price per room';
						}						
						?>
					</div>
					<div class="sfs-row">
						<div class="sfs-column-left" style="width: 60px;">
							<?php echo (int)$this->merchant_fee->dinner_merchant_fee;?> %
						</div>
						<?php echo JText::_('COM_SFS_COMMISION_PERCENTAGE_FB')?>
					</div>
					<?php if( isset($backendParams) && (int)$backendParams->merchant_fixed_fee_enable == 1 ) : ?>
					<div class="sfs-row">
						<div class="sfs-column-left" style="width: 60px;">
							<?php echo (int)$this->merchant_fee->monthly_fee;?> <?php echo $this->taxes->currency_name?>
						</div>
						<?php echo JText::_('COM_SFS_NET_MONTHLY_FEE')?>
					</div>
					<?php endif;?>
					
					<?php if ($this->merchant_fee->comment) : ?>
					<div class="sfs-row">
						<div class="sfs-column-left" style="width: 60px;">
							Comment
						</div>
						<?php echo $this->merchant_fee->comment;?>
					</div>
					<?php endif;?>
				</div>

				<p>
					<?php //echo SfsHelper::getArticle(102, 1, 1); ?>
				</p>
			</div>
			


			<div class="sfs-white-wrapper floatbox">
				<div class="fs-16"><?php echo JText::_('COM_SFS_FREE_RELEASE_POLICY'); ?></div>
				<div style="padding-left:150px;" class="midpaddingtop">
 						<div class="sfs-row">
 							<?php echo JText::_('COM_SFS_PERCENTAGE_FREE_RELEASE_POLICY')?>*&nbsp;&nbsp;
                            <?php echo floatval($this->taxes->percent_release_policy);?>
                            <div class="clear"></div>
                			<p class="fs-12"><?php echo JText::_('COM_SFS_PERCENTAGE_FREE_RELEASE_POLICY_NOTE'); ?></p>
                        </div>
                </div>
			</div>

		</div>
	</div>
	<div class="sfs-below-main">
		<div class="s-button float-left">
			<a href="javascript:window.history.back();" class="s-button"><?php echo JText::_('COM_SFS_BACK');?></a>
		</div>
		<div class="s-button float-right">
    		<a href="<?php echo JRoute::_('index.php?option=com_sfs&view=hotelprofile&layout=formtaxes&Itemid='.JRequest::getInt('Itemid'));?>"	class="s-button">
    			<?php echo JText::_('COM_SFS_EDIT');?>
    		</a>
        </div>
	</div>

</div>
</div>
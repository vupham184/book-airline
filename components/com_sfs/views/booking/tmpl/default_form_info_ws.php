<style>
    .erratum{
        display: none !important;
        color: white;
        background-color: #01B2C3;
        border-radius: 10px;
        padding: 5px 5px 5px 5px;
        margin-right: 10px
    }
</style>
<?php
	defined('_JEXEC') or die();
	/* @var $wsRoomType Ws_Do_Search_RoomTypeResult */
	/* @var $wsPreBook Ws_Do_PreBook_Response */
	/* @var $pc Ws_Do_PreBook_CancellationResponse */

	$wsRoomTypes = $this->wsRoomTypes;
	$wsPreBook = $this->wsPreBook;
    $system_currency = SfsHelper::getCurrency();
    $currency = SfsWs::getCurrencyIdByCurrencyCode($system_currency);
	$currency_symbol = $currency['CurrencySymbol'];
    $app = JFactory::getApplication();
?>

<table cellpadding="0" cellspacing="0" border="0" width="100%">
	<tr valign="top">
		<td width="55%" style="padding-left: 15px;vertical-align: top;">
			<div class="fs-16">
				<?php echo $this->hotel->name;?>
				<?php if( (int)$this->hotel->star > 0 ): ?>
				<div>
					<?php
					for($i=0;$i<(int)$this->hotel->star;$i++) {
						echo '<img src="components/com_sfs/assets/images/star.png" />';
					} 
					?>
				</div> 				
				<?php endif;?>
			</div>
			
			<div class="floatbox fs-14">
			
				<?php foreach($this->wsRoomTypes as $room) : ?>
					<?php $wsRoomType = Ws_Do_Search_RoomTypeResult::fromString($room['roomType']);?>
					<div class="sfs-row">
						Room: <?php echo $wsRoomType->NumberOfRooms ?> <?php echo $wsRoomType->Name?>. Mealplan: <?php echo $wsRoomType->MealBasisID ? $wsRoomType->MealBasisName : 'No'?>
					</div>	
				<?php endforeach?>
				
				<div class="sfs-row">
					<div class="sfs-column-left">
						Rate: 
					</div>
					<?php echo $currency_symbol . $wsPreBook->TotalPrice?>
				</div>
				<br/>
				<?php if($wsPreBook->TotalPrice != ceil($wsRoomType->Total)):?>
				<div class="sfs-row" style="color: white; background-color: #01B2C3; border-radius: 10px;padding: 15px;display: inline-block">
					<img src="<?php echo JURI::base();?>templates/<?php echo $app->getTemplate();?>/images/alert_icon.png" style="float:left; margin-left: 5px" />
					<div style="margin-left: 50px; font-size: 13px">
					Please note there is a rate change, you are kindly requested to check if you still want to make the booking at this new amount.
					</div>
				</div>
				<?php endif;?>

				
				<?php
				$session = JFactory::getSession();
				$payment_type = $session->get('payment_type'); 				
				if($payment_type=='airline'||$payment_type=='passenger'):
				?>
				
				<div class="sfs-row">
					<div class="sfs-column-left">
						Invoice for: 
					</div>
					<div class="float-left">
						<?php if($payment_type=='airline'):?>
						<?php echo $airline->name?>
						<?php else :?>
						Passenger
						<?php endif;?>
					</div>
				</div>
				<?php endif;?>
				
			</div>

            <?php if($wsRoomType->Errata) : ?>
                <div class="erratum">
                    <img src="<?php echo JURI::base();?>templates/<?php echo $app->getTemplate();?>/images/alert_icon.png" style="float:left; margin-left: 5px;" />
                    <div style="margin-left: 35px; font-size: 13px; padding: 10px 5px 10px 5px">
                        <?php foreach($wsRoomType->Errata as $erratum):?>
                            Erratum: <?php echo $erratum->Subject?>
                            <br/>
                            <?php echo $erratum->Description?>
                            <br/>
                        <?php endforeach;?>
                    </div>
                </div>
            <?php endif;?>
			
		</td>
		<td width="45%" style="padding-left: 15px;vertical-align: top;">
		
		<div class="fs-16">
			Cancellations:
		</div>
		<?php if(count($wsPreBook->Cancellations)) : ?>
			<?php foreach($wsPreBook->Cancellations as $pc) :?>
				<div style="padding-top:15px;" class="fs-14">
					<?php $from = date('d/m/Y', strtotime($pc->StartDate));
						$to = date('d/m/Y', strtotime($pc->EndDate));
					?>
					<?php if($from && $from != '01/01/1970') :?>
						from <?php echo $from?>
					<?php endif;?>
					<?php if($to && $to != '01/01/1970') : ?>
						to <?php echo $to?>
					<?php endif;?>
					penalty
                    <?php
                        $componentParams = &JComponentHelper::getParams('com_sfs');
                        $configSaleRate = $componentParams->get('ws-sales-rate');
                        $pc->Penalty  = ceil($pc->Penalty*(1+ $configSaleRate/100));
                        echo $currency_symbol . $pc->Penalty
                    ?>
				</div>
			<?php endforeach;?>
		<?php else: ?>
		 <div style="padding-top:15px;" class="fs-14">
		 	Cancellations: No
		 </div>
		 <?php endif;?>

		 <div class="fs-14" style="padding-top:15px;">
		 	Transport to accommodation included: No
		 </div>
		
		</td>
	</tr>
</table>
	


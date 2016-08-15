<?php
defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.modal');

$document = JFactory::getDocument();
$document->addStyleSheet(JURI::base().'components/com_sfs/assets/css/match.css');

$airline = SFactory::getAirline();
$airlineName = '';
if($airline->grouptype == 3) {
	$selectedAirline = $airline->getSelectedAirline();
	$airlineName = 	$selectedAirline->name;
}

$jsonUrl  = JURI::base().'index.php?option=com_sfs&task=match.check&format=json';
$jsonUrl2 = JURI::base().'index.php?option=com_sfs&task=match.testRooms&format=json';

$clcss = JRequest::getVar('clcss');
if ( $clcss == 'none') :
?>
<style>
	.notice.message, .alert.alert-notice{
		display:none !important;
	}
	.
</style>
<?php endif;?>

<script type="text/javascript">
	<!--
	window.addEvent('domready', function() {
		
		var alertFx = new Fx.Scroll(window);
		
		var airlineMatch = document.id('airlineMatch');
		var countPassenger = 0;
		var countReservation = 0;
		var reservationId = 0;

		$('validateGroupVoucher').addEvent('click', function(e){
			e.stop();
			countPassenger = 0;
			countReservation = 0;
			reservationId = 0;

			$('sfsVoucherSelectRooms').setStyle('display','none');
			
			$$('.check-seats').each(function(seat){
				if( seat.checked ){
					countPassenger = countPassenger + 1;
				}
			});
			$$('.reservationItem').each(function(reservation){
				if( reservation.checked ){
					countReservation = countReservation + 1;
					reservationId = reservation.get('value').toInt();
				}
			});
			if(countPassenger==0) {
				//alert('Please select Passengers');
				alertFx.toElement($('bd'));
				$('alertMsg').setStyle('display','block');
				$('alertMsg').set('text','Please select Passengers');
			} else {
				if(countReservation==0){
					alertFx.toElement($('bd'));
					$('alertMsg').setStyle('display','block');
					$('alertMsg').set('text','Please select Hotel');
				} else {
					///if( countPassenger < 4 ) {
					if( countPassenger < 2 ) {
						airlineMatch.submit();
					} else {
						new Request.JSON({
							url: '<?php echo $jsonUrl;?>',
							onRequest: function(){
								$('ajax-Spinner').addClass('ajax-Spinner');
							},
							onSuccess: function(jsonObject) {
							    if( jsonObject.allowMatch == 1 ) {
							    	airlineMatch.submit();
							    } else {
								    if(jsonObject.showSelectRooms == 1){
										if ( jsonObject.single_room_available == 1 ) {											
											$('td_total_double_rooms').set('text','Double rooms');
										}
										else {
											$('tr_total_single_rooms').setStyle('display','none');
										}
										if ( jsonObject.quad_room_available == 0 ) {											
											
											$('tr_quad_room_available').setStyle('display','none');
										}
										
								    	$('sroomAvailable').set('text',jsonObject.s_room);
								    	$('sdroomAvailable').set('text',jsonObject.sd_room);
								    	$('troomAvailable').set('text',jsonObject.t_room);
								    	$('qroomAvailable').set('text',jsonObject.q_room);
										
										/*if ( $('sroomAvailable').get('text') > 0 ) {
											$('total_single_rooms').set('value','1');
											
										}
										else if( $('sdroomAvailable').get('text') > 0 ) {
											$('total_double_rooms').set('value','1');
										}*/
										
								    	$('sfsVoucherSelectRooms').setStyle('display','block').setStyle('top', (jQuery(document).height()-600) + "px");
								    } else {
								    	alertFx.toElement($('bd'));
										$('alertMsg').setStyle('display','block');
										$('alertMsg').set('text',jsonObject.error);
								    }
							    }
							    $('ajax-Spinner').removeClass('ajax-Spinner');
							},
							onError: function(text, error){
								$('ajax-Spinner').removeClass('ajax-Spinner');
							}
						}).get({'seats': countPassenger, 'reservationId': reservationId});
					}
				}
			}
		});

		$('selectRoomButton').addEvent('click', function(e){
			e.stop();
			$('alertMsg2').setStyle('display','none');
			var total_single_rooms = $('total_single_rooms').get('value');
			var total_double_rooms = $('total_double_rooms').get('value');
			var total_triple_rooms = $('total_triple_rooms').get('value');
			var total_quad_rooms   = $('total_quad_rooms').get('value');
			
			airlineMatch.calback_total_single_rooms.value = total_single_rooms;
			airlineMatch.calback_total_double_rooms.value = total_double_rooms;
			airlineMatch.calback_total_triple_rooms.value = total_triple_rooms;
			airlineMatch.calback_total_quad_rooms.value = total_quad_rooms;
			
			//alert( '<?php echo $jsonUrl2;?>' );return false;
			new Request.JSON({
				url: '<?php echo $jsonUrl2;?>',
				onRequest: function(){
					$('ajax-Spinner1').addClass('ajax-Spinner');
				},
				onSuccess: function(jsonObject) {
				    if( jsonObject.allowMatch == 1 ) {
				    	airlineMatch.submit();
				    } else {
					    if(jsonObject.error){
					    	$('alertMsg2').setStyle('display','block');
							$('alertMsg2').set('text',jsonObject.error);
					    }
				    }
				    $('ajax-Spinner1').removeClass('ajax-Spinner');
				},
				onError: function(text, error){
					$('ajax-Spinner1').removeClass('ajax-Spinner');
				}
			}).get({
				'seats': countPassenger,
				'reservationId': reservationId,
				'total_single_rooms': total_single_rooms,
				'total_double_rooms': total_double_rooms,
				'total_triple_rooms': total_triple_rooms,
				'total_quad_rooms': total_quad_rooms
			});
		});

		var transportEvent = function(){
			countPassenger = 0;
			$$('.check-seats').each(function(seat){
				if( seat.checked ){
					countPassenger = countPassenger + 1;
				}
			});
			///if(countPassenger > 4){
			if(countPassenger > 2){
				$$('.taxiTransportWrap').setStyle('display','none');
				$$('.groupTransportWrap').setStyle('display','block');
				
			}
			///if(countPassenger < 4){
			if(countPassenger < 2){
				$$('.taxiTransportWrap').setStyle('display','block');
				$$('.groupTransportWrap').setStyle('display','none');
			}

			///if( countPassenger == 4 ) {
			if( countPassenger == 2 ) {
				$$('.reservationItem').each(function(reservation){
					resId = reservation.get('value').toInt();
					availableqroomId = 'availableqroom'+resId;
					availableqroom = $(availableqroomId).get('value').toInt();

					taxiTransportWrapId = 'taxiTransportWrap'+resId;
					groupTransportWrapId = 'groupTransportWrap'+resId;
					
					if(availableqroom == 0)
					{
						$(taxiTransportWrapId).setStyle('display','none');
						$(groupTransportWrapId).setStyle('display','block');
					} else {
						$(taxiTransportWrapId).setStyle('display','block');
						$(groupTransportWrapId).setStyle('display','none');
					}
				});
			}
			 	
		}

		transportEvent();

		$$('.check-seats').addEvent('change', function(e){
			e.stop();
			transportEvent();
		});

		$$('.reservationItem').addEvent('change', function(e){
			e.stop();
			transportEvent();
		});

		//SqueezeBox.open($('sfs-voucher-print-form'), {
		//	handler: 'adopt'
		//});
			
	});
	jQuery.noConflict();
	jQuery(function($){
		equalheight = function(container){

			var currentTallest = 0,
				currentRowStart = 0,
				rowDivs = new Array(),
				$el,
				topPosition = 0;
			$(container).each(function() {

				$el = $(this);
				$($el).height('auto')
				topPostion = $el.position().top;

				if (currentRowStart != topPostion) {
					for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
						rowDivs[currentDiv].height(currentTallest);
					}
					rowDivs.length = 0; // empty the array
					currentRowStart = topPostion;
					currentTallest = $el.height();
					rowDivs.push($el);
				} else {
					rowDivs.push($el);
					currentTallest = (currentTallest < $el.height()) ? ($el.height()) : (currentTallest);
				}
				for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
					rowDivs[currentDiv].height(currentTallest);
				}
			});
		};
		$(window).load(function() {
			equalheight('.contrast-block');
			$('.contrast-block.table').height($('.contrast-block.table').height()+10);
		});

	});

	-->
</script>

<?php
	if( count($this->voucher) > 0 ) :
		 
		///$this->voucher->vgroup =  intval($this->voucher->vgroup);
		
		if(count($this->voucher) == 1) :
			
			if( $this->voucher[0]->taxi_voucher_id ) : ?>

                <div id="sfs-wrapper" class="match">
                    <div id="sfs-voucher-print-form">
						<div  class="modal-block col-2">
                            <div class="modal-inner" style="border: #01B2C3;">
								<?php echo $this->loadTemplate('printform');?>
							</div>
							<div class="wrap-modal"></div>
						</div>
					</div>
                </div>
			<?php
			else:?>
                <div id="sfs-wrapper" class="match">
					<div id="sfs-voucher-print-form">
						<div  class="modal-block">
							<div class="modal-inner" style="border: #01B2C3;">
								<?php echo $this->loadTemplate('printform');?>
							</div>
							<div class="wrap-modal"></div>
						</div>
					</div>
                </div>

			<?php
			endif;
		else :
			//group voucher
			?>
			<div id="sfs-voucher-print-form">
						<div  class="modal-block col-2">
							<div class="modal-inner">
			<?php
			echo $this->loadTemplate('groupvoucherform');
			?>
								</div>
							<div class="wrap-modal"></div>
						</div>
					</div>
			
			<?php
		endif;
		
	endif;
?>

<div class="heading-block clearfix">
    <div class="heading-block-wrap">
        <h3><?php if($airlineName) echo $airlineName.': ';?><?php echo JText::_('COM_SFS_AIRLINE_MATCH_PAGE_TITLE');?></h3>
        <div class="clear floatbox largemargintop" style="text-align:center;color:#fff;">
        	<?php if($this->prevNight):?>
        	<a href="<?php echo $this->prevNightUrl?>" class="match-prev-night" data-step="8" data-intro="<?php echo SfsHelper::getTooltipTextEsc('match_prev_night', '&lt;&lt; Previous night', 'airline');?>"><?php echo SfsHelper::htmlTooltip('match_prev_night', '&lt;&lt; Previous night', 'airline');?></a>
        	<?php else:?>
        	<span class="match-prev-night"><?php echo SfsHelper::htmlTooltip('match_prev_night', '&lt;&lt; Previous night', 'airline');?></span>
        	<?php endif;?>
        	<a href="<?php echo $this->nextNightUrl?>" class="match-next-night" data-step="10" data-intro="<?php echo SfsHelper::getTooltipTextEsc('match_next_night', 'Next night &gt;&gt;', 'airline');?>"><?php echo SfsHelper::htmlTooltip('match_next_night', 'Next night &gt;&gt;', 'airline');?></a>
        	
        	<div class="sfs-match-title-desc<?php if($this->todayDate != $this->night) echo ' sfs-match-title-desc-warning';?>" data-step="9" data-intro="<?php echo SfsHelper::getTooltipTextEsc('match_night_range', $text, 'airline');?>">
        		<?php
    	    	$text = 'For the night starting: '.JFactory::getDate($this->night)->format('d M Y').' ending: '.JFactory::getDate($this->nextNight)->format('d M Y');
    	    	echo SfsHelper::htmlTooltip('match_night_range', $text, 'airline');
    	    	?>
        	</div>
        </div>
    </div>
</div>

<!-- Main -->
<div class="main">
	<div id="alertMsg" class="uk-alert uk-alert-danger" style="display:none;"></div>

	<div class="box-style yellow radius">
		<div class="airline-overview airline-overview4 clearfix">
			<form action="<?php echo JRoute::_('index.php?option=com_sfs&view=match');?>" name="airlineMatch" id="airlineMatch" method="post">
			
				<!-- airline-seat -->
				<?php echo $this->loadTemplate('seats');?>
				
				<!-- airline-hotel -->
				
				<?php echo $this->loadTemplate('reservations');?>
				
				
				<?php
				echo $this->loadTemplate('selectrooms');
				?>
								
				<input type="hidden" name="nightdate" value="<?php echo $this->night?>" />
				<input type="hidden" name="option" value="com_sfs" />
			    <input type="hidden" name="task" value="match.match" />
                
                <!--De kiem tra truong hop khi nguoi dung cho 3 seats PASSENGERS va khi show popup [Please select the number of rooms] add 1 Single rooms & 1 Double rooms-->
                <input type="hidden" name="calback_total_single_rooms" value="" />
                <input type="hidden" name="calback_total_double_rooms" value="" />
                <input type="hidden" name="calback_total_triple_rooms" value="" />
                <input type="hidden" name="calback_total_quad_rooms" value="" />
                
			    <input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid');?>" />
			    <?php echo JHtml::_('form.token'); ?>
			</form>
			
		</div>
			
	<div class="main-bottom-block">
		<a class="btn orange sm" href="<?php echo JRoute::_('index.php?option=com_sfs&view=dashboard&Itemid='.JRequest::getInt('Itemid'))?>"><?php echo JText::_('COM_SFS_BACK')?></a>
		

		<a href="javascript:void(0)" id="validateGroupVoucher" class="btn orange sm pull-right" data-step="7" data-intro="<?php echo SfsHelper::getTooltipTextEsc('match_submit', $matchButton, 'airline');?>"><?php echo JText::_('COM_SFS_AIRLINE_MATCH') ?></a>
		<?php
		//$matchButton = '<a href="javascript:void(0)" id="validateGroupVoucher" class="btn orange sm pull-right">'.JText::_('COM_SFS_AIRLINE_MATCH').'</a>';
		// echo SfsHelper::htmlTooltip('match_submit', $matchButton, 'airline');
		?>				
		<div id="ajax-Spinner" class="float-right"></div>
	</div>
	</div>
	

<!-- End Main -->


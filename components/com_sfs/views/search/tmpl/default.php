<?php
defined('_JEXEC') or die();
JHTML::_('behavior.tooltip');
JHTML::_('behavior.modal');

echo $this->loadTemplate('geomap_partial');

$ordering_url = 'index.php?option=com_sfs&view=search&Itemid='.(int)JRequest::getInt('Itemid');

if( (int)$this->state->get('filter.rooms') ) {
    $ordering_url .= '&rooms='.$this->state->get('filter.rooms');
}
if( (int)$this->state->get('filter.date_start') ) {
    $ordering_url .= '&date_start='.$this->state->get('filter.date_start');
}
if( (int)$this->state->get('filter.date_end') ) {
    $ordering_url .= '&date_end='.$this->state->get('filter.date_end');
}
if( (int)$this->state->get('filter.hotel_star') ) {
    $ordering_url .= '&hotel_star='.$this->state->get('filter.hotel_star');
}
$extend = JRequest::getInt('extend');
if( (int)$extend == 1 ) {
    $ordering_url .= '&extend=1';
}

$this->ordering_url = JURI::base().$ordering_url.'&ordering=';
?>

<div class="heading-block clearfix">
    <div class="heading-block-wrap">
        <h3>Search</h3>
    </div>
</div>

<?php if(JRequest::getInt('reservation_id') == null || JRequest::getInt('print') ==  1) {?>
    <div class="main">
        <?php
        if( ( isset($this->result) && count($this->result) ) || $this->state->get('filter.allow_search') ) :

            //echo $this->loadTemplate('request');

            echo $this->loadTemplate('results');

        else :
            echo $this->loadTemplate('form');
        endif;
        ?>
    </div>
<?php } ?>


<?php
$reservation_id = JRequest::getInt('reservation_id');
$singlevoucherpreview = JRequest::getInt('singlevoucherpreview');

if($reservation_id) :
    if($reservation_id == -1){
        $session = JFactory::getSession();
        $this->reservation = unserialize($session->get("reservation_temp"));
    }else{
        $this->reservation = SReservation::getInstance($reservation_id);
    }
$airline = SFactory::getAirline();

$sizeY = 400;

if( isset($airline->params['show_vouchercomment']) && (int)$airline->params['show_vouchercomment'] == 1 ) {
	$sizeY += 140;
}
if( isset($airline->params['enable_return_flight']) && (int)$airline->params['enable_return_flight'] == 1 ) {
	$sizeY += 110;
}
if( $reservation->sd_room){
	$sizeY += 30;
}
if( $reservation->t_room){
	$sizeY += 70;
}
if( $reservation->q_room){
	$sizeY += 80;
}

if(!empty($reservation->ws_room_type)) {
	$wsRoomType = Ws_Do_Search_RoomTypeResult::fromString($reservation->ws_room_type);
	if($wsRoomType->NumAdultsPerRoom == 1) {
		$sizeY += 30;
	}
	if($wsRoomType->NumAdultsPerRoom == 2) {
		$sizeY += 70;
	}
	if($wsRoomType->NumAdultsPerRoom == 3) {
		$sizeY += 110;
	}
	if($wsRoomType->NumAdultsPerRoom == 4) {
		$sizeY += 130;
	}
}

$print = JRequest::getInt('print',0);
?>
<script type="text/javascript">
<?php if($singlevoucherpreview) : ?>

	<?php
		$voucherPreviewUrl = JURI::base().'index.php?option=com_sfs&view=voucher&tmpl=component&layout=singlevoucherpreview&reservation_id='.$reservation_id;
	?>

	window.addEvent('domready', function(){
		var voucherPreviewUrl  = '<?php echo $voucherPreviewUrl?>';
		SqueezeBox.open( voucherPreviewUrl,
			{
				handler: 'iframe',
				size: {x: 800, y: 600}, //user airplus
				/*size: {x: 500, y: 600},*/
				closable: false,
				onClose: function(){
					window.location.href = '<?php echo $this->ordering_url?>';
				}
			}
		);
	});

<?php elseif(!$print): 
	if ( $reservation_id == -1 ) :
?>
	var iframeX = 600; //su dung cho popup WS
<?php else:?> 
var iframeX = 800; //su dung cho popup partner
<?php endif;?>
var vourcherFormUrl = '<?php echo JURI::base()?>index.php?option=com_sfs&view=match&layout=singlevoucher&tmpl=component&reservation_id=<?php echo $reservation_id?>';
window.addEvent('domready', function(){
	SqueezeBox.open( vourcherFormUrl,
		{
			handler: 'iframe',
			size: {x: iframeX, y: <?php echo $sizeY?>},
			closable: false
		}
	);
});
<?php else:
	$printVoucherUrl = JURI::base().'index.php?option=com_sfs&view=voucher&tmpl=component&reservation_id='.$reservation_id;
?>
window.addEvent('domready', function(){
	var vourcherFormUrl  = '<?php echo $printVoucherUrl?>';
	//800 ,700
	SqueezeBox.open( vourcherFormUrl,
		{
			handler: 'iframe',
			size: {x: 954, y: 700},
			closable: false
		}
	);
});
<?php endif;?>

</script>

<?php
endif;
?>
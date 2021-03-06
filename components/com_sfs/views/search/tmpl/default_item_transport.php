<?php
defined('_JEXEC') or die();
?>

<span class="r-heading" style="display: inline-block; color: #000000; font-weight: normal">Hotel shuttle available</span>
<span class="transport-content" style="display: inline-block">
<?php

if( (int)$this->item->transport_available > 0 && (int) $this->item->transport_included > 0 ) :

$transportTooltip = 'Transport to accommodation included: ';

switch ( (int)$this->item->transport_available ) {
	case 1:
		$transportTooltip .= 'Yes';
		break;
	case 2:
		$transportTooltip.='Not necessary (walking distance)';
		break;
	default :
		$transportTooltip .= 'No';
		break;
}

$transportTooltip .='<br />';
$transportTooltip .= (int)$this->item->transport_complementary == 1 ? 'Complimentary: Yes':'Complimentary: No';
$transportTooltip .='<br />';
$this->item->operating_hour = (int)$this->item->operating_hour	;
if($this->item->operating_hour == 0 ){
	$transportTooltip .='Operation hours: Not available';
} else if($this->item->operating_hour == 1) {
	$transportTooltip .='Operation hours: 24-24 for stranded';
} else if($this->item->operating_hour == 2) {
	$transportTooltip .='Operation hours: From '.str_replace(':','h',$this->item->operating_opentime).' till '.str_replace(':','h',$this->item->operating_closetime);
}
$transportTooltip .='<br />';
$transportTooltip .='Every: '.$this->item->frequency_service.' minutes';
if($this->item->pickup_details) {
	$transportTooltip .='<br /><br />Details:<br />'.$this->item->pickup_details;
}
$tip = SfsHelper::getTooltip('search_result_transport', $this->tooltip);

if($tip) $transportTooltip = $tip;

?>
<span class="hasTip" title="<?php echo $transportTooltip;?>">
	: Yes <img src="components/com_sfs/assets/images/transport-info.png" alt="" style="vertical-align: text-bottom" />
</span>
<?php else : ?>
: No
<?php endif; ?>
</span>
<?php if ($this->item->ws_id>0){?>
<span class="r-heading" style="display: inline-block; color: #000000; font-weight: normal">
    <a class="button-information" rel="<?php echo $this->item->hotel_id?>" style="cursor: pointer">Please check hoteldescription</a>
</span>
<?php }?>
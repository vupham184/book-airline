<?php
defined('_JEXEC') or die();
JHTML::_('behavior.tooltip');
JHTML::_('behavior.modal');

jimport('joomla.application.component.controller');

$date_start  = date( "Y-m-d" , strtotime('+1 day', strtotime( date("Y-n-d") ) ) );
$result = strtotime( date("Y-n-d") );
$end_date = date( "Y-m-d" , strtotime('+2 day', $result) );

$str = $this->airport_location[0];
$is_listArr = $this->airport_location[1];

?>
<script src="https://code.jquery.com/jquery-1.11.2.min.js" type="text/javascript"></script>
<script>
	<?php echo $str;?>
	
	var t = 0, len = AirportCodeArr.length - 1;
    function searchHotels() {
		if (t > len ) {
			jQuery('.status').text('DONE').css('color', 'green');
			return false;
		}
		var el = $('#airports').find('tr.not-loaded:eq(0)');
		el.find('.status').text('Loading...').css('color', 'red');
		jQuery("#filter-bar .notice").text('Please wait, synchronizing all hotels!').css("color", "blue");
		$.ajax({
                url: '../index.php?option=com_sfs&task=search.autoSearch' + '&AirportCode='+AirportCodeArr[t]+'&date_start=<?php echo $date_start;?>&rooms=1&ws_only=1',
                success: function () {
					el.find('.status').text('DONE').css('color', 'green');
                    el.removeClass('not-loaded');
					t++;
					searchHotels();
                }
            });
		if ( !len ) {
			$('#start').removeAttr('disabled');
            $("#filter-bar .notice").text('Synchronized all hotels completely!').css("color", "green");
		}
    }

   // searchHotels();
    $(function(){
        $('#start').click(function(){
			t = 0;
           searchHotels();
        });
    });
</script>

<fieldset id="filter-bar">
    <div class="sfs-cpanel-left float-left">
        <button id="start" type="button" >Start</button>
        <span class="notice" style="color:blue; display: none;"></span>
    </div>
    <div class="sfs-cpanel-right">
        <span style="margin-left: 10%"></span>
    </div>
</fieldset>
<div>
    <table class="adminlist">
        <thead>
        <tr>
            <th width="10%">Airport Code</th>
            <th width="15%" style="display:none;">Region</th>            
            <th width="10%">Status</th>
            <th width="65%">Command</th>
        </tr>
        </thead>
        <tbody id="airports">
        <?php $i = -1;?>
        <?php foreach($is_listArr as $air) : ?>
        <?php $i++?>
        <tr class="not-loaded" data-index="<?php echo $i?>" data-id="<?php echo $air->AirportCode?>" data-name="<?php echo $air->name?>">
            <td class="name"><?php echo $air->AirportCode?></td>
            <td class="name" style="display:none;"><?php echo $air->Region?></td>
            <td class="status"></td>
            <td class="command"><?php echo JURI::root()."index.php?option=com_sfs&task=search.autoSearch&AirportCode=$air->AirportCode&date_start=" . date('Y-m-d') . "&rooms=1&ws_only=1";?></td>
        </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div>


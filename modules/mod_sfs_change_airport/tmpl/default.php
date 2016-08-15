<?php
defined('_JEXEC') or die;
?>
<script>
    jQuery.noConflict();
    jQuery(function($){
        $(document).ready(function(){
            $('#form-change-airport .ui.dropdown').dropdown({
                onChange: function (val) {
					$('input[name="airport_id"]').remove();
                    var $form = $("#form-change-airport");
                    $('<input />').attr('type', 'hidden')
                        .attr('name', "airport_id")
                        .attr('value', val)
                        .appendTo($form);
                    $form.submit();
                }
            });
        })
    })
</script>
<?php
 if(count($airport_list) >1):
 	
	//lchung
	function fwriteJson( $filename = '', $response ){
		$fp = fopen($filename, 'w');
		fwrite($fp, json_encode($response));
		fclose($fp);
	}
	$ses = session_id();
	$content_ = '';
	$code = '';
	foreach($airport_list as $airportSub){
		if($airportSub->id == $airport_current_id){
			$code = $airportSub->code;
		}
	}
                    
	if ( file_exists(JPATH_ROOT . '/tmp/changeAirport/info.log') ) {
		$c = file_get_contents(JPATH_ROOT . '/tmp/changeAirport/info.log');
		if( $c != '' ) {
			$js = json_decode( $c );
			$d = array( );
			foreach( $js as $vk => $v  ){
				if ( $vk == $ses) 
					$d[$vk] = array( 'id' => $airport_current_id, 'code' => $code );
				else
					$d[$vk] = array( 'id' => $v->id, 'code' => $v->code );;
			}
			
			$content_ = $d;
		}
	}
	else {
		$content_ = array( $ses => array('id' => $airport_current_id, 'code' => $code) );
	}
	fwriteJson( JPATH_ROOT . '/tmp/changeAirport/info.log', $content_);
	//End lchung
 ?>
<div style=" float: right; margin-top: 20px; margin-right: 120px">
    <label style="vertical-align: -webkit-baseline-middle; font-size: 20px">Airport:</label>
   <form action="<?php echo JRoute::_('index.php');?>" method="post" id="form-change-airport">
        <div class="ui floating dropdown labeled search icon button" style="position: absolute; top: 15px; margin-left: 80px" data-step="1" data-intro="<?php echo SfsHelper::getTooltipTextEsc('change_airport', 'help-icon', 'airline', false) ?>">
            <i class="world icon"></i>
            <span class="text"><?php echo $airport_current_code;?></span>
            <div class="menu">
                <?php foreach($airport_list as $airport):?>
                    <?php if($airport->id == $airport_current_id):?>
                        <div class="item <?php echo "active"?>" data-value="<?php echo $airport->id?>"><?php echo $airport->code?></div>
                    <?php else:?>
                        <div class="item" data-value="<?php echo $airport->id?>"><?php echo $airport->code?></div>
                    <?php endif?>
                <?php endforeach;?>
            </div>
        </div>
        <input type="hidden" name="task" value="changeAirport" />
        <input type="hidden" name="type" value="<?php echo $type?>" />
        <input type="hidden" name="redirect_link" value="<?php echo JUri::getInstance(); ?>" />
    </form>
</div>
<?php endif;?>

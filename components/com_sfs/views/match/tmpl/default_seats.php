<?php
defined('_JEXEC') or die;
?>
<?php if( count($this->flights_seats) ) : ?>
<script type="text/javascript">
<!--
	window.addEvent('domready', function() {						
		$('allseats').addEvent('click', function() {
			SqueezeBox.open($('select-all-seats'), {handler: 'clone',size: {x: 350, y: 80}});						
		});			
	});	

	function selectallseats(status)
	{
		if(status==1) {
			$('allseats').checked=true;
			$$('.check-seats').each(function(el) { el.checked = true; });
		} else {
			$('allseats').checked=false;
			$$('.check-seats').each(function(el) { el.checked = false; });
		}		
		window.parent.SqueezeBox.close();		
	}	
	
-->
</script>		
<?php endif;?>

<script type="text/javascript">
<!--
	//lchung
	jQuery.noConflict();
	jQuery(function($){
		var h_contrast_body = $('.contrast-body').css('height').replace("px","");
		var h_select_reservation = $('.select-reservation').css('height').replace("px","");
		if ( parseInt( h_contrast_body ) > parseInt( h_select_reservation ) ){
			h_contrast_body = parseInt( h_contrast_body ) + 10;
			$('.select-reservation').css('height', h_contrast_body + 'px');
		}
		else if( parseInt( h_contrast_body ) < parseInt( h_select_reservation ) ){
			h_select_reservation = parseInt( h_select_reservation ) - 10;
			$('.contrast-body').css('height', h_select_reservation + 'px');
		}
	});
-->
</script>

<div class="contrast-block-wrapper"  style="width:200px; float:left">	
	<h4><?php echo JText::_('COM_SFS_PASSENGERS');?></h4>	

	<div class="contrast-block">
		<div class="contrast-body" data-step="1" data-intro="<?php echo SfsHelper::getTooltipTextEsc('select_passengers', $text, 'airline');?>">
        	<div class="contrast-foot" style="margin-top:62px;" ></div>
			<?php
			$seat_count = 0;
			if( count($this->flights_seats) ) :
			?>					
				<?php 
				foreach ( $this->flights_seats as $item ) :
					$item->seats = (int) $item->seats - (int)$item->seats_issued ;
					$seat_count += $item->seats;
					for( $i = 0; $i < $item->seats ; $i++  ) :
				?>
					<div>
						<input class="check-seats" type="checkbox" name="flight[<?php echo $item->id;?>][<?php echo $i + 1; ?>]" value="<?php echo $item->flight_code;?>" />
						<?php echo $item->flight_code.' '.$item->flight_class;?>
					</div>	
					<?php endfor;?>						
				<?php endforeach; ?>					
			<?php endif;?>		
		</div>
		<div class="contrast-foot" data-step="2" data-intro="<?php echo SfsHelper::getTooltipTextEsc('select_all_passengers', $text, 'airline');?>" style="text-align:left;">
			<?php if( count($this->flights_seats) ) : ?>	
				<input type="checkbox" name="allseats" value="1" id="allseats" />
                <label for="allseats">Select all</label><br />
			<?php endif;?>
			<span style="margin-left:6px;">
			<?php echo $seat_count ?> <?php echo JString::strtolower(JText::_('COM_SFS_PASSENGERS'));?>
            </span>
		</div>							
	</div>			
</div>
<div style="display:none">
    <div id="select-all-seats">	
        <div class="select-all-seats-wrap">		               			
            Are you sure you want to select all seats / flights?<br />
            
            <div class="s-button float-left" style="margin-top:15px;">
	            <button type="button" class="s-button" onclick="selectallseats(1);" style="min-width: 60px;">
		            <?php echo JText::_('COM_SFS_YES')?>
	            </button>
            </div>
            <div class="s-button float-left" style="margin-left:15px; margin-top:15px;">
	            <button type="button" class="s-button" onclick="selectallseats(0);" style="min-width: 60px;">
	            	<?php echo JText::_('COM_SFS_NO')?>
	            </button>
            </div>
        </div>			               			               				
    </div>
</div>	 

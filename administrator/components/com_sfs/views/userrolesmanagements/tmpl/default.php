<?php
// no direct access
defined('_JEXEC') or die;
$items = $this->items;
?>
<style>
.list-code{
	float:left;
	width:30px;
}
.max-airport-code{
	max-width:810px;
	overflow-x: scroll;
	position:relative;
}
.max-list-h{
	max-height:400px;
	overflow-y: scroll;
}
.adminlist p{
	padding:0px;
	margin:0px;
}
.adminlist td.padding-b1{
	padding-bottom:1px;
}
.adminlist td.padding-b2{
	padding-bottom:2px;
}
.adminlist td.padding-b3{
	padding-bottom:3px;
}

/*Format div*/
p{
	padding:0px;
}
.d-content-items{
	border-bottom:1px solid #ccc;
}
.d-column{
	float:left;
}
.d-column1{
	width:38%
}
.d-column2{
	width:62%;
}
.d-column-w20{
	width:20%;
}
.d-column-w30{
	width:30%;
}
.d-column-w40{
	width:40%;
}
.d-column-w35{
	width:35%;
}
.d-column-w5{
	width:5%;
}

.d-column-w50{
	width:100px;
}
.d-column-w70{
	width:100px;
}
.d-column-w100{
	width:100px;
}
.d-margin-t15{
	margin-top:15px;
}
.d-padding-l5{
	padding-left:5px;
}
.b{
	font-weight:bold;
}
.colum-sub{
	width:50px;
	text-align:center;
}
.title-sub-l{
	width:100%;
	clear:both;
}
.title-sub{
	width:<?php echo count($this->airportcodes)*50?>px;
}
.heg30{
	height:30px;
	padding-bottom:5px;
	padding-right:5px;
}
.heg15{
	height:15px;
}
.p-t10{
	padding-top:10px;
	display:block;
}
.p-b10{
	padding-bottom:10px;
}
.position-absolute{
	background-color:#E5E5E5;
	padding-top:15px;
	padding-bottom:10px;
}
.p-t20{
	padding-top:20px;
}
.text-long{
    white-space:nowrap; 
	overflow:hidden;
	text-overflow:ellipsis;
	/*max-width: 100px;*/
}
</style>
<form action="" method="post" name="adminForm" id="adminForm" class="max-list-h">
	<input type="hidden" id="getchangeairport_id" name="getchangeairport_id" value="<?php echo $this->state->get('filter.getchangeairport_id',0);?>" />
    <div class="d-title">
    	<div class="d-column d-column1 d-margin-t15" style="position:relative;">
        	<div class="title-sub-l heg30 d-content-items">
                <div class="d-column d-column-w30">
                    <p class="d-padding-l5"><input type="text" name="filter_code" id="filter_code" value="<?php echo $this->escape($this->state->get('filter.code')); ?>" /></p>
                    <p class="d-padding-l5 b">Airline</p>
                </div>
                <div class="d-column d-column-w30">
                    <p class="d-padding-l5"><input type="text" name="filter_name" id="filter_name" value="<?php echo $this->escape($this->state->get('filter.name')); ?>" /></p>
                    <p class="d-padding-l5 b">Name</p>
                </div>
                <div class="d-column d-column-w35">
                    <p class="d-padding-l5"><input type="text" name="filter_g_name" id="filter_g_name" value="<?php echo $this->escape($this->state->get('filter.g_name')); ?>" /></p>
                    <p class="d-padding-l5 b">Userrole</p>
                </div>
                <div class="d-column d-column-w5">
                    <p class="d-padding-l5">&nbsp;</p>
                    <p class="d-padding-l5 b">All</p>
                </div>
            </div>
            
            <div class="title-sub-l heg30 d-content-items position-absolute p-t20" style="display:none;">
                <div class="d-column d-column-w30">
                    <p class="d-padding-l5">&nbsp;</p>
                    <p class="d-padding-l5 b">Airline</p>
                </div>
                <div class="d-column d-column-w30">
                    <p class="d-padding-l5">&nbsp;</p>
                    <p class="d-padding-l5 b">Name</p>
                </div>
                <div class="d-column d-column-w35">
                    <p class="d-padding-l5">&nbsp;</p>
                    <p class="d-padding-l5 b">Userrole</p>
                </div>
                <div class="d-column d-column-w5">
                    <p class="d-padding-l5">&nbsp;</p>
                    <p class="d-padding-l5 b">All</p>
                </div>
            </div>
            
            <?php $d_row = 0; foreach ( $this->items as $item) : $d_row++;?>
        	<div class="title-sub-l heg30 d-content-items">
				<div class="d-column d-column-w30 text-long">
					<span class="d-padding-l5 p-t10"><?php echo $item->code; ?></span>
				</div>
                <div class="d-column d-column-w30 text-long">
					<span class="d-padding-l5 p-t10" title="<?php echo $item->name; ?>"><?php echo $item->name; ?></span>
				</div>
                <div class="d-column d-column-w35 text-long">
					<span class="d-padding-l5 p-t10" title="<?php echo $item->g_name; ?>"><?php echo $item->g_name; ?></span>					
				</div>
                <div class="d-column d-column-w5 text-long">
					<span class="d-padding-l5 p-t10" title="<?php echo $item->g_name; ?>">
                    <input type="checkbox" class="checkAll" data-row="<?php echo $d_row;?>" />
					</span>
				</div>
                
			</div>
         <?php endforeach;?>
         
        </div><!--End d-column d-column1-->
        
        <div class="d-column d-column2 d-margin-t15 d-content-items">
        	<div class="d-padding-l5 b">Filter on airport code 
            <input type="text" name="filter_airport_code" id="filter_airport_code" 
            value="<?php echo $this->escape($this->state->get('filter.airport_code')); ?>" />
            <button type="submit">Filter</button>
            <button onclick="
            document.id('filter_code').value='';
            document.id('filter_name').value='';
            document.id('filter_g_name').value='';
            document.id('filter_airport_code').value='';
            this.form.submit();
            " type="button">Clear</button>
            </div>
            <div class="max-airport-code">
            
            	<div class="title-sub heg15 d-content-items">
                	<?php foreach  ( $this->airportcodes as $airportcode ): ?>                                
                	<div class="d-column colum-sub">
                    	<strong><?php echo $airportcode->code; ?></strong>
                    </div>
                    <?php endforeach;?>
                </div>
                <div class="title-sub heg15 d-content-items position-absolute" style="display:none;">
                	<?php foreach  ( $this->airportcodes as $airportcode ): ?>                                
                	<div class="d-column colum-sub">
                    	<strong><?php echo $airportcode->code; ?></strong>
                    </div>
                    <?php endforeach;?>
                </div>
                
                <?php $i_name = 0; 
				foreach ( $this->items as $ite) : 
				$i_name++;
				?>
                <div class="title-sub heg30 d-content-items data-row<?php echo $i_name;?>">
                    <?php foreach  ( $this->airportcodes as $airportcode ): 
						$j_name = 0;
					$arr_val = array('user_id' => $ite->id, 'group_id' => $ite->group_id, 'airline_id' => $ite->airline_id, 'airportcode' => $airportcode->id);
					$strVal = str_replace('"',"'", json_encode( $arr_val ) );
					?>
                    <div class="d-column colum-sub">
                    	<span class="d-padding-l5 p-t10">
                       <input name="airports_code[<?php echo $airportcode->id . '_' . $i_name;?>]" value="<?php echo $strVal;?>" type="checkbox" class="padding-b2" <?php echo(array_key_exists("k" . $ite->id.$ite->airline_id.$airportcode->id, $this->airline_airports) )? "checked=\"checked\"" :"";?> />
                       </span>
                    </div>
                    <?php endforeach;?>
                </div>
                <?php endforeach;?>
                
            </div><!--End max-airport-code-->
            
        </div><!--End d-column d-column2-->
        
    </div><!--End d-title-->
    

	<div>
		<input type="hidden" name="task" value="" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
<script>

	jQuery(function($){
		var st = setInterval(function(){
			var filter_airport_code = $('#filter_airport_code').val();
			$.get("<?php 
			$url = '../index.php?option=com_sfs&task=getchangeairport.shoinfo';
			echo JRoute::_($url, false);
			?>",{},function( data ){
				if( data != null && data.code != '' ){
					if( filter_airport_code != data.code && data.code != '' ){
						$('#filter_airport_code').val( data.code );
						$('#getchangeairport_id').val( data.id );
						$.get("<?php echo JRoute::_("../index.php?option=com_sfs&task=getchangeairport.updateinfo", false);?>");
						setTimeout(function(){
							$('#adminForm').submit();
						}, 100);
					}
				}
			},'json');
			
		}, 2000 );
		
		$( "#adminForm" ).scroll(function() {
			if( $( this ).scrollTop() > 50 ) {
				$('.position-absolute').css({'display':'block', 'position':'absolute', 'top': ($( this ).scrollTop()-40) + 'px'});
			}
			else {
				$('.position-absolute').css({'display':'none'});
			}
		});
		
		$('.checkAll').click(function(e) {
			var d_row = $(this).attr("data-row");
            if( $(this).is(":checked") ){
				$('.data-row' + d_row).find("input").attr("checked", true);
			}
			else {
				$('.data-row' + d_row).find("input").attr("checked", false);
			}
        });
		
	});
	

</script>

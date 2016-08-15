<?php
defined('_JEXEC') or die;
?>

<div class="col w80 pull-left p20">
	<div class="form-group">
		<label>Shuttle service available</label>
		<div class="col w60">
			<div class="row">
				<div class="col w20">
					<div class="row">
						<div class="radio">
							<label><input type="radio" value="1" name="transport_available"<?php echo ( is_object($this->transport) && $this->transport->transport_available==1 ) ? ' checked="checked"' : ''; ?> /> Yes</label>				
						</div>
					</div>
				</div>
				<div class="col w20 ">
					<div class="row">
						<div class="radio">
							<label><input type="radio" value="0" name="transport_available"<?php echo ( is_object($this->transport) && $this->transport->transport_available==0 ) ? ' checked="checked"' : ''; ?> /> No</label>
						</div>
					</div>
				</div>
				<div class="col w60">
					<div class="radio">
						<label><input type="radio" value="2" name="transport_available"<?php echo ( is_object($this->transport) && $this->transport->transport_available==2 ) ? ' checked="checked"' : ''; ?> /> Not necessary<small class="help-block text-right">(walking distance)</small></label> 				
					</div>
				</div>
			</div>
		</div>		
	</div>

	<div class="form-group">
		<label>Complimentary</label>
		 <div class="col w60">            
            <div class="row">
	            <div class="radio">
	            	<label style="margin-left: 11px;"><input type="radio" value="1" name="transport_complementary"<?php echo ( is_object($this->transport) && $this->transport->transport_complementary==1 ) ? ' checked="checked"' : ''; ?> /> Yes</label>
	            </div>
	            <div class="radio">
	            	<label style="margin-left: 11px;"><input type="radio" value="0" name="transport_complementary"<?php echo ( is_object($this->transport) && $this->transport->transport_complementary==0 ) ? ' checked="checked"' : ''; ?> /> No</label>
	            </div>
			</div><br />
			<p>If not complementary please indicate costs in the additional information field</p>
        </div>	
	</div>

	<div class="form-group">
		<div class="row r10">
			<div class="col w60 label-modified">
		    	<label>Operating hours of the shuttle service</label>	   
		    </div>

		    <div class="form-group">
		    	<div class="form-group">
			        <div class="row r10">
	            	    <div class="radio">
			        		<label><input type="radio" value="1" name="operating_hour"<?php echo ( is_object($this->transport) && $this->transport->operating_hour==1 ) ? ' checked="checked"' : ' checked="checked"'; ?> />
					24-24 for stranded</label>
						</div>
					</div>
				</div>
				<div class="wrap-col">		            
	            	<div class="row r10">
	            	    <div class="radio">
				            <label><input type="radio" name="operating_hour" value="2" <?php echo ( is_object($this->transport) && $this->transport->operating_hour==2 ) ? 'checked="checked"' : ''; ?> />
				            From:
				                <?php
				                    if( is_object($this->transport) && $this->transport->operating_opentime) {
				                        $sst_array = explode(':', $this->transport->operating_opentime );
				                        $selectTimeArray = SfsHelperDate::getSelect24TimeField($sst_array[0],$sst_array[1]);
				                    } else {
				                        $selectTimeArray = SfsHelperDate::getSelect24TimeField('6','00');
				                    }
				                ?>
				            </label>
			            </div>
			        </div>
					
					<div class="form-group" style="margin-left: 25px">
						<div class="col w20">
		                    <div class="label-modified"><label>HH:</label></div>
			                <div class="wrap-col"><select name="service_opentime_h" class="smaller-size"><?php echo $selectTimeArray[0]->html;?></select></div>
			            </div>
			            
			            <div class="col w20">
			            	<div class="label-modified"><label>MM:</label></div>
			            	<div class="wrap-col"><select name="service_opentime_m" class="smaller-size"><?php echo $selectTimeArray[1]->html;?></select></div>
			           	</div>
			            
			            <div class="col w20" style="width: 10%;">
		                      <p style="text-align: center; margin-top: 40px; margin-bottom: 0;"><strong>Till</strong> <i class="fa fa-arrow-right"></i></p>                      
		                </div>
						
						<div class="col w20">
							<?php
			                    if(is_object($this->transport) && $this->transport->operating_closetime) {
			                        $sst_array = explode(':', $this->transport->operating_closetime );
			                        $selectTimeArray = SfsHelperDate::getSelect24TimeField($sst_array[0],$sst_array[1]);
			                    } else {
			                        $selectTimeArray = SfsHelperDate::getSelect24TimeField('23','35');
			                    }
			                ?>
			                <div class="label-modified"><label>HH:</label></div>
			                <div class="wrap-col"><select name="service_closetime_h" class="smaller-size"><?php echo $selectTimeArray[0]->html;?></select></div>
			            </div>
						
						<div class="col w20">
			                <div class="label-modified"><label>MM:</label></div>
			                <div class="wrap-col"><select name="service_closetime_m" class="smaller-size"><?php echo $selectTimeArray[1]->html;?></select></div>
			            </div>
		            </div>
		        </div>
		        <div class="form-group">
	                <div class="row r10">
	                    <div class="radio">
							<label><input type="radio" name="operating_hour" value="0" <?php echo  is_object($this->transport) && $this->transport->operating_hour==0 ? 'checked="checked"' : ''; ?> />Not available</label>
						</div>
					</div>
				</div>			
		    </div>
		</div>
    </div>

    <div class="form-group">
		<label>Frequency of the shuttle service</label>		
		<div class="col w60">
			<div class="col w20">
				<p>Every</p>
			</div>
			<div class="col w30"><select name="frequency_service" class="smaller-size">
				<?php
				$i=0;
				while(  $i <= 60) :
				?>
					<option value="<?php echo $i;?>"<?php echo  is_object($this->transport) && $this->transport->frequency_service == $i ? ' selected="selected"': ''; ?>><?php echo $i < 10 ? '0'.$i : $i;?></option>
				<?php
				$i+=5;
				endwhile;?>
			</select></div>
			<div class="col w20">
				<p>Minutes</p>
			</div>
		</div>		
	</div>

	<div class="form-group">
		<label>
			Additional information
			<div style="font-size:10px; line-height:13px;">
			Include: Seat capacity, pick-up location, additional schedule information.
			</div>
		</label>
		
		<div class="col w60">
			<script type="text/javascript">
	
			window.addEvent('domready', function(){
			 var Limiter = new TextLimiter();
			 });
	
			</script>
			<textarea rows="12" cols="60" class="inputbox limiter" rel="6" name="pickup_details"><?php echo  is_object($this->transport) ? $this->transport->pickup_details : ''; ?></textarea>
			<div id="counter_div"></div>
			<button type="button" onclick="Joomla.submitbutton('hotelprofile.preview')" class="btn orange sm"><?php echo JText::_('COM_SFS_PREVIEW_SAMPLE_VOUCHER') ?></button>		        			

		</div>		
	</div>
</div>



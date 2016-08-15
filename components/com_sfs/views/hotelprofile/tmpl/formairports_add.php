<?php
defined('_JEXEC') or die;
?>
<div id="additional-airport" style="padding:20px" class="floatbox">
<div id="additional-airport-box" >

	<div style="float:left;">
		<h3 style="font-size:20px; margin:0;">Add new Airport Code:</h3>
	</div>
	<div style="width:100px; float:right; text-align:right">
		<a onclick="window.parent.SqueezeBox.close();" style="cursor:pointer;">Close</a>
	</div>            			
	            			
	<div class="floatbox" style="clear:both;padding:20px">						       		       						    
        <div style="margin-bottom:5px;overflow:hidden; clear:both;font-size:13px;">
            <div style="width:170px;overflow:hidden;float:left;margin-right:20px;">
           	   <strong>
					<?php
                    if( $this->aIndex > 0 ){
                        echo SfsHelper::addOrdinalNumberSuffix( (int)$this->aIndex + 1 );
                    }
                    ?>
                    Nearest Airport Code :
                </strong>
            </div>                         
            <?php echo SfsHelperField::getAirportField('airport['.$this->aIndex.'][code]', $this->airport->airport_id, $this->hotel->country_id,'style="width:200px;padding:3px;border:solid 1px #909bb1;"');?>                            
        </div>
        <div style="margin-bottom:5px;overflow:hidden; clear:both;font-size:13px;">
            <div style="width:170px;overflow:hidden;float:left;margin-right:20px;">
                <?php echo JText::_('COM_SFS_DISTANCE_AIRPORT'); ?> :
            </div>
            <input type="text" value="<?php echo $this->airport->distance;?>" name="airport[<?php echo $this->aIndex; ?>][distance]" class="validate-numeric thin-size" style="width:130px;" />            
            <select name="airport[<?php echo $this->aIndex; ?>][distance_unit]" class="inputbox smaller-size" style="width:58px;padding:3px;border:solid 1px #909bb1;">
				<option value="km" selected="selected">Km</option>
				<option value="mi">M</option>
			</select>            
        </div>
    	<input type="hidden" name="airport[<?php echo $this->aIndex; ?>][id]" value="<?php echo (int) $this->airport->id;?>" />
    </div>
</div>    
</div>
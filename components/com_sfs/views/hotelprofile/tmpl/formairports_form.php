<?php
defined('_JEXEC') or die;
?>
<div class="block border orange clearfix">
    <?php if( $this->aIndex > 0 ):?>
        <legend><span class="text_legend"><?php echo JText::_("COM_SFS_HOTEL_OPTIONAL");?></span></legend>
    <?php else:?>
        <legend><span class="text_legend"><?php echo JText::_("COM_SFS_HOTEL_LOCATION");?></span></legend>
    <?php endif;?>
    <div class="col w80 pull-left p20">
        <?php if($this->aIndex ==0 ) :?>
        
        <div class="form-group">
        	<label><?php echo JText::_('COM_SFS_HOTEL_LOCATION');?> :</label>
            <div class="col w60">
                <?php
                    echo SfsHelperField::getHotelLocationField('hotel_location',$this->hotel->location_id);
                ?>
            </div>
        </div>
        <?php endif; ?>
        
        <div class="form-group">
            <label>
           	   <strong>
    				<?php
                    if( $this->aIndex > 0 ){
                        echo SfsHelper::addOrdinalNumberSuffix( (int)$this->aIndex + 1 );
                    }
                    ?>
                    Nearest Airport Code :
                </strong>
            </label>
            <div class="col w60">                      
                <?php 
                    
                    if($this->aIndex ==0 ) {
                        //echo SfsHelperField::getAirportField('airport['.$this->aIndex.'][code]', $this->airport->airport_id, $this->hotel->country_id,'class="change-value-distance select-airport'.$this->aIndex.' required validate-custom-required emptyValue:0"');
                        echo SfsHelperField::getAirportField('airport['.$this->aIndex.'][code]', $this->airport->airport_id, 0,'class="change-value-distance select-airport'.$this->aIndex.' required validate-custom-required emptyValue:0"');
                    }
                    if($this->aIndex ==1){
                        //echo SfsHelperField::getAirportField('airport['.$this->aIndex.'][code]', $this->airport->airport_id, $this->hotel->country_id, 'class="select-airport'.$this->aIndex);
                        echo SfsHelperField::getAirportField('airport['.$this->aIndex.'][code]', $this->airport->airport_id, 0, 'class="select-airport'.$this->aIndex);
                    }
                    if($this->aIndex ==2){
                        //echo SfsHelperField::getAirportField('airport['.$this->aIndex.'][code]', $this->airport->airport_id, $this->hotel->country_id, 'class="select-airport'.$this->aIndex);
                        echo SfsHelperField::getAirportField('airport['.$this->aIndex.'][code]', $this->airport->airport_id, 0, 'class="select-airport'.$this->aIndex);
                    }
                ?>
            </div>          
        </div>
        
        <div class="form-group">
            <label><?php echo JText::_('COM_SFS_DISTANCE_AIRPORT'); ?> :</label>
            <div class="col w60">
                <div class="row r10">
                    <div class="col w30">
                        <input id="airport[<?php echo $this->aIndex; ?>][distance]" type="text" value="<?php echo $this->airport->distance;?>" name="airport[<?php echo $this->aIndex; ?>][distance]" class="distance-unit<?php echo $this->aIndex; ?> validate-numeric thin-size<?php if( $this->aIndex ==0 || $this->airport->id > 0 ) echo ' required';?>"  />
                    </div>
                    <div class="col w30">
                        <?php echo JHTML::_('select.genericlist', $this->length_unit, 'airport['.$this->aIndex.'][distance_unit]', 'class="inputbox smaller-size"', 'value', 'text',$this->airport->distance_unit); ?>
                    </div>
                </div>
            </div>
        </div>
        
        <?php if ( $this->aIndex == 0 ) :?>
        <!--Lchung-->
        <div class="form-group">
            <label style="margin-top:20px;">
           	   <strong>
    				
                   Geolocation :
                </strong>
            </label>
            <div class="col w60">
                <div class="col w30" style="padding-left:0px;">
                	<label style="margin-top:0px;"><?php echo JText::_('Lat');?> :</label>
                    <input type="text" value="<?php echo $this->hotel->geo_location_latitude;?>" name="geo_location_latitude" id="geo_location_latitude<?php echo ( $this->aIndex > 0 ) ? $this->aIndex : "0";?>" class="validate-numeric "  />
                </div>
                <div class="col w30">
                	<label style="margin-top:0px;"><?php echo JText::_('Lon');?> :</label>
                    <input type="text" value="<?php echo $this->hotel->geo_location_longitude;?>" name="geo_location_longitude" id="geo_location_longitude<?php echo ( $this->aIndex > 0 ) ? $this->aIndex : "0";?>" class="validate-numeric"  />
                    
                </div>
                <label style="margin:0px; width:auto; margin-top:30px;">
               
                <a class="find-hotel-on-map" data-id="<?php echo ( $this->aIndex > 0 ) ? $this->aIndex : "0";?>" style="text-decoration:underline; cursor:pointer;">Find hotel on map</a>
                 </label>
            </div>          
        </div>
        <!--End lchung-->
        <?php endif;?>
        <input type="hidden" name="airport[<?php echo $this->aIndex; ?>][id]" value="<?php echo (int) $this->airport->id;?>" />
    </div>
</div>

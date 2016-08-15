<?php
defined('_JEXEC') or die;
?>
<div class="heading-block descript clearfix">
	<div class="heading-block-wrap">
		<h3><?php echo $this->hotel->name.' - Airports'; ?></h3>
	</div>
</div>
<div id="sfs-wrapper" class="fs-14 main">
	<div class="sfs-main-wrapper-none">
	<div class="sfs-orange-wrapper">
	
	<?php
	$i = 0;
	foreach ($this->airports as $airport) :
		$i++;
	?>
		<div class="sfs-white-wrapper floatbox" <?php echo $i < count($this->airports) ? 'style="margin-bottom:25px;"':'';?>>
			<div class="hotel-airports">			
					
				<?php if( $i == 1 ) : ?>
					<div class="sfs-row">
						<div class="field-label sfs-column-left">
							<?php echo JText::_('COM_SFS_HOTEL_LOCATION');?> :
						</div>
					<?php
						echo SfsHelperField::getHotelLocationName($this->hotel->location_id);
					?>
					</div>
				<?php endif;?>
				
		        <div class="sfs-row">
		            <div class="field-label sfs-column-left">
		           	   <strong>
							<?php
		                    if( $i > 1 ){
		                        echo SfsHelper::addOrdinalNumberSuffix( (int)$i );
		                    }
		                    ?>
		                    Nearest Airport Code :
		                </strong>
		            </div>	
		             <?php echo SfsHelperField::getAirportName( $airport->airport_id )?>
		                                                   
		        </div>
		        <div class="sfs-row">
		            <div class="field-label sfs-column-left">
		                <?php echo JText::_('COM_SFS_DISTANCE_AIRPORT'); ?> :
		            </div>
		            <?php echo $airport->distance.' '.$airport->distance_unit;?>
		            
		        </div>
		        <div class="sfs-row">
		            <div class="field-label sfs-column-left">
		                <?php echo JText::_("COM_SFS_DRIVING_TIME_TO_AIRPORT");?> :
		            </div>
		            <span class="fs-12">no separators allowed</span>
		        </div>
		        <div class="sfs-row">
		            <div class="field-label sfs-column-left" style="text-align:right">normal:</div>
		            <?php echo $airport->normal_hours;?>
		            <?php echo JText::_("MINUTES");?>
		        </div>
		        <div class="sfs-row">
		            <div class="field-label sfs-column-left" style="text-align:right">rush hours:</div>
		            <?php echo $airport->rush_hours;?>
		            <?php echo JText::_("MINUTES");?>
		        </div>			
			 
			 </div>
		 </div>
	<?php endforeach;?>			
	
	</div>
	</div>

	<div class="sfs-below-main">
		<div class="s-button float-left">
			<a href="<?php echo JRoute::_( SfsHelperRoute::getSFSRoute('hotelprofile') );?>" class="s-button">
				<?php echo JText::_('COM_SFS_BACK');?>
			</a>
		</div>
		<div class="s-button float-right">
			<a href="<?php echo JRoute::_('index.php?option=com_sfs&view=hotelprofile&layout=formairports&Itemid='.JRequest::getInt('Itemid'));?>" class="s-button">
				<?php echo JText::_('COM_SFS_EDIT');?>
			</a>
		</div>
		
	</div>
	<div class="clear"></div>


</div>
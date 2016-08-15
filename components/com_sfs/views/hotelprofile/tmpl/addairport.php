<?php
defined('_JEXEC') or die;
JHtml::_('behavior.mootools');
?>

<script type="text/javascript">
<!--	
	window.addEvent('domready', function(){
		var hotelRegisterForm = document.id('hotelRegisterForm');
		var hotelRegisterFormValidate = new Form.Validator(hotelRegisterForm); 

	});		
//-->
</script>

<div id="sfs-wrapper">
<div style="width:400px; float:left;"><h3>Add new Airport Code:</h3></div><div style="width:100px; float:right; text-align:right"><a onclick="window.parent.SqueezeBox.close();" style="cursor:pointer;">Close</a></div>

<div id="hotel-registraion" class="sfs-wrapper airports">

	<form id="hotelRegisterForm" action="<?php echo JRoute::_('index.php?option=com_sfs&view=hotelprofile'); ?>" method="post">
    <div class="airport-fields" style="padding-left: 100px;">
    
        <div class="hotel-register-row">
            <div class="field-label">
           	   <strong>
                    <?php echo JText::sprintf('COM_SFS_AIRPORT_CODE', '<sup class="orderstr">'.$orderStr.'</sup>');?> :
                </strong>
            </div>                         
            <?php 
            	echo SfsHelperField::getAirportField('airport[0][code]', 0, $this->hotel->country_id,'class="required validate-custom-required emptyValue:0"',true);
            ?>                            
        </div>
        
        <div class="hotel-register-row">
            <div class="field-label">
                <?php echo JText::_('COM_SFS_DISTANCE_AIRPORT'); ?> :
            </div>
            <input type="text" value="" name="airport[0][distance]" class="required validate-interger thin-size"  />
            <select name="airport[0][distance_unit]" class="inputbox smaller-size" size="1">
				<option value="km" selected="selected">Km</option>
				<option value="mi">M</option>
			</select>
        </div>
        
        <div class="hotel-register-row">
            <div class="field-label">
                <?php echo JText::_("COM_SFS_DRIVING_TIME_TO_AIRPORT");?> :
            </div>
            <small><?php echo JText::_('COM_SFS_AIRPORT_NO_SEPARATORS');?></small>
        </div>
        
        <div class="hotel-register-row">
            <div class="field-label" style="text-align:right">normal:</div>
            <input type="text" name="airport[0][normal_hours]" class="required validate-interger smaller-size" value="" >
            <?php echo JText::_("COM_SFS_MINUTES");?>
        </div>
        <div class="hotel-register-row">
            <div class="field-label" style="text-align:right">rush hours:</div>
            <input type="text" name="airport[0][rush_hours]" class="required validate-interger smaller-size" value="" />
            <?php echo JText::_("COM_SFS_MINUTES");?>
        </div>
    
        <input type="hidden" name="airport[0][id]" value="0" />
    </div>
    
	    <div class="s-button float-right">            	   		
	   		<button type="submit" class="s-button">Save &amp; Close</button>        
	    </div>
        <input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid');?>">
        <input type="hidden" name="tmpl" value="component">                
        <input type="hidden" name="task" value="hotelprofile.saveAirports" />
        <?php echo JHtml::_('form.token'); ?>
    </form>    
    
</div>
</div>
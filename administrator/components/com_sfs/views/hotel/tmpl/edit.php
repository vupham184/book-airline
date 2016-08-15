<?php
// No direct access.
defined('_JEXEC') or die;

// Load the tooltip behavior.
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
JHtml::_('behavior.modal');

?>

<script type="text/javascript">
    Joomla.submitbutton = function(task) {
        if (task == 'hotel.cancel' || document.formvalidator.isValid(document.id('item-form'))) {
            Joomla.submitform(task, document.getElementById('item-form'));
        } else {
            alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
        }
    }

    jQuery(function( $ ){     

        $('.editCurrency').click(function(event) {
            $('.editCurrency').remove();
            $('.showEditCurrency').css('display','block');
        });

        $('#taxcurrency_id').on('change', function(){
            var hotel_id = $('#taxcurrency_id').val();
            var html = '<input type="hidden" value="'+hotel_id+'" name="address[currency_id]">'
            $('.addressCurrency').html(html);
        });
    });
</script>
<style type="text/css">
    select#taxcurrency_id{width: 120px;}
</style>

<div class="width-100" style="margin-top:15px;">

    <fieldset class="adminform">

        <?php if( isset($this->createdUser) && (int)$this->createdUser->block == 1 && $this->createdUser->activation ) : ?>

        <?php else: ?>

            <div class="" style="overflow:hidden;float:left;padding:10px 0 0;margin:0 0 0 25px; width:auto;">
                <h3 style="padding:0px 0px 10px 0;margin:0;">
                    <?php if( empty($this->todayInventory) ) : ?>
                        The hotel did not loaded the rooms today.
                    <?php else: ?>
                        The hotel loaded the rooms today.
                    <?php endif;?>
                </h3>

                <button type="button" onclick="Joomla.submitbutton('hotel.inventoryNotification')">Send</button>
            </div>

            <div style="overflow:hidden;float:left;padding:10px 0 0;margin:0 0 0 25px; font-size:16px;">
                <?php

                $airlineSent =SfsHelper::getAirlineSendNotification($this->item->id);
                if(count($airlineSent) > 0 ) :
                    ?> <?php foreach ($airlineSent as $u ): ?>
                    Invited hotel by <?php echo $u->airline_alliance;?> username <a target="_blank" href="<?php echo JRoute::_('index.php?option=com_users&task=user.edit&id=' . $u->uid); ?>">
                        <span class="icon-32-options"> </span>
                        <?php echo $u->username;?>
                    </a> <?php echo $u->airline_alliance;?> has a contract with the hotel <br />
                <?php endforeach;?>
                <?php else :
                    $sentUsers =SfsHelper::getSendNotification($this->item->id);
                    if(count($sentUsers)) :
                        ?>
                        <?php foreach ($sentUsers as $u ): ?>
                        <?php echo $u->name;?> sent at <?php echo JHtml::_('date',$u->date, JText::_('DATE_FORMAT_LC2'));?><br />
                    <?php endforeach;?>
                    <?php endif;?>
                <?php endif;?>
            </div>

        <?php endif;?>

    </fieldset>

</div>

<form action="<?php echo JRoute::_('index.php?option=com_sfs&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="item-form" class="form-validate">
<div class="width-60 fltlft">
    <fieldset class="adminform">
        <legend>Hotel Details</legend>
        <ul class="adminformlist">
            <li>
                <label for="hotel-name">Hotel Name:</label>
                <input type="text" size="40" name="address[name]" id="hotel-name" class="required" value="<?php echo $this->item->name;?>">
            </li>

    <div class="" style="overflow:hidden;float:left;padding:10px 0 0;margin:0 0 0 25px; width:auto;">
        <h3 style="padding:0px 0px 10px 0;margin:0;">
            <?php if( empty($this->todayInventory) ) : ?>
            The hotel did not loaded the rooms today.
            <?php else: ?>
            The hotel loaded the rooms today.
            <?php endif;?>
        </h3>
            <li>
                <label>Star</label>
                <select name="address[star]" class="inputbox">
                    <?php echo JHtml::_('select.options', SfsHelper::getStarOptions(), 'value', 'text', $this->item->star);?>
                </select>
            </li>
            <li>
                <label>Chain affiliation:</label>
                <input type="text" readonly="readonly" class="readonly" size="22" value="<?php echo $this->item->chain_id ? SfsHelperField::getChainName($this->item->chain_id):'none';?>">
            </li>
            <li>
                <label>Created Date:</label>
                <input type="text" size="30" value="<?php echo JHTML::_('date',$this->item->created_date, JText::_('DATE_FORMAT_LC2')); ?>" readonly="readonly" class="readonly">
            </li>

            <li>
                <label for="jform_block">Block this Hotel</label>
                <fieldset class="radio" id="jform_block">
                    <input type="radio" <?php if($this->item->block==0) echo 'checked="checked"';?> value="0" name="block" id="jform_block0"><label for="jform_block0">No</label>
                    <input type="radio" <?php if($this->item->block==1) echo 'checked="checked"';?> value="1" name="block" id="jform_block1"><label for="jform_block1">Yes</label>
                </fieldset>
            </li>
            
          <!--  <li><label for="jform_block"><?php //echo JText::_('Currency');?></label><?php //echo SfsHelperField::getCurrencyField('address[currency_id]', $this->item->currency_id); ?></li>  --> 


    <div style="overflow:hidden;float:left;padding:10px 0 0;margin:0 0 0 25px; font-size:16px;">
    <?php
    
    $airlineSent =SfsHelper::getAirlineSendNotification($this->item->id);
    if(count($airlineSent) > 0 ) :
    ?> <?php foreach ($airlineSent as $u ): ?>  
        Invited hotel by <?php echo $u->airline_alliance;?> username <a target="_blank" href="<?php echo JRoute::_('index.php?option=com_users&task=user.edit&id=' . $u->uid); ?>">

    <span class="icon-32-options"> </span>
    <?php echo $u->username;?>
    </a> <?php echo $u->airline_alliance;?> has a contract with the hotel <br />
                <?php endforeach;?>
        <?php else :
        $sentUsers =SfsHelper::getSendNotification($this->item->id);
        if(count($sentUsers)) :
        ?>
            <?php foreach ($sentUsers as $u ): ?>                
                <?php echo $u->name;?> sent at <?php echo JHtml::_('date',$u->date, JText::_('DATE_FORMAT_LC2'));?><br />
            <?php endforeach;?>
        <?php endif;?>
    <?php endif;?>
    </div>
            <li>
                <label>Hotel ID</label>
                <input name="id" type="text" readonly="readonly" class="readonly" size="10" value="<?php echo $this->item->id;?>">
            </li>
        </ul>
        <div class="clr"></div>
        <div style="border-top:solid 1px #CCCCCC;padding-top:10px;">
            <div style="margin-bottom: 10px;font-size: 1.091em;">
                Hotel Admins:
                <?php
                $i = 0;
                foreach ($this->admins as $admin) :
                    ?>
                    <a href="index.php?option=com_users&task=user.edit&id=<?php echo $admin->user_id?>"><?php echo $admin->username;?></a>
                    <?php
                    if( $i < (count($this->admins) - 1) ) echo ' , ';
                    $i++;
                endforeach;
                ?>
            </div>
            <div class="clr"></div>
            <a rel="{handler: 'iframe', size: {x: 600, y: 440}, onClose: function() {}}" href="<?php echo JRoute::_('index.php?option=com_sfs&view=hotel&layout=newadmin&tmpl=component&id='.$this->item->id);?>" class="modal icon-16-user" style="background-position: left 50%;background-repeat: no-repeat;color: #333333; padding-left: 25px;">Add New Hotel Admin</a>
        </div>
    </fieldset>

    <fieldset class="adminform">
        <?php echo $this->loadTemplate('address');?>
    </fieldset>



    <fieldset class="adminform">
        <?php echo $this->loadTemplate('billing');?>
    </fieldset>

    <fieldset class="adminform">
        <?php echo $this->loadTemplate('merchantfee');?>
    </fieldset>

</div>
<?php $contacts = $this->item->getContacts();?>
<div class="width-40 fltrt">

<?php echo JHtml::_('sliders.start','panel-sliders',array('useCookie'=>'0')); ?>


<?php echo JHtml::_('sliders.panel', 'Hotel registered by', 'hotel-panel-maincontact'); ?>
<div>
    <fieldset class="adminform" style="margin-bottom:8px;padding-bottom:0;">
        <ul class="adminformlist">
            <li>
                <label for="">Username:</label>
                <a href="index.php?option=com_users&task=user.edit&id=<?php echo $contacts[(int)$this->item->created_by]->user_id;?>" target="_blank">
                    <input type="text" size="30" value="<?php echo $contacts[(int)$this->item->created_by]->username;?>" readonly="readonly" class="readonly" style="color:#146295;cursor:pointer">
                </a>
            </li>
            <li>
                <label for="">Job title:</label>
                <input type="text" size="30" value="<?php echo $contacts[(int)$this->item->created_by]->job_title;?>" readonly="readonly" class="readonly">
            </li>
            <li>
                <label for="">Gender:</label>
                <input type="text" size="30" value="<?php echo $contacts[(int)$this->item->created_by]->gender;?>" readonly="readonly" class="readonly">
            </li>
            <li>
                <label for="">Name:</label>
                <input type="text" size="30" value="<?php echo $contacts[(int)$this->item->created_by]->name;?>" readonly="readonly" class="readonly">
            </li>
            <li>
                <label for="">Surname:</label>
                <input type="text" size="30" value="<?php echo $contacts[(int)$this->item->created_by]->surname;?>" readonly="readonly" class="readonly">
            </li>
            <li>
                <label for="">Email:</label>
                <input type="text" size="30" value="<?php echo $contacts[(int)$this->item->created_by]->email;?>" readonly="readonly" class="readonly">
            </li>
            <li>
                <label for="">Direct office telephone:</label>
                <input type="text" size="30" value="<?php echo $contacts[(int)$this->item->created_by]->telephone;?>" readonly="readonly" class="readonly">
            </li>
            <li>
                <label for="">Direct fax:</label>
                <input type="text" size="30" value="<?php echo $contacts[(int)$this->item->created_by]->fax;?>" readonly="readonly" class="readonly">
            </li>
            <li>
                <label for="">Mobile:</label>
                <input type="text" size="30" value="<?php echo $contacts[(int)$this->item->created_by]->mobile;?>" readonly="readonly" class="readonly">
            </li>
        </ul>

        <div class="clr"></div>
    </fieldset>
</div>


<?php echo JHtml::_('sliders.panel', 'Hotel Localtion', 'hotel-panel-location'); ?>

<div>
    <fieldset class="adminform" style="margin-bottom:8px;padding-bottom:0;">
        <ul class="adminformlist">
            <li>
                <label>Hotel Location:</label> <input type="text" size="50" class="readonly" readonly="readonly" value="<?php echo SfsHelperField::getHotelLocationName($this->item->location_id );?>" />
            </li>
            <li>
                <label>Latitude:</label> <input type="text" name="address[geo_location_latitude]" size="50" value="<?php echo $this->item->geo_location_latitude;?>" />
            </li>
            <li>
                <label>Longitude:</label> <input type="text" name="address[geo_location_longitude]" size="50" value="<?php echo $this->item->geo_location_longitude;?>" />
            </li>
        </ul>
        <div class="clr"></div>
    </fieldset>
    <table class="adminlist">
        <thead>
        <tr>
            <th><strong>Nearest Airport</strong></th>
            <th><strong>Distance</strong></th>
            <th><strong>Driving time</strong></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($this->servicing_airports as $value) : ?>
            <tr>
                <td><?php echo $value->airport_code.', '.$value->airport_name;?></td>
                <td class="center"><?php echo $value->distance.' '.$value->distance_unit; ?></td>
                <td>
                    <table>
                        <tr>
                            <td>Normal hours:</td><td><?php echo $value->normal_hours; ?></td>
                        </tr>
                        <tr>
                            <td>Rush hours:</td><td><?php echo $value->rush_hours; ?></td>
                        </tr>
                    </table>
                </td>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div>

<?php echo JHtml::_('sliders.panel', 'Hotel Rooms', 'hotel-panel-room'); ?>
<fieldset class="adminform" style="margin-bottom:8px;padding-bottom:0;">
    <ul class="adminformlist">
        <li>
            <label>Total number of rooms:</label> <input type="text" size="5" class="readonly" readonly="readonly" value="<?php echo $this->hotel_room->total;?>" />
            <label>Number of standard rooms:</label> <input type="text" size="5" class="readonly" readonly="readonly" value="<?php echo $this->hotel_room->standard;?>" />
            <label>Average size standard rooms:</label> <input type="text" size="50" class="readonly" readonly="readonly" value="<?php echo $this->hotel_room->standard_size.' '.$this->hotel_room->standard_size_unit ;?>" />
        </li>
    </ul>
    <div class="clr"></div>
</fieldset>


<?php echo JHtml::_('sliders.panel', 'Hotel Taxes', 'hotel-panel-taxes'); ?>
<fieldset class="adminform" style="margin-bottom:8px;padding-bottom:0;">
    <ul class="adminformlist">
        <div class="showEditCurrency" style="display: none;">
            <li><label for="jform_block"><?php echo JText::_('Currency');?></label><?php echo SfsHelperField::getCurrencyField('tax[currency_id]', $this->taxes->currency_id); ?></li>
            <div class="addressCurrency"></div>
            <hr style="clear:both;" />
        </div>
        <li class="editCurrency">
            <label>Currency</label>
            <input type="text" size="50" class="readonly" readonly="readonly" value="<?php echo $this->taxes->currency_name;?>" />
            <a class="editCurrency" href="javascript:void(0);" >Edit</a>
          
            <hr style="clear:both;" />
        </li>
        <li>
            <label>Percent total taxes per night</label>
            <input type="text" size="10"  value="<?php echo $this->taxes->percent_total_taxes;?>" name="tax[percent_total_taxes]" />
        </li>
        <li>
            <label>Os fee per night</label>
            <input type="text" size="10"  value="<?php echo $this->taxes->os_fee_per_night;?>" name="tax[os_fee_per_night]" /></li>
        <li>
            <label>Os fee per stay</label>
            <input type="text" size="10" value="<?php echo $this->taxes->os_fee_per_stay ;?>" name="tax[os_fee_per_stay]" />
        </li>
        <li>
            <label>free release percentage</label>
            <span style="float: left;margin: 5px 5px 5px 0;width: auto;"><?php echo (int)$this->taxes->percent_release_policy;?>%</span>
            <a class="modal" rel="{handler: 'iframe', size: {x: 300, y: 250}, onClose: function() {}}" style="float: left;margin: 5px 5px 5px 0;width: auto;" href="index.php?option=com_sfs&view=hotel&tmpl=component&layout=freerelease&id=<?php echo $this->item->id;?>">Edit</a>
        </li>

    </ul>
    <div class="clr"></div>
</fieldset>
<?php $mealplan = $this->item->getMealPlan();?>
<?php echo JHtml::_('sliders.panel', '  Hotel F B mealplans', 'hotel-panel-mealplans'); ?>
<table class="adminlist">
    <thead>
    <tr>
        <th colspan="2"><strong>Dinner information</strong></th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td width="40%">Price layover meals 1 course:</td>
        <td><?php echo $mealplan->course_1; ?></td>
    </tr>
    <tr>
        <td>Price layover meals 2 course:</td>
        <td><?php echo $mealplan->course_2; ?></td>
    </tr>
    <tr>
        <td>Price layover meals 3 course:</td>
        <td><?php echo $mealplan->course_3; ?></td>
    </tr>
    <tr>
        <td>Quoted menu prices are:</td>
        <td><?php echo $mealplan->quoted_menu_price; ?></td>
    </tr>
    <tr>
        <td>Taxes that are applicable for the above prices:</td>
        <td>
            <input type="text" size="10" value="<?php echo $mealplan->tax ; ?>" name="fb[tax]" />
        </td>
    </tr>
    <tr>
        <td>Stop selling time for the restaurant:</td>
        <td><?php echo $mealplan->stop_selling_time ; ?></td>
    </tr>
    <tr>
        <td>Restaurant is open on days:</td>
        <td>
            <?php

            if( ! empty($mealplan->available_days) ){
                $day_array = array(
                    1 => JText::_('MON'),
                    2 => JText::_('TUE'),
                    3 => JText::_('WED'),
                    4 => JText::_('THU'),
                    5 => JText::_('FRI'),
                    6 => JText::_('SAT'),
                    7 => JText::_('SUN')
                );
                $available_days = explode(',', $mealplan->available_days);
                JArrayHelper::toInteger($available_days);
                foreach ($available_days as $day){
                    echo $day_array[$day].' ';
                }

            } else {
                echo 'Not available all days';
            }
            ?>
        </td>
    </tr>
    </tbody>
</table>

<table class="adminlist">
    <thead>
    <tr>
        <th colspan="2"><strong>Lunch information</strong></th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td width="40%">Standard price lunch per person:</td>
        <td><?php echo $mealplan->lunch_standard_price ; ?></td>
    </tr>
    <tr>
        <td>Taxes that are applicable for the above prices:</td>
        <td>
            <input type="text" size="10" value="<?php echo $mealplan->lunch_tax ; ?>" name="fb[lunch_tax]" />
        </td>
    </tr>

    <tr>
        <td>Regular opening hours for the lunch service:</td>
        <td>
            <?php echo $mealplan->lunch_service_hour == 1 ? '24 Hrs': $mealplan->lunch_opentime.' till '.$mealplan->lunch_closetime ; ?>
        </td>
    </tr>
    <tr>
        <td>Restaurant is open on days:</td>
        <td>
            <?php

            if( ! empty($mealplan->lunch_available_days) ){
                $day_array = array(
                    1 => JText::_('MON'),
                    2 => JText::_('TUE'),
                    3 => JText::_('WED'),
                    4 => JText::_('THU'),
                    5 => JText::_('FRI'),
                    6 => JText::_('SAT'),
                    7 => JText::_('SUN')
                );
                $available_days = explode(',', $mealplan->lunch_available_days);
                JArrayHelper::toInteger($available_days);
                foreach ($available_days as $day){
                    echo $day_array[$day].' ';
                }

            } else {
                echo 'Not available all days';
            }
            ?>
        </td>
    </tr>
    </tbody>
</table>

<table class="adminlist">
    <thead>
    <tr>
        <th colspan="2"><strong>Breakfast information</strong></th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td width="40%">Standard price breakfast per person:</td>
        <td><?php echo $mealplan->bf_standard_price ; ?></td>
    </tr>
    <tr>
        <td>Price layover breakfast per person</td>
        <td><?php echo $mealplan->bf_layover_price  ; ?></td>
    </tr>
    <tr>
        <td>Taxes that are applicable for the above prices:</td>
        <td>
            <input type="text" size="10" value="<?php echo $mealplan->bf_tax ; ?>" name="fb[bf_tax]" />
        </td>
    </tr>
    <tr>
        <td>Regular opening hours:</td>
        <td><?php echo $mealplan->bf_opentime ; ?></td>
    </tr>
    <tr>
        <td>Outsite regular opening:</td>
        <td><?php echo $mealplan->bf_outside  ; ?></td>
    </tr>
    </tbody>
</table>


<?php echo JHtml::_('sliders.panel', 'Hotel Administrator Settings', 'hotel-administrator-params'); ?>
<fieldset class="adminform" style="margin-bottom:8px;padding-bottom:0;">
    <ul class="adminformlist">
        <li>
            <label>Hotel is located in the following Ring:</label>
            <select name="hotel_ring">
                <option value="1"<?php echo (int)$this->adminSetting->ring == 1 ? ' selected="selected"':'';?>>1</option>
                <option value="2"<?php echo (int)$this->adminSetting->ring == 2 ? ' selected="selected"':'';?>>2</option>
                <option value="3"<?php echo (int)$this->adminSetting->ring == 3 ? ' selected="selected"':'';?>>3</option>
                <option value="4"<?php echo (int)$this->adminSetting->ring == 4 ? ' selected="selected"':'';?>>4</option>
            </select>
        </li>
        <li>
            <label>Second hotel fax for copy of bookings:</label> <input type="text" name="second_fax" size="50" class="inputbox" value="<?php echo $this->adminSetting->second_fax;?>" />
        </li>
        <li>
            <label>Hotel may be invited by airline:</label>
            <fieldset class="radio">
                <input type="radio" name="hotel_invited" value="0"<?php echo (int)$this->adminSetting->hotel_invited == 0 ? ' checked="checked"':'';?>><label>No</label>
                <input type="radio" name="hotel_invited" value="1"<?php echo (int)$this->adminSetting->hotel_invited == 1 ? ' checked="checked"':'';?>><label>Yes</label>
            </fieldset>
        </li>
        <li>
            <label>Enable Single Room:</label>
            <fieldset class="radio">
                <input type="radio" name="single_room_available" value="0"<?php echo (int)$this->adminSetting->single_room_available == 0 ? ' checked="checked"':'';?>><label>No</label>
                <input type="radio" name="single_room_available" value="1"<?php echo (int)$this->adminSetting->single_room_available == 1 ? ' checked="checked"':'';?>><label>Yes</label>
            </fieldset>
        </li>
        <li>
            <label>Enable Quad Room:</label>
            <fieldset class="radio">
                <input type="radio" name="quad_room_available" value="0"<?php echo (int)$this->adminSetting->quad_room_available == 0 ? ' checked="checked"':'';?>><label>No</label>
                <input type="radio" name="quad_room_available" value="1"<?php echo (int)$this->adminSetting->quad_room_available == 1 ? ' checked="checked"':'';?>><label>Yes</label>
            </fieldset>
        </li>
    </ul>
    <div class="clr"></div>
</fieldset>

<?php echo JHtml::_('sliders.panel', 'Hotel Webservice', 'hotel-webservice-params'); ?>
<fieldset class="adminform" style="margin-bottom:8px;padding-bottom:0;">
    <ul class="adminformlist">
        <li>
            <label>Webservice Source:</label>
            <input type="text" value="<?php echo $this->item->ws_type;?>" disabled="disabled"/>
        </li>
        <li>
            <label>Webservice #ID:</label>
            <input type="text" value="<?php echo $this->item->ws_id;?>" disabled="disabled" />
        </li>
    </ul>
    <div class="clr"></div>
</fieldset>



<?php echo JHtml::_('sliders.end');?>
</div>

<div class="clr"></div>

<div>
    <input type="hidden" name="airport" value="<?php echo $this->item->airport_id ?>">
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="return" value="<?php echo JRequest::getCmd('return');?>" />
    <input type="hidden" name="tmpl" value="<?php echo JRequest::getVar('tmpl')?>" />
    <?php echo JHtml::_('form.token'); ?>
</div>

</form>


<?php
defined('_JEXEC') or die;
$have_block_code= (isset($this->block_code))?1:0;
$status = $have_block_code ? $this->block_code->status : null;
$read_only='';
if( isset($this->block_code) && !in_array($status, array('P', 'O') ) ) {
    $read_only='readonly="readonly"';
}
$is_saved = JRequest::getInt('is_save');
#print_r($this->block_code);
?>
<script>
    jQuery.noConflict();
    jQuery(document).ready(function(){
        
        //lchung
        jQuery(".input-vouchernumber").keyup(function(e) {
            var valOld = jQuery(this).val();
            var valNew = '';
            for ( var ii = 0; ii < valOld.length; ii++) {
                if ( valOld[ii] != 'O' && valOld[ii] != 'o' && valOld[ii] != 0  ) {
                    valNew += valOld[ii].toUpperCase();
                }
            }
            jQuery(this).val( valNew );
        });
        //End lchung
        
        jQuery(".input-vouchernumber").change(function(){
            
            var index = parseInt(jQuery(this).attr("name").match(/\d+/));
            var voucher_code = jQuery(this).val();
            var thisObj = this;
            var thisInput = jQuery(this);
            
            <?php if($have_block_code == 1 && count($this->items)):?>
                var passengers = <?php echo json_encode($this->items)?>;
                var jsons = [];
                jQuery.each(passengers, function(i, item)
                {
                    if(voucher_code == passengers[i].code)
                    {
                        jsons.push(passengers[i]);
                    }
                });
                var filled = false;
                jQuery('.input-vouchernumber').each(function(){
                    if(this != thisObj && jQuery(this).val() == voucher_code) {
                        filled = true;
                    }
                });
                if(!filled) {
                    jQuery.each(jsons, function(i, item)
                    {
                        var passenger = jsons[i];
                        jQuery('input[name^="vouchers['+(i + index)+'][voucher_number]').val(voucher_code);
                        jQuery('input[name^="vouchers['+(i + index)+'][voucher_number]').removeClass("inputbox-red").addClass("inputbox-green");
                        jQuery('input[name^="vouchers['+(i + index)+'][first_name]"]').val(passenger.first_name);
                        jQuery('input[name^="vouchers['+(i + index)+'][last_name]"]').val(passenger.last_name);
                    });
                } else if(jsons.length) {
                    jQuery(this).removeClass("inputbox-red").addClass("inputbox-green");
                } else {
                    jQuery(this).removeClass("inputbox-green").addClass("inputbox-red");
                }

                if(jsons.length) {
                    jQuery(this).removeClass("inputbox-red").addClass("inputbox-green");
                    if(!filled) {
                        jQuery.each(jsons, function(i, item)
                        {
                            var passenger = jsons[i];
                            jQuery('input[name^="vouchers['+(i + index)+'][voucher_number]').val(voucher_code);
                            jQuery('input[name^="vouchers['+(i + index)+'][voucher_number]').removeClass("inputbox-red").addClass("inputbox-green");
                            jQuery('input[name^="vouchers['+(i + index)+'][first_name]"]').val(passenger.first_name);
                            jQuery('input[name^="vouchers['+(i + index)+'][last_name]"]').val(passenger.last_name);
                        });
                    }
                } else {                   
                    jQuery(this).removeClass("inputbox-green").addClass("inputbox-red");
                    jQuery('input[name^="vouchers['+jQuery(this).index()+'][first_name]"]').val("");
                    jQuery('input[name^="vouchers['+jQuery(this).index()+'][last_name]"]').val("");
                }

            <?php else:?>
                var block_code = jQuery("#blockcode").val();
                var checkURL = '<?php echo "http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}&tmpl=component&task=rooming.getPassengersByVoucher&voucher_code=";?>' + voucher_code+"&block_code="+block_code;
                jQuery.ajax({
                    url: checkURL,
                    dataType: 'json',
                    success: function(passengers){
                        if(!passengers.error)
                        {
                            var filled = false;
                            jQuery('.input-vouchernumber').each(function(){
                                if(this != thisObj && jQuery(this).val() == voucher_code) {
                                    filled = true;
                                }
                            });

                            thisInput.removeClass("inputbox-red").addClass("inputbox-green");

                            if(!filled) {
                                jQuery.each(passengers, function(i, item)
                                {
                                    jQuery('input[name^="vouchers['+(i + index)+'][voucher_number]"]').val(voucher_code);
                                    jQuery('input[name^="vouchers['+(i + index)+'][voucher_number]"]').removeClass("inputbox-red").addClass("inputbox-green");
                                    jQuery('input[name^="vouchers['+(i + index)+'][first_name]"]').val(passengers[i].first_name);
                                    jQuery('input[name^="vouchers['+(i + index)+'][last_name]"]').val(passengers[i].last_name);
                                });
                            }
                        }
                        else {
                            thisInput.removeClass("inputbox-green").addClass("inputbox-red");
                        }
                    }
                });
            <?php endif;?>
        })
    });
</script>
<div class="fs-16"><?php echo JText::_('COM_SFS_ROOMING_INSERT_MANUALLY');?></div>

<div class="rooming-block">

    <div class="fs-14"><?php echo JText::_('COM_SFS_ROOMING_INSERT_MANUALLY_DESC');?></div>



            <label><?php echo JText::_('COM_SFS_BLOCK_CODE')?> :</label> <input type="text" name="blockcode" <?php echo $read_only;?> value="<?php echo isset($this->block_code) ? $this->block_code->blockcode : JRequest::getVar('code');?>" class="inputbox" id="blockcode" />


        <div style="padding:20px 100px 0 100px;">
            <table cellpadding="0" cellspacing="0" width="100%" class="roomingtable">
                <tr>
                    <th>#</th>
                    <th><?php echo JText::_('COM_SFS_ROOMING_VOUCHER_NUMBER');?></th>
                    <th><?php echo JText::_('COM_SFS_FIRST_NAME');?></th>
                    <th><?php echo JText::_('COM_SFS_SURNAME');?></th>
                </tr>
                <?php
                if( isset($this->block_code) && !in_array($status, array('P', 'O', 'T', 'C')) ) {
                    $read_only='readonly="readonly"';
                }
                $i = 0;
                if(count($this->items_saved)) :

                        foreach ($this->items_saved as $item) :
                            $value_voucher_number =  $item->code;
                            $value_first_name = $item->first_name;
                            $value_last_name = $item->last_name;
                            ?>
                                <tr class="<?php echo ($i%2) ? 'odd':'even';?>">
                                    <td><input type="hidden" name="vouchers[<?php echo $i;?>][id]" value="<?php echo $item->id;?>"/><?php echo $i+1;?></td>
                                    <td><input type="text" <?php echo $read_only;?> name="vouchers[<?php echo $i;?>][voucher_number]" class="input-vouchernumber inputbox" value="<?php echo $value_voucher_number?>"/></td>
                                    <td><input type="text" <?php echo $read_only;?> name="vouchers[<?php echo $i;?>][first_name]" class="inputbox" value="<?php echo $value_first_name?>"/></td>
                                    <td><input type="text" <?php echo $read_only;?> name="vouchers[<?php echo $i;?>][last_name]" class="inputbox" value="<?php echo $value_last_name?>"/></td>
                                </tr>
                        <?php
                            $i++;
                        endforeach;

                endif;
                ?>

                <?php

                $voucher_count = isset($this->block_code) && $this->guest_count ? $this->guest_count : 10;
                $app = JFactory::getApplication();
                while( $i < $voucher_count) :
                        ?>
                        <tr class="<?php echo ($i%2) ? 'odd':'even';?>">
                            <td><input type="hidden" name="vouchers[<?php echo $i;?>][id]" value="0" /><?php echo $i+1;?></td>
                            <td><input type="text" name="vouchers[<?php echo $i;?>][voucher_number]" value="" class="inputbox input-vouchernumber" /></td>
                            <td><input type="text" name="vouchers[<?php echo $i;?>][first_name]" value="" class="inputbox" /></td>
                            <td><input type="text" name="vouchers[<?php echo $i;?>][last_name]" value="" class="inputbox" /></td>
                        </tr>
                <?php
                    $i++;
                endwhile;
                $app->setUserState('com_sfs.rooming.data',null);

                ?>

            </table>

            <?php
            if ( ! empty($this->guaranteeVoucher) ) :
            ?>
            <div class="midpaddingtop">
                <div class="fs-16 midmarginbottom midmargintop">
                    Your minimum guarantee voucher number
                </div>
                <table cellpadding="0" cellspacing="0" width="100%" class="roomingtable">
                    <tr>
                        <th>#</th>
                        <th><?php echo JText::_('COM_SFS_ROOMING_VOUCHER_NUMBER');?></th>
                        <th><?php echo JText::_('COM_SFS_FIRST_NAME');?></th>
                        <th><?php echo JText::_('COM_SFS_SURNAME');?></th>
                    </tr>
                    <?php

                    for ($i=0; $i < (int)$this->guaranteeVoucher->rooms; $i++) :
                        if( isset($this->guaranteeIssuedVouchers[$i]) ) {
                            $extraClass = ' inputbox-green';
                        } else {
                            $extraClass = '';
                        }
                    ?>
                    <tr class="<?php echo ($i%2) ? 'odd':'even';?>">
                        <td><?php echo $i+1;?></td>
                        <td><input type="text" name="guaranteeVouchers[<?php echo $i;?>][voucher_number]" value="<?php echo isset($this->guaranteeIssuedVouchers[$i]) ? $this->guaranteeIssuedVouchers[$i] : '';?>" class="inputbox<?php echo $extraClass;?>" /></td>
                        <td><input type="text" name="guaranteeVouchers[<?php echo $i;?>][first_name]" value="No show" readonly="readonly" class="inputbox inputbox2" /></td>
                        <td><input type="text" name="guaranteeVouchers[<?php echo $i;?>][last_name]" value="No show" readonly="readonly" class="inputbox inputbox2" /></td>
                    </tr>
                    <?php endfor;?>
                </table>
            </div>
            <?php endif;?>

            <div style="padding-top:10px;">
                <?php if ( strlen( $read_only ) == 0 ) : ?>
                <button type="button" onclick="roomingsubmit('rooming.save')" class="btn orange sm" >
                    <?php echo JText::_('JSAVE') ?>
                </button>
                <?php endif;?>
                <div style="display:none;">
                    <div id="send-rooming-list">
                        <p class="fs-16"><?php echo JText::_('COM_SFS_ROOMING_IMPORTANT_MESSAGE') ?>:</p>

                        <p class="fs-14">
                            <?php echo JText::_('COM_SFS_ROOMING_SENDING_CONFIRM_TO_AIRLINE') ?>: <?php echo $this->airline->name;?>
                        </p>

                        <p class="fs-14">
                            <?php echo JText::_('COM_SFS_ROOMING_CONFIRM_DESC') ?>
                        </p>

                        <p class="fs-16">Additional message for <?php echo $this->airline->name;?> about this block:</p>

                        <div class="floatbox fs-14" style="padding:0px 30px 0;">
                            <div style="padding:0 0 0 5px;">Dear <?php echo $this->bookedAirlineContact->gender?> <?php echo $this->bookedAirlineContact->name?>,</div>
                            <textarea style="width:500px;height: 150px;border:solid 1px #ccc;padding:10px;"></textarea>
                            <div style="padding:5px 0 0 5px;">With best regards,<br /><?php echo $this->user->name;?></div>
                        </div>

                        <div class="floatbox" style="padding-top:50px;">
                            <button onclick="window.SqueezeBox.close();" type="button" class="small-button float-left"><?php echo JText::_('COM_SFS_BACK') ?></button>
                            <button type="button" onclick="roomingsubmit('rooming.confirm')" class="small-button float-left" style=" margin-left:20px;" ><?php echo JText::_('COM_SFS_CONFIRM') ?></button>
                        </div>

                    </div>

                    <div id="send-rooming-list-warning">
                        <p class="fs-16">Warning!</p>
                        <p class="fs-14">
                             You have not inserted all the rooms in this roomblock, are you sure you want to send this roomblock to the Airline?
                        </p>

                        <p class="fs-16">Additional message for <?php echo $this->airline->name;?> about this block:</p>

                        <div class="floatbox fs-14" style="padding:0px 30px 0;">
                            <div style="padding:0 0 0 5px;">Dear <?php echo $this->bookedAirlineContact->gender?> <?php echo $this->bookedAirlineContact->name?>,</div>
                            <textarea style="width:500px;height: 150px;border:solid 1px #ccc;padding:10px;"></textarea>
                            <div style="padding:5px 0 0 5px;">With best regards,<br /><?php echo $this->user->name;?></div>
                        </div>

                        <div class="floatbox" style="padding-top:40px;">

                            <div class="mid-button float-left" style="margin-left: 40px;">
                                <button style="text-indent:22px;" onclick="window.SqueezeBox.close();" type="button">No, I have some more vouchers</button>
                            </div>

                            <div class="mid-button float-right" style="margin-right: 40px;">
                                <button type="button" onclick="roomingsubmit('rooming.confirm')" style="text-indent:22px;margin-left:20px;" >Yes, I want to send it anyway </button>
                            </div>
                        </div>

                    </div>



                </div>
            </div>
        </div>


</div>
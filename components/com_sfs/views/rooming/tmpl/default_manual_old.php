<?php
defined('_JEXEC') or die;
$read_only='';
if( isset($this->block_code) && $this->block_code->status!='P'&&$this->block_code!='O' ) {
	$read_only='readonly="readonly"';
}
?>
<div style="font-size:16px;">Option 2: Insert manually</div>
<div style="padding:30px;">
    <div>
        Type your stranded passengers names in below input fields and press the upload button below. This data will be seen approved number of rooms of the hotel.
    </div>
    <div style="font-size:15px;">
        <br /><br />
        Block code : <input type="text" name="blockcode" <?php echo $read_only;?> value="<?php echo isset($this->block_code) ? $this->block_code->blockcode : JRequest::getVar('code');?>" class="inputbox" />
        <br /><br />
        
        <div style="padding:0 100px 0 100px;">
            <table cellpadding="0" cellspacing="0" width="100%" class="roomingtable">
                <tr>
                    <th>#</th><th>First name</th><th>Last name</th><th>Voucher number</th>                                
                </tr>
                <?php
                if( isset($this->block_code) && $this->block_code->status!='P' && $this->block_code->status!='O' && $this->block_code->status!='T' && $this->block_code->status!='C' ) {
					$read_only='readonly="readonly"';
				}else {
					$read_only='';
				}                
                $i = 0;
                if(count($this->items)) :	                            
                    foreach ($this->items as $item) :                    	             
	                    ?>
	                        <tr class="<?php echo ($i%2) ? 'odd':'even';?>">
	                            <td>
	                                <input type="hidden" name="vouchers[<?php echo $i;?>][id]" value="<?php echo $item->id;?>" />
	                                <?php echo $i+1;?>
	                            </td>
	                            <td><input type="text" <?php echo $read_only;?> name="vouchers[<?php echo $i;?>][first_name]" value="<?php echo $item->first_name;?>" class="inputbox" /></td>
	                            <td><input type="text" <?php echo $read_only;?> name="vouchers[<?php echo $i;?>][last_name]" value="<?php echo $item->last_name;?>" class="inputbox" /></td>
	                            <td><input type="text" <?php echo $read_only;?> name="vouchers[<?php echo $i;?>][voucher_number]" value="<?php echo $item->voucher_number;?>" class="inputbox" /></td>                                                                
	                        </tr>                                 	
                        <?php                      
                        $i++;                       
                    endforeach;
                endif;?>
                
                <?php 
                $voucher_count = isset($this->block_code) ? (int)$this->guest_count : 10;
                $app = JFactory::getApplication();
                $voucherDataFromSess = $app->getUserState('com_sfs.rooming.data');    
                                        
                while( $i < $voucher_count) :
                    
                    if(isset($voucherDataFromSess) && count($voucherDataFromSess) && !count($this->items)) :?>
                    <tr class="<?php echo ($i%2) ? 'odd':'even';?>">
                        <td><input type="hidden" name="vouchers[<?php echo $i;?>][id]" value="<?php echo $voucherDataFromSess[$i]['id'];?>" /><?php echo $i+1;?></td>
                        <td><input type="text" name="vouchers[<?php echo $i;?>][first_name]" value="<?php echo $voucherDataFromSess[$i]['first_name'];?>" class="inputbox" /></td>
                        <td><input type="text" name="vouchers[<?php echo $i;?>][last_name]" value="<?php echo $voucherDataFromSess[$i]['last_name'];?>" class="inputbox" /></td>
                        <td><input type="text" name="vouchers[<?php echo $i;?>][voucher_number]" value="<?php echo $voucherDataFromSess[$i]['voucher_number'];?>" class="inputbox" /></td>                                                                
                    </tr>	                            
                    <?php
                    else : 	                            	                           
                    ?>
                    <tr class="<?php echo ($i%2) ? 'odd':'even';?>">
                        <td><input type="hidden" name="vouchers[<?php echo $i;?>][id]" value="0" /><?php echo $i+1;?></td>
                        <td><input type="text" name="vouchers[<?php echo $i;?>][first_name]" value="" class="inputbox" /></td>
                        <td><input type="text" name="vouchers[<?php echo $i;?>][last_name]" value="" class="inputbox" /></td>
                        <td><input type="text" name="vouchers[<?php echo $i;?>][voucher_number]" value="" class="inputbox" /></td>                                                                
                    </tr>                        
                    <?php
                    endif;
                    $i++;
                endwhile;
                ?>                 
            </table>
            <div style="padding-top:10px;">           
                <?php if ( strlen( $read_only ) == 0 ) : ?>
                <button type="button" onclick="roomingsubmit('rooming.save')" class="button" >
                    <?php echo JText::_('JSAVE') ?>
                </button>                       
                <?php endif;?>
                <div style="display:none;">
                    <div id="send-rooming-list">		        	                    				                    		
                        <h3>Important message:</h3>									
                        <p style="font-size:16px;">
                            <br />
                            You will be sending the rooming list to: <?php echo $this->airline->name;?>									
                        <br />
                        <br />
                        <br />
                            The number of vouchers will be used by the airline to determine the charge that they will recive from your hotel. Confirm that you have inserted all vouchers relating to this block code.
                        </p>									
                        <div class="floatbox" style="padding-top:10px;">									
                            <button type="button" onclick="roomingsubmit('rooming.confirm')" class="button" >Confirm</button>     										
                            <button onclick="window.SqueezeBox.close();" type="button" class="button float-left" style="margin-left:100px;">Back</button>									
                        </div>		                    		
                    </div>
                </div>                            
            </div>                                                
        </div>
                            
    </div>                
</div>
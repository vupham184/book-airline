<?php 
	$link_Img = JURI::root().'media/media/images/select-pass-icons';
 ?>

<div>
<a href="javascript:void(0);" class="sfs-button pull-right close-content" style="font: inherit; font-size: 14px; margin-top: -3px; margin-right: 5px">Close</a>
</div><br />
<div style="margin-left: 10px;" class="main-issue">	
    <div style="margin-left: 10px;">	
        <div class="issue_head">
        <h3>Issue vouchers</h3>
        </div>
        
        <div id="issue_top">
            <div class="top_left">
            <img src="<?php  echo $link_Img.'/group.png' ?>" alt="" width="25px"><br/>
            <div class="list-passenger"></div>
            
        </div><!--End issue_top-->

        <div class="top_center">
            <div class="center_on">
            <input type="text" id="email" value="<?php echo $this->item->email_address;?>" name="email" class="input_center">
            <!-- <button id="sendemailvoucher" class="button_issue">EMAIL VOUCHERS</button> -->
            <a id="sendemailvoucher" href="javascript:void(0);">EMAIL VOUCHERS</a>
            </div>
            <div class="center_down" style="position:relative;">
            <input type="text" id="tel" value="<?php echo $this->item->phone_number;?>" name="sms" class="input_center">
            <!-- <button  class="button_issue">SMS VOUCHERS</button> -->
            <a id="sendsmsvoucher" data-flight-number="<?php echo $this->item->rebooked_fltno->carrier . $this->item->rebooked_fltno->flight_no;?>" data-std="<?php echo $this->item->rebooked_fltno->std;?>" data-etd = "<?php echo $this->item->rebooked_fltno->etd;?>" data-passengers="<?php echo str_replace('"',"'", json_encode($arrInfo)) ; ?>" data-url-code="<?php echo $url_code;?>" onclick="sendsmsvoucher(this);" href="javascript:void(0);">SMS VOUCHERS</a>
            <span class="ajx-loading loading" style="display:none; position:absolute; left:150px; top:0px;">&nbsp;</span>
            
            
            
            </div>
        </div><!--End top_center-->
        
        <div class="info_service">
            <div class="info-service-header">
            <table class="tableInfo" cellpadding="0" cellspacing="0" border="0">
            <tr>
            <th width="40%">Service</th>
            <th width="10%">Status</th>
            <th width="40%">Details</th>
            <th width="10%">Details</th>
            </tr>
            </table>
            </div>
            <!-- Hotel -->
            
        	 <div id="service8" class="obj-service" style="display:none;">
				<?php echo $this->loadTemplate('list-service'); ?>
			</div>
			
            
        </div><!--End info_service-->
		
    </div><!--End margin-left: 10px; 2-->
</div><!--End main-issue-->
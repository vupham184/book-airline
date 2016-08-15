<?php 
$airline = SFactory::getAirline();
?>

<style type="text/css">
#main_specific{
    width: 90%;
    margin: 0 auto;
    padding: 0;

}

.logo, .infoVoucher, .headerVoucher, .infoVoucher_text{float:left; width: 100%;}
.infoVoucher{margin-top: 20px; font-weight: 600!important; font-size: 14px;}
label{float: left; width: 48%; font-size: 14px; }
.text_right{float: left; width: 50%; border: 1px solid #dfdfdf; padding: 8px; 
    text-align: center;box-shadow: 3px 3px 3px #666;;
}
.text_barcode{float: left;width: 50%;text-align: center;}
.text_barcode div{margin-left: 5%;}
.headerVoucher{font-size: 36px; font-weight: 600; padding:20px 0;}
.infoVoucher_text{margin-top: 20px;}
</style>

<?php
    $lang = JFactory::getLanguage();
    $extension = 'com_sfs';
    $base_dir = JPATH_SITE;
    $language_tag = 'de-EN';
    $reload = true;
    $lang->load($extension, $base_dir, $language_tag, $reload);
?>

<div class="heading-buttons" style="page-break-after:always;">
    <?php
    $t = 0;
    $rePrint = JRequest::getInt('reprint',0);
    if( $rePrint == 0 ) :
        ?>
        <a onclick="window.parent.SqueezeBox.close();" class="sfs-button float-right">Close</a>
        <a onclick="window.print();if(document.body.style.MozTransform==undefined){window.parent.SqueezeBox.close();};" class="sfs-button float-right">Print</a>
    <?php else : $t = 1; ?>
        <a onclick="self.close();" class="sfs-button float-right">Close</a>
        <a onclick="window.print();self.close()" class="sfs-button float-right">Print</a>
    <?php endif;?>
    
    <?php
    if(strstr($_SERVER['HTTP_USER_AGENT'],'iPhone') || strstr($_SERVER['HTTP_USER_AGENT'],'iPod') || strstr($_SERVER['HTTP_USER_AGENT'],'iPad')) :
    if( $t == 0 ):
    ?>
        <a onclick="self.close();" class="sfs-button float-right">Close</a>
        <a onclick="window.print();self.close()" class="sfs-button float-right">Print</a>
    <?php
    endif;
    endif;
    ?>
</div><br clear="all" />

<div id="main_specific">
    <div class="logo">    
        <img src="<?php echo JURI::root().'media/media/images/Logo_AB_for_Vouchers.png'; ?>" width="50%" style="float:right;">
    </div>
    <div class="headerVoucher">
        <?php echo JText::_('COM_INVITATION');?>/Invitation
    </div>
    <div class="infoVoucher">
        <label>
         <?php echo JText::_('COM_AIRBERLIN_INVITES');?> /<br />
        airberlin invites you to the amount of</label>
        <div class="text_right">
            <?php echo $this->dataPassengerService[0]->refreshment_amount[0] . ",- Euro"; ?>
        </div>
    </div>
    <div class="infoVoucher">
        <label>
            <?php echo JText::_('COM_VALID_ON');?> /<br />
            valid on
        </label>
        <div class="text_right">
        <?php 
            $originalDate = $this->dataPassengerService[0]->flight_date;
            $newDate = date("d F Y", strtotime($originalDate));
            echo $newDate;
        ?>
            <!-- <?php //echo $this->dataPassengerService[0]->flight_date; ?> -->
        </div>
    </div>
    <div class="infoVoucher">
        <label>
             <?php echo JText::_('COM_VALID_AIRPORT');?> /<br />
            valid on airport
        </label>
        <div class="text_right">
            <?php echo $this->dataPassengerService[0]->dep; ?>
        </div>
    </div>
    <div class="infoVoucher">
        <label>
            <?php echo JText::_('COM_FLIGHT');?> /<br />
            flight number
        </label>
        <div class="text_right">
            <?php echo $this->dataPassengerService[0]->carrier.''.$this->dataPassengerService[0]->flight_no; ?>
        </div>
    </div>
    <div class="infoVoucher">
        <label>
            <?php echo JText::_('COM_BILLING');?>/<br />
            billing address
        </label>
        <div class="text_barcode">
        Air Berlin PLC & Co. Luftverkehrs KG • <br />
        Saatwinkler Damm 42-43 • 13627 Berlin</div>
    </div>
    <div class="infoVoucher">
        <label>Reference code</label>
        <div class="text_barcode">
        <?php if( (int)$airline->params['specific_services_enabled'] == 1) : ?>
            <?php include_once(JPATH_ROOT.DS.'components'.DS.'com_sfs'.DS.'libraries'.DS.'tcpdf'.DS.'tcpdf_barcodes_1d.php');

                foreach ($this->dataPassengerService[0]->services as $k => $val) {
                    if( (int)$val->service_id == 2){
                        $codeBlock = $val->block_code;
                        $w=1; $h=30; $color=array(0,0,0);
                        $code = $codeBlock; 
                        $type="C128";
                        $data = new TCPDFBarcode($code, $type);
                        $bar_code = $data->getBarcodeHTML($w,$h,'black');
                        echo $bar_code;
                    }
                }

               
           ?>
           <span style="font-weight:100; font-size:11px;"><?php echo $codeBlock; ?></span>
        <?php else: ?>
            <span style="font-weight:600; font-size:12px;">
                <?php 
                    foreach ($this->dataPassengerService[0]->services as $k => $val) {
                        if( (int)$val->service_id == 2){                            
                            echo $val->block_code;
                        }
                    }
                ?>
            </span>
        <?php endif; ?>
            
       </div>
        
    </div>
    <div class="infoVoucher_text">
        <?php echo JText::_('COM_PLEASE_HAND');?> <br />
        Please hand over this voucher to your waiter when ordering. <br /><br />
        <?php echo JText::_('COM_THIS_VOUCHER');?> <br />
        This voucher is valuable for your purchases in restaurants on named airport and on board of named flight only on the valid day. The voucher in only valid on flights airberlin sales. Please be advised, there will be no sales on board of flights inside Germany.<br /><br />
        <?php echo JText::_('COM_CASH_PAY');?> <br />
        Cash pay out is not possible.<br /><br />
        <?php echo JText::_('COM_VOUCHER_WILL');?> <br />
        Voucher will not be accepted without reference code.<br />
        
    </div>
</div>
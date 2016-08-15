<?php 

//single spe

$airline = SFactory::getAirline();
$airline_current = SAirline::getInstance()->getCurrentAirport();
$date = SfsHelperDate::getDate('now', "d F Y", $airline_current->time_zone);
$time = SfsHelperDate::time('now', $airline_current->time_zone);

$flag_taxi_train = array(); //this is flag for service taxi or train if this exist

$flag_taxi_train['Train'] = (int)$this->dataPassengerService[0]->to_trainstation;

$flag_taxi_train['Taxi'] = (int)$this->dataPassengerService[0]->taxi_id;
$flag_bus = (int)$this->dataPassengerService[0]->gtc_id;
$hotel_name = $this->dataPassengerService[0]->hotel_name;
$gtc_company_name = $this->dataPassengerService[0]->gtc_company_name;
// echo "<pre>";
// print_r($airline);
// echo "</pre>";
?>

<style type="text/css">
.main_specific{
    width: 90%;
    margin: 0 auto;
    padding: 0;

}
 .refreshment, .hotel,.taxi-gtc,.bus-gtc{float:left; width: 100%;}
.logo, .infoVoucher, .headerVoucher, .infoVoucher_text{float:left; width: 100%;}
.infoVoucher{margin-top: 20px; font-weight: 600!important; font-size: 14px;}
label{float: left; width: 48%; font-size: 14px; }
.text_right{float: left; width: 50%; border: 1px solid #dfdfdf; padding: 8px; 
    text-align: center;/*box-shadow: 3px 3px 3px #666;*/
}
.text_barcode{float: left;width: 50%;text-align: center;}
.text_barcode div{margin-left: 5%;}
.headerVoucher{font-size: 36px; font-weight: 600; padding:20px 0;}
.infoVoucher_text{margin-top: 20px; font-size: 14px;}
.titleHeader{float:left; width: 80%;}
.titleImage{float:left; width: 20%;}

.rentalcar{
    float: left;
    width: 90%;
    margin-left: 40px;
}

.hotel{
    padding-top: 50px;
}
.taxi-gtc,.bus-gtc{
    width: 60%;
    margin-left: 20%;
    padding-top: 50px;
}
@media print {
    .heading-buttons{display:none;}
    #Taxi,#Train,.bus-gtc{height: 500px;}
    .refreshment,.hotel,.rentalcar,#Taxi,#Train,.bus-gtc {  page-break-inside: avoid; }
}
.add-box-shadow {
    box-shadow: 2px 2px 1px #888888;
}
.infoVoucher .add-info-staff{
    width: 100%;
    height: 100px;
    border: 1px solid #000;
}
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
        <a onclick="window.print();if(document.body.style.MozTransform==undefined){window.parent.SqueezeBox.close();};" class="sfs-button float-right" >Print</a>
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
</div><div style="clear:both;"></div>
<?php foreach ($this->dataPassengerService[0]->services as $key => $value) :
    if( (int)$value->service_id == 2) :
?>

<?php foreach ($this->dataPassengerService as $k => $valPasenger) :?>
    <?php if(isset($valPasenger->passenger_id) ): ?>


<div class ="main_specific">
<div class ="refreshment">
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
        <div class="add-box-shadow text_right">
            <?php echo $valPasenger->refreshment_amount . ",- Euro"; ?>
        </div>
    </div>
    <div class="infoVoucher">
        <label>
            <?php echo JText::_('COM_VALID_ON');?> /<br />
            valid on
        </label>
        <div class="add-box-shadow text_right">
        <?php 
            $originalDate = $valPasenger->flight_date;
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
        <div class="add-box-shadow text_right">
            <?php echo $valPasenger->dep; ?>
        </div>
    </div>
    <div class="infoVoucher">
        <label>
            <?php echo JText::_('COM_FLIGHT');?> /<br />
            flight number
        </label>
        <div class="add-box-shadow text_right">
            <?php echo $valPasenger->carrier.''.$valPasenger->flight_no; ?>
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

                foreach ($valPasenger->services as $k => $val) {
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
                    foreach ($valPasenger->services as $k => $val) {
                        if( (int)$val->service_id == 2){                            
                            echo $val->block_code;
                        }
                    }
                ?>
            </span>
        <?php endif; ?>
            
       </div>
        
    </div>
    <div  class="infoVoucher">
        <p style="width:250px">Name of issuing staff and company (stamp and signature):</p>
        <div class="add-box-shadow add-info-staff"></div>
    </div>
    <div class="infoVoucher_text">
        <?php echo JText::_('COM_PLEASE_HAND');?> <br />
        Please hand over this voucher to your waiter when ordering. <br /><br />
        <?php echo JText::_('COM_THIS_VOUCHER');?> <br />
        This voucher is valuable for your purchases in restaurants on named airport and on board of named flight only on the valid day. The voucher in only valid on flights airberlin sales. Please be advised, there will be no sales on board of flights inside Germany.<br /><br />
        <?php echo JText::_('COM_CASH_PAY');?> <br />
        Cash pay out is not possible.<br /><br />
        <?php echo JText::_('COM_VOUCHER_WILL');?> <br />
        Voucher will not be accepted without company stamp and signature.<br />
        
    </div>
</div>
</div>
<?php endif; endforeach; ?>
<?php endif; endforeach; ?>

<?php foreach ($this->dataPassengerService[0]->services as $key => $value) :
    if( (int)$value->service_id == 6) :
?>
<div style="clear:both;"></div>
<div class ="main_specific">
<div class ="rentalcar">
    <div class="logo">    
        <img src="<?php echo JURI::root().'media/media/images/Logo_AB_for_Vouchers.png'; ?>" width="50%" style="float:right;">
    </div>
    <div class="headerVoucher">
        <span class="titleHeader">
            <?php echo JText::_('COM_MOBILYTI');?>/Mobility voucher
        </span>
        <span class="titleImage">
            <img src="<?php echo JURI::root().$this->dataForRental->logo; ?>" width="80%">
        </span>        
    </div>
    <div class="headerVoucher" style="font-size: 14px;">
        <?php echo JText::_('COM_WE_OFFER_YOU');?>/We offer you complementary car hire
    </div>
    <div class="infoVoucher">
        <label>
         <?php echo JText::_('COM_NAME_PASSENGER');?> /<br />
        Name of passengers</label>
        <div class="add-box-shadow text_right" style="text-align:left;">
            <?php foreach ($this->dataPassengerService as $key => $valRentalCar) {
                if(isset($valRentalCar->passenger_id)){
                    echo '<pre>'.$valRentalCar->title.'. '.$valRentalCar->first_name.' '.$valRentalCar->last_name.'</pre>';
                }                
            } ?>
        </div>
    </div>
    <div class="infoVoucher">
        <label>
            <?php echo JText::_('COM_DATE');?> /<br />
            Date
        </label>
        <div class="add-box-shadow text_right">
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
             <?php echo JText::_('COM_PICKUP_LOCATION');?> /<br />
            Rental pick-up location
        </label>
        <div class="add-box-shadow text_right">
            <?php echo $this->dataForRental->location[0]->code; ?>
        </div>
    </div>
    <div class="infoVoucher">
        <label>
            <?php echo JText::_('COM_RETURN_LOCATION');?> /<br />
            Rental return location
        </label>
        <div class="add-box-shadow text_right">
            <?php echo $this->dataForRental->location[1]->code; ?>
        </div>
    </div>
    <div class="infoVoucher">
        <label>
            <?php echo JText::_('COM_FLIGHT');?> /<br />
            Flight number
        </label>
        <div class="add-box-shadow text_right">
            <?php echo $this->dataPassengerService[0]->carrier.''.$this->dataPassengerService[0]->flight_no; ?>
        </div>
    </div>
    
    <div class="infoVoucher">
        <label>Vouchercode</label>
        <div class="text_barcode">
        <?php if( (int)$airline->params['specific_services_enabled'] == 1) : ?>
            <?php include_once(JPATH_ROOT.DS.'components'.DS.'com_sfs'.DS.'libraries'.DS.'tcpdf'.DS.'tcpdf_barcodes_1d.php');

                foreach ($this->dataPassengerService[0]->services as $k => $val) {
                    if( (int)$val->service_id == 6){
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
        <?php echo JText::_('COM_VEHICLE_SIZE');?>/Vehicle size
        <p>
            CDMR (Hertz) bzw CLMR (Sixt) (CDP 713764 / HCC 26501792 9992) <br />
            SX Agentur-Nummer/Agency No; 593115; <br/>
            SX CD Nummer/CD-No. 9939466
        </p>
    </div>
    <div class="infoVoucher_text" style="font-size:8px!important">
        <?php echo JText::_('COM_TEXT_RENTAL_CAR');?> <br />                
    </div>
    <div class="infoVoucher_text" style="font-size:8px!important">
        Information for passengers:<br />
This voucher entitles you to hire a car from either of our car hire partners, Hertz or Sixt, on a single occasion and for a period of 24 hours from Start of hire. You will be entering into a contract of hire with one of these partners. This voucher pays for the car hire contract. The voucher only applies to cars collected from and returned to a location in Germany.<br /><br />
The following terms and conditions apply:<br /><br />
To be able to enter into a contract of hire you will need to show your driver’s license, as well as a credit card or EC card. Your credit card or EC card will be debited with at least EUR 160.00 as deposit when you hire the car. This amount will be re-credited to your credit card or EC card when you return the vehicle, unless the car hire partner is entitled to further Claims (e.g. if you have failed to fill the tank with fuel). The deposit will be increased if you take out additional insurance or choose other optional extras. For further Information please ask at the car hire Station when you hire the vehicle. You will have comprehensive insurance cover with an excess of EUR 850.00. Additional insurance (e.g. personal accident insurance or reduced excess) and other optional extras (e.g. child seat, Tyres suitable for winter, snow chains, ski rack) must be paid for in advance when you hire the car.<br />
You must return the vehicle to the relevant partner’s car hire Station at the specified destination within 24 hours of the Start of the rental period. You can return the vehicle outside the station’s office hours by depositing the car keys in a return safe specifically designed for this purpose. If you fail to return the vehicle within 24 hours, the relevant car hire partner will Charge you for each additional period of 24 hours or part thereof, at the rate shown on the car hire partner’s price list valid when you collected the vehicle. For details about current rates, please ask at the car hire partner’s Station when you collect the car.<br />
Please return the hire car to the relevant car hire partner’s Station at your destination with a full tank of fuel. We will refund the fuel costs for the journey between the point of the car’s collection and return, provided that you submit the receipt to the address shown below:<br /><br />

Air Berlin PLC & Co. Luftverkehrs KG<br />
Kundenservice/Customer Service<br />
Saatwinkler Damm 42-43<br />
D-13627 Berlin<br /><br />

This also applies if you were on a flight operated by another airline within the airberlin Group.
If the vehicle is not returned with a full tank of fuel, the car hire partner will Charge you for tne costs of filling up with fuel on the basis of the currently valid price list. For details about these costs please ask at the car hire partner’s Station when you collect the car. We hope you will understand that airberlin is not able to refund those charges. The car hire is subject to the relevant partner’s general conditions of hire. These will be displayed at the partner’s Station and can be viewed there. This voucher Is only valid on the date of Issue, as well as on the following day, and only if presented by the person named on the voucher. This voucher is not transferable. Miles will not be created for this voucher.
              
    </div>
</div>
</div>
<?php endif; endforeach; ?>
<?php 

$tpl_issue_voucher = JRequest::getInt('tpl_issue_voucher', 0 );

if ((int)$this->dataPassengerService[0]->hotel_id > 0 && $tpl_issue_voucher==0): ?>
    <?php echo $this->loadTemplate('single'); ?>
<?php else:

        $this->list_vouchers=array();
    foreach ($this->dataPassengerService as $key => $value) {
        if($value->voucher_id){            
            $this->list_vouchers[$value->voucher_id][] = $value;
        }
    }
?>
    <style type="text/css">
        .main_specific{
            font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
        }

    </style>
    <?php 
    foreach ($this->list_vouchers as $key => $value) {
        $this->detail_voucher = $this->listvoucher[$key];
        $this->voucher_current = $value;
        echo $this->loadTemplate('issue-voucher');     
    }
endif; ?>

<?php foreach ($flag_taxi_train as $key => $value): ?>
    <?php if ($value > 0): ?>
    <br clear="all" />
    <div class ="main_specific">
        <div id = "<?php echo $key; ?>" class ="taxi-gtc">
            <div class="logo">    
                <img src="<?php echo JURI::root().'media/media/images/Logo_AB_for_Vouchers.png'; ?>" width="50%" style="float:right;">
            </div>
                <h3><?php echo $key; ?></h3>
                <p>
                    Please replace this paper for an handwritten <?php echo $key; ?> voucher <br>
                    Dear passenger if you have recieved 
                    this piece of paper please check if you 
                    have an handwritten <?php echo $key; ?> voucher as 
                    well. 
                </p>
                <br>
                <p>
                    If you have not please report back to 
                    the desk. 
                </p>

        </div>
    </div>
    <?php endif ?>
<?php endforeach ?>

<?php if ($flag_bus > 0): ?>
    <br clear="all" />
    <div class ="main_specific">
        <div  class ="bus-gtc">
            <div class="logo">    
                <img src="<?php echo JURI::root().'media/media/images/Logo_AB_for_Vouchers.png'; ?>" width="50%" style="float:right;">
            </div>
            <h3>BUS</h3>
            <div class="content">
                <p>There was a bus arranged for your transportation to the</p>
                <p><?php echo $hotel_name.' airport hotel'; ?></p>
                <p>Pick up time <?php echo $date.' '.$time.' hours'; ?></p>
                <p>Pick up at <?php echo $airline->airport_name;?></p>
                <p>Additional information: Your bus service is provided to you by our partner <?php echo $gtc_company_name; ?></p>
            </div>
        </div>
    </div>
<?php endif ?>

</div>
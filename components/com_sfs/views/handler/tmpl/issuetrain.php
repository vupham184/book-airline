<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.modal');

$startDateList = SfsHelperDate::getSearchDate('expire', 'class="inputbox fs-13" style="width:155px;"');

$airline = SFactory::getAirline();
$airports = $airline->getAirlineAirportData();
$airlineName = '';
if ($airline->grouptype == 3) {
    $selectedAirline = $airline->getSelectedAirline();
    $airlineName = $selectedAirline->name;
}
$viewCardUrl = JURI::base().'index.php?option=com_sfs&view=handler&tmpl=component&layout=airplus_train_voucher';

?>
<style>
   .main-iframe{
      display: none;

  } 
  .iTrain{
    top: 134px;
    left: 300px;
    height: 600px;
    z-index: 10;
    width: 720px;
    position: absolute;
    background-color: white;
}
.sfs-form.form-vertical .form-group label {
    width: 111px;
    color: #B0ABA3;
    font-weight: 400;    }

    div.passenger-choice label {
        font-weight: normal !important;
    }

    div.passenger-choice div.field-passenger label {
        display: block !important;
        margin: 0;
        width: 100% !important;
        clear: both;
        text-align: right;
        float: none !important;
        padding-right: 10px;
        margin-right: 0 !important;
    }

    div.passenger-choice div.field-passenger span {
        display: inline-block;
        float: none;
    }

    div.passenger-choice div.field-passenger{
        display: inline-block;
        float: left;
        margin-right: 10px;
    }

    input {
        width: 40px;;
    }

    ul#passengers_tree {
        overflow: hidden;
        margin-left: 170px;
        padding-top: 0;
    }

    ul#passengers_tree li {
        display: block;
        position: relative;
        margin-left: 0 !important;
        padding-top: 20px;
    }

    ul#passengers_tree li:first-child {
        margin-top: 0;
        padding-top: 0;
    }

    ul#passengers_tree li:before {
        content: "";
        position: absolute;
        width: 1px;
        height: 100%;
        background: #E7E7E7;
        top: -21px;
    }

    ul#passengers_tree li:after {
        content: ".";
        display: block;
        height: 0;
        overflow: hidden;
        clear: both;
    }

    ul#passengers_tree li:first-child span.passenger {
        margin-top: 58px;
    }

    ul#passengers_tree li span.passenger {
        float: left;
        display: inline-block;
        margin-top: 30px;
        margin-right: 20px;
        position: relative;
        padding-left: 80px;
    }

    ul#passengers_tree li span.passenger:after {
        content: "";
        position: absolute;
        left: 0;
        bottom: 10px;
        width: 100px;
        height: 1px;
        background: #E7E7E7;
    }

    select.adults {
        width: 100px;
        margin-left: 30px;
    }

    select.children {
        width: 100px;
    }

    input.first-name {
        width: 100px;
        margin-left: 30px;
    }

    input.last-name {
        width: 100px;
    }

    hr {
        margin: 20px 15px 10px 100px;
        border: none;
        height: 1px;
        background: #E7E7E7;
    }

    #ws-booking-loading{
        background-color: #fff;
        display: none;
        margin-left: 15px;
    }
    .popup {
        border: 1px solid black;
        border-radius: 5px;
        color: #f9071f;
    }
    .popup p{
        margin:5px 0px;
    }
    .push-left{
        float: left;
    }
    .push-right{
        float: right;
    }
    .clear{
        clear: both;
    }
    div{
        padding: 0px 10px;
    }
    .popup-body p span{
        float: left;
        
    }
    .popup-body p span:first-child{
        max-width: 50%;
    }
    .popup-body p span:last-child{
        padding-top: 10px;
    }
    .popup-bottom{
        height: 200px;
    }
</style>
<script type="text/javascript">
    jQuery.noConflict();
    jQuery(function ($) {
        function loadRoomRows(num_passenger) {
            removeAllRows();
            for(var i=0; i < num_passenger; i++)
            {
                addNewRow(i);
            }
            $("#hotelSearchForm ul").after("<hr/>");
        }

        function addNewRow(id) {
            var $li = $("<li></li>"),
            $span = $("<span class='passenger'></span>"),
            $div1 = $("<div class='passenger-choice'></div>"),
            $div_firstname = $("<div class='field-passenger'></div>"),
            $div_lastname = $("<div class='field-passenger'></div>"),
            $input_firstname = $("<input type='text' name='passenger["+id+"][first_name]' class='smaller-size first-name' required='true' />"),
            $input_lastname = $("<input type='text' name='passenger["+id+"][last_name]' class='smaller-size last-name' required='true' />");
            $li.attr("id", id);

            if(id == 0)
            {
                var $label_firstname = $("<label></label>"),
                $label_lastname = $("<label></label>");

                $label_firstname.attr("for", "passenger[0][first_name]").css("padding-right", "30px").text("<?php echo "First name"; ?>");
                $div_firstname.append($label_firstname);

                $label_lastname.attr("for", "passenger[0][last_name]").css("padding-right", "30px").text("<?php echo "Last name"; ?>");
                $div_lastname.append($label_lastname);
            }

            $div_firstname.append($input_firstname);
            $div1.append($div_firstname);

            $div_lastname.append($input_lastname);
            $div1.append($div_lastname);

            $li.append($span);
            $li.append($div1);
            $("#generateForm ul").append($li);
        }

        function removeAllRows() {
            for(var i=0; i < 20; i++)
            {
                $("#generateForm ul li#" + i).remove();
                $("#generateForm ul").next("hr").remove();
            }
        }

        $(document).ready(function () {

          

            $("#passengers").on("change",function () {
                num_passengers = parseInt($(this).val());
                if(!$.isNumeric(num_passengers) || num_passengers < 1 )
                {
                    $(this).val(1);
                    num_passengers = 1;
                }
                if(num_passengers >20)
                {
                    $(this).val(20);
                    num_passengers=20;
                }
                if(num_passengers <=20)
                    loadRoomRows(num_passengers);
                else
                    removeAllRows();
            })


            $(document).on('click',function(e) {

                if(e.target.id != 'btnPrint') {
                    $(".main-iframe").hide();
                }
            })
            
            $("#generateForm").on("submit",function(){
                var loading         = $(document.activeElement).next(".ws-booking-loading");
                var airport_id      = $("#from-address").find('option:selected').attr("data-id");
                var viewCardUrl     = '<?php echo $viewCardUrl?>';
                var flight_number   = $('#flight-number').val();
                var passengers      = $('#passengers').val();

                //SqueezeBox.open(viewCardUrl, {handler: 'iframe', size: {x: 954, y: 700} });
                
                $("#generateForm").ajaxSubmit({
                    url: "index.php",
                    type: 'post',
                    beforeSend:function(){
                        loading.css("display", "inline-block");
                    },
                    success: function(data) {
                        console.log(data);
                        viewCardUrl = viewCardUrl + '&vouchers=' + data;
                        SqueezeBox.open(viewCardUrl, {handler: 'iframe', size: {x: 954, y: 700} });
                    },
                    complete: function(){
                        loading.css("display", "none");
                    }
                });
                return false;
            });

        });
    })
</script>

<div class="main-iframe">
    <div class="iTrain" src="">
        <div class="popup">
            <div class="popup-head">
                <p class="push-left"><strong>Good for train</strong> / ICE-ticket</p>
                <p class="push-right">Logo</p>
            </div>
            <div class="clear"></div>
            <div class="popup-body">
                <p class="push-left">
                    <span >Name des Passagiers / Passenger name</span>
                    <span class="passenger-name">John Doe</span>
                </p>
                <p class="clear"></p>

                <p class="push-left">
                    <span >Flug Nr. / Flight No.</span>
                    <span class="flight-no" >code flight</span>
                </p>

                <p class="push-right">
                    <span style="max-width: 30%;">Von / From</span>
                    <span class='train-from'>code flight</span>
                </p>
                <p class="clear"></p>

                <p class="push-left">
                    <span >Reisetag / Travel day</span>
                    <span class="train-date">code flight</span>
                </p>

                <p class="push-right" style="padding-right:10px">
                    <span style="max-width: 51px">Nach / To</span>
                    <span class="train-to">code flight</span>
                </p>
                <p class="clear"></p>

                <p class="push-left">
                    <span >Ticketnumber / Ticket number</span>
                    <span class="train-ticket">code flight</span>
                </p>
                <p class="clear"></p>

                <p class="push-left">Hinweis für den Passagier / Information for the passenger</p>
                <p class="clear"></p>
            </div>

            <div class="popup-bottom">
                <p>Dieser coupon ist am Tag seiner ausstellung und am darauf folgenden Tag für die im Coupon benannte Strecke in der 2. Klasse nur gegen Vorlage durch die Coupon bezeichnete Person gültig und nicht üertragbar. Bei Einlösung des Coupon werden keine Meilen gutgeschrieben /
                    This coupon is only valid on the day of issue and teh following day, for the mentioned destination, in 2nd class and only if presented by the person named on the coupon. This coupon is not transferable. Miles will not be credited for this coupon.</p>
                </div>
            </div>
        </div>   
    </div>
    <div class="heading-block clearfix">
        <div class="heading-block-wrap">
            <h3><?php if ($airlineName) echo $airlineName . ': '; ?><?php echo "Issue Mealplan Credit Card Service"; ?></h3>
        </div>
    </div>

    <div class="main">
        <form id="generateForm" action="<?php echo JRoute::_('index.php')?>" method="post"
            class="form-validate sfs-form form-vertical">
            <div class="form-group">
                <label>
                    <?php echo "Flight Number"; ?>:
                </label>
                <input type="text" value="" size="1" name="flight-number" id="flight-number" class="required smaller-size" required="true"
                style="width:100px"/>
            </div>

            <div class="form-group" data-step="2"
            data-intro="<?php echo SfsHelper::getTooltipTextEsc('start_need_room_field', $text, 'airline'); ?>">
            <label style="font-weight: bold; color: #3C3E38">
                <?php echo JText::_("COM_SFS_ROOMS_NEED"); ?>:
            </label>
            <input type="text" value="1" size="1" name="passengers" id="passengers" class="required smaller-size"
            style="width:100px; margin-right: 10px"/><?php echo "train voucher(s)"; ?>
            <ul id="passengers_tree">
                <li id="0">
                    <span class="passenger"></span>
                    <div class="passenger-choice">
                        <div class="field-passenger">
                            <label for="passenger[0][first_name]"
                            style="padding-right: 30px; "><?php echo "First name"; ?></label>
                            <input name="passenger[0][first_name]" class="smaller-size first-name required" required="true"/>
                        </div>
                        <div class="field-passenger">
                            <label for="passenger[0][last_name]"
                            style="padding-right: 30px; "><?php echo "Last name"; ?></label>
                            <input name="passenger[0][last_name]" class="smaller-size last-name required" required="true"/>
                        </div>
                    </div>
                </li>
            </ul>
            <hr/>
        </div>

        <div class="form-group">
            <label>
                <?php echo "From Trainstation"; ?>:
            </label>
            <select class="required smaller-size" name="from-address" id="from-address" style="width: 200px">
                <?php foreach ($this->airlinetrains as $airlinetrain):?>
                    <option value="<?php echo $airlinetrain->id; ?>"><?php echo $airlinetrain->cityname.' '.$airlinetrain->stationname; ?></option>
                <?php endforeach;?>
            </select>
        </div>

        <div class="form-group">
            <label>
                <?php echo "To Trainstation"; ?>:
            </label>
            <select class="required smaller-size" name="to-address" id="to-address" style="width: 200px">
                <?php foreach ($this->airlinetrains as $airlinetrain):?>
                    <option value="<?php echo $airlinetrain->id; ?>"><?php echo $airlinetrain->country.' '.$airlinetrain->cityname.' '.$airlinetrain->stationname; ?></option>
                <?php endforeach;?>
            </select>
        </div>


        <div data-step="3"
        data-intro="<?php echo SfsHelper::getTooltipTextEsc('expired_date', 'help-icon', 'airline', false); ?>">
        <div class="form-group">
            <label><?php echo "Valid for travel on"; ?>:</label>
            <?php echo $startDateList; ?>
        </div>
    </div>

    <?php
    if (isset($airline->params['enable_passenger_payment']) && (int)$airline->params['enable_passenger_payment'] == 1) :
        ?>
    <div class="form-group">
        <label class="pull-left">Invoice for:</label>

        <div class="pull-left">
            <div class="form-group radio">
                <label>
                    <input type="radio" name="payment_type" value="airline" checked="checked"
                    id="payment_type_airline" style="padding: 0;margin:0">
                    <?php echo $airline->name?></label>
                </div>

                <div class="form-group radio">
                    <label><input type="radio" name="payment_type" id="payment_type_passenger" value="passenger"
                      style="padding: 0;margin:0">
                      Passenger</label>
                  </div>
              </div>

          </div>
      <?php endif; ?>

      <div class="form-group">
          <button type="submit" class="btn orange lg" id="btnPrint">
            <?php echo "Print"; ?>
                  </button>
        
        <div id="ws-booking-loading" class="ws-booking-loading">
            <span class="ws-booking-spinner ajax-Spinner48"></span>
        </div>
    </div>

    <input type="hidden" name="task" value="ajax.issueTrainVoucher"/>
    <input type="hidden" name="airport_id" value="<?php echo $airline->airport_id;?>"/>
    <input type="hidden" name="format" value="raw"/>
    <input type="hidden" name="option" value="com_sfs"/>
    <!-- <input type="hidden" name="Itemid" value="<?php //echo JRequest::getInt('Itemid'); ?>"/> -->
    <?php echo JHtml::_('form.token'); ?>

</form>
</div>

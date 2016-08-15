<?php
defined('_JEXEC') or die;
$startDateList = SfsHelperDate::getSearchDate('expire', 'class="inputbox fs-13" style="width:120px;"','date_flight','Y-m-d');
?>


<style type="text/css">
#mainPopupReport{float: left; width:100%;}
.textaform_1{float:left; width: 100%; height: 70px;}
.textaform_2{float:left; width: 100%; height: 70px;}
.orides{color: #E27C7B; font-weight: 600;}
input.s-button{background-color: #ff8806;padding: 5px;}
th{text-align: left; background-color: #dfdfdf; height: 30px; border:2px solid #78B8F0; border-left: none;border-right: none;}
table.mainInfo td{border-bottom: 1px solid #78B8F0;padding: 5px 0;}
table.mainInfo{float:left; width:100%;}
.addSendto{background:#ff8806;width:80px;text-align:center;margin-left:20px;height:38px; line-height:38px;color:#ffffff;font-weight:600;cursor: pointer;}
.commentInfoSendmail{
    float: left;
    width: 100%;
    padding: 5px;
    background-color: #E1F1F9;
    margin-top: 20px;
}
.commentInfoSendmail input{float: right; background-color: #ff8806; padding: 5px 7px;width: 140px;color: #ffffff; font-weight: 600;margin-top: 40px;}
ul li{list-style: none; margin-left:0;}
ul.servicename li{margin: 0;}

.row_le{background: #f8f8f8;}
.changetexthotel{width: 100%;}
#sbox-window {
    background-color: #fff;
    border: 4px solid #FBE7A0;
    overflow: visible;
    padding: 10px 10px 30px;
    position: absolute;
    text-align: left;
}
#maflight, #maDate{
    width: 150px;
}
table.reportcount th{border: 1px solid #78B8F0;padding: 5px 0;text-align: center;}
table.reportcount td{border: 1px solid #78B8F0;padding: 5px 0;text-align: center;}
table.reportcount{float:left; width:60%; margin: 10px;}

.infomail{float:left; width: 53%;}
.info_subject{float:left; width: 45%;}
.subinfomail{float:left; width: 100%;}

</style>

<link rel="stylesheet" href="<?php echo JUri::root() . 'media/media/js/jquery-ui.css'; ?>">
<link rel="stylesheet" href="<?php echo JUri::root() . 'media/media/js/token-input.css'; ?>">
<script src="<?php echo JUri::root() . 'media/media/js/jquery-1.10.2.js'; ?>"></script>
<script src="<?php echo JUri::root() . 'media/media/js/jquery-ui-11.4.js'; ?>"></script>
<script src="<?php echo JUri::root() . 'media/media/js/jquery.tokeninput.js'; ?>"></script>


<script type="text/javascript">
var listArrMail = [];
var listArrMailBcc = [];

    jQuery(function($){
        $("#maflight").on('change', function(){
            $.ajax({
                url:"<?php echo JURI::base().'index.php?option=com_sfs&task=communication.getChangeFlight'; ?>",
                type:"POST",  
                data: {"flight":$("#maflight").val()},              
                dataType: 'json',                
                success:function(data){    
                   // console.log(data);  
                   var htmlShow ='<tr>';
                        htmlShow +=   '<th width="22%">Passenger</th>';
                        htmlShow +=    '<th width="10%">PNR</th>';
                        htmlShow +=   '<th width="12%">Add Info</th>';
                        htmlShow +=    '<th width="14%">Flightnumber</th>';
                        htmlShow +=    '<th width="12%">Onward Flight</th>';
                        htmlShow +=    '<th width="30%">Services</th>';
                        htmlShow +=    '</tr>';
                    
                    var html = '<option value="0">-- choose date --</option>'; 
                    if(data != ""){
                        $.each(data, function(index, val) {                            
                            html +='<option value="'+val.id+'">'+val.flightDate+'</option>';
                            $("#maDate").html(html);
                        });
                    }else{                        
                        $("#maDate").html(html);                        
                    } 
                    var html_count = '<tr><th width="30%">&#160;</th><th width="30%">Authorised</th><th width="30%">Issued</th></tr>';

                    var html = '<input type="text" value="" readonly style="width:80%" >';
                    var html_ = '<input type="text" value="" readonly style="width:400px;" >';

                    $(".mainInfo").html(htmlShow);  
                    $('.time-std-etd').html("");
                    $('.time-sta-eta').html("");
                    $('.orides').html(""); 
                    $('.airport_1').html(html);
                    $('.airport_2').html(html); 
                    $('.subject_com').html(html_);
                    $('.reportcount').html(html_count);                                 
                }
            }); 
        });  

        $("#maDate").on('change', function(){
            var flight_ = $("#maflight").val();
            var date_   = $("#maDate option:selected").text();

            var html = '<input type="text" value="" readonly style="width:80%" >';
            var html_ = '<input type="text" value="" readonly style="width:400px;" >';
            var htmlShow ='<tr>';
                htmlShow +=   '<th width="22%">Passenger</th>';
                htmlShow +=    '<th width="10%">PNR</th>';
                htmlShow +=   '<th width="12%">Add Info</th>';
                htmlShow +=    '<th width="14%">Flightnumber</th>';
                htmlShow +=    '<th width="12%">Onward Flight</th>';
                htmlShow +=    '<th width="30%">Services</th>';
                htmlShow +=    '</tr>';
            $('.airport_1').html(html);
            $('.airport_2').html(html); 
            $('.subject_com').html(html_);
            $(".mainInfo").html(htmlShow);

            $.ajax({
                url:"<?php echo JURI::base().'index.php?option=com_sfs&task=communication.getFillterChange'; ?>",
                type:"POST",  
                data: {"date": $("#maDate").val()},              
                dataType: 'json',                
                success:function(data){    
                    //console.log(data);
                    if(data != ""){
                        var html = '<input type="text" value="'+data.dep+'" readonly style="width:80%" >';

                        var html_ = '<input type="text" value="'+data.arr+'" readonly style="width:80%" >';
                        if(data.passengerInfo.length){
                            var html_sub = '<input type="text" value="Due to '+data.irreg_reason+' (and '+data.passengerInfo[0].irreg_reason+') ' +flight_+ ' ' +date_+ ' Please note following" style="width:400px;" readonly>';
                        }
                       
                        var html_sum = data.dep + " and " + data.arr;
                        $('.airport_1').html(html);
                        $('.airport_2').html(html_);                       
                        $('.subject_com').html(html_sub);
                        $('.summary_text').html(html_sum);
                        
                        var row = "row_le";
                        
                        var htmlShow ='<tr>';
                            htmlShow +=   '<th width="22%">Passenger</th>';
                            htmlShow +=    '<th width="10%">PNR</th>';
                            htmlShow +=   '<th width="12%">Add Info</th>';
                            htmlShow +=    '<th width="14%">Flightnumber</th>';
                            htmlShow +=    '<th width="12%">Onward Flight</th>';
                            htmlShow +=    '<th width="30%">Services</th>';
                            htmlShow +=    '</tr>';

                        $.each(data.passengerInfo,function(index, value) {
                            if(index%2 == 0){
                                row = "row_chan";
                            }else{
                                row = "row_le";
                            }
                            
                            htmlShow += '<tr class="'+row+'">';
                            htmlShow += '<td width="22%">'+value.first_name+' '+value.last_name+' '+value.title+ '</td>';
                            htmlShow += '<td width="10%">'+value.pnr+ '</td>';
                            htmlShow += '<td width="12%">'+data.irreg_reason+ ', ' +value.irreg_reason+ '</td>';
                            htmlShow += '<td width="14%">'+data.dep+ " " +data.carrier+data.flight_no+ " " +data.arr+ '</td>';
                            htmlShow += '<td width="12%"></td>';
                            htmlShow += '<td width="30%"><ul class="servicename">';
                                if(value.name_service != ""){
                                    $.each(value.name_service,function(index, el) {
                                        if(parseInt(el.id) == 1){
                                            if(el.hotel_name != ''){
                                               htmlShow += '<li>'+el.name_service+ ': ' +el.hotel_name.name+ '</li>'; 
                                           }else{
                                                htmlShow += '<li>'+el.name_service+ ':</li>';
                                           }
                                            
                                        }else{
                                            if(el.price_per_person == null){
                                                htmlShow += '<li>'+el.name_service+ ':</li>';
                                            }else{
                                                if(parseInt(el.id) == 2 || parseInt(el.id) == 3){
                                                    var floatNum = parseInt(el.price_per_person);
                                                    htmlShow += '<li>'+el.name_service+ ': ' +floatNum.toFixed(2)+ '</li>';
                                                }else{
                                                    htmlShow += '<li>'+el.name_service+ ': ' +el.price_per_person+ '</li>';
                                                }
                                                
                                            }
                                            
                                        }
                                        
                                    });
                                }else{
                                    htmlShow += '<li>&#160;</li>';
                                }
                                
                            htmlShow += '</ul></td>';
                            
                            
                            
                            htmlShow += '</tr>';
                        });
                        $(".mainInfo").html(htmlShow);

                        // count Author and Issue                        
                        var conv_count_issue = [];
                        var count = 0;
                        var html_count = '<tr><th width="30%">&#160;</th><th width="30%">Authorised</th><th width="30%">Issued</th></tr>';

                        $.each(data.countAuthen,function(key_author, el) {
                            if(data.countIssue[key_author] > 0){
                                conv_count_issue.push(data.countIssue[key_author]);
                            }else{
                                data.countIssue[key_author] = 0;
                                conv_count_issue.push(data.countIssue[key_author]);
                            }
                        });

                        $.each(data.countAuthen,function(key_author, el) {
                            html_count += '<tr>';
                            html_count += '<td>'+key_author+'</td>';    
                            html_count += '<td>'+el+'</td>';                       
                            html_count += '<td>'+conv_count_issue[count]+'</td>';                   
                            html_count += '</tr>';   
                            count++;                        
                        });
                        
                        $('.reportcount').html(html_count);
                    }    
                }
            }); 
        });

        $("#sendmessage").on('click', function (){
            var listmail = listArrMail;   
            var listmailbcc = listArrMailBcc;         
            console.log(listmail);
            if(listmail.length == 0){
                alert('Please choose email send to!');
                return false;
            }

            $("#sendmessage").css('display','none');
            $('.loadMessage').css("display","block");

            $.ajax({
                url:"<?php echo JURI::base().'index.php?option=com_sfs&task=communication.sendmessage'; ?>",
                type:"POST",  
                data: {"dateId": $("#maDate").val(), "text_1": $(".textaform_1").val(), "text_2":$(".textaform_2").val(), "listmail": listmail, "subject": $('.subject_title').val(), "listmailbcc": listmailbcc},              
                dataType: 'json',  
                success: function(data){
                    //console.log(data.status);
                    if(data.status == "ok"){
                        $('.loadMessage').css("display","none");
                        $("#sendmessage").css('display','block');
                    }
                }
            });
        });


    });


</script>
<div id="mainPopupReport">
    <form>
        <table cellpadding="0" cellspacing="0" border="0" style="float:left; width:100%;background:#E1F1F9; padding: 10px 10px 20px 10px;">
            <tr>
                <td width="20%">Flight</td>
                <td width="20%">Date</td>
                <td width="20%">Origin</td>                
                <td width="20%">Destination</td>
                <td width="20%">Cause</td>                
            </tr>
            <tr>
                <td width="20%">
                    <select id="maflight">
                        <option>-- choose flight --</option>
                        <?php foreach ($this->dataPassengerReport as $key => $value): ?> 
                            <option value="<?php echo $value->carrier."".$value->flight_no; ?>">
                                <?php echo $value->carrier."".$value->flight_no; ?>
                            </option>
                        <?php endforeach ?>
                    </select>
                </td>
                <td width="20%">
                    <span>
                        <select id="maDate">
                            <option>-- choose date --</option>                        
                        </select>
                    </span>
                </td>                    
                
                <td width="20%">
                    <span class="airport_1">
                        <input type="text" value="" readonly style="width:80%;">
                    </span>
                </td>
                <td width="20%">
                    <span class="airport_2">
                        <input type="text" value="" readonly style="width:80%;">
                    </span>
                </td>
                
                <td width="20%"><select><option>cause</option></select></td>                
            </tr>
            <tr><td>&#160;</td></tr>
            <tr>
                <td colspan="5" valign="top">
                    <div class="infomail">
                        <div class="subinfomail">
                            Send to: <br />
                            <input type="text" class="demo-input-local-custom-formatters_report" id="sendtoReport" name="blah" />
                        </div>
                       
                        <div class="subinfomail" style="margin-top: 10px;">
                            Subject: <br />  
                            <span class="subject_com">                          
                                <input type="text" class="subject_title" name="title_subject" value="" style="width:400px;" />
                            </span>
                        </div>                        
                    </div>
                    <div class="info_subject">
                        Bcc: <br />
                        <input type="text" class="demo-input-local-custom-formatters_report_bcc" id="sendtoBcc" name="blah" style="height: 100px;" />
                    </div>
                                  
                </td>               
            </tr>
            <!-- <tr>
                <td colspan="5">
                    <span style="margin-top: 10px;float:left;">
                        Subject: <br />
                        <span class="subject_com">
                            <input type="text" class="subject_title" name="title_subject" value="" style="width:400px;" />
                        </span>                        
                    </span>
                </td>                
            </tr> -->
            <tr>
                <td colspan="5">
                    <span style="margin-top: 10px;float:left;">
                        Summary: <br />
                        Attention all concerned at <span class="summary_text"></span> <br />
                        Please <a href="http://www.sfs-web.org/dev3-map2-v3/index.php?option=com_sfs&view=passengersimport&itemit-171">checkout SFS 360</a> for vouchers to be issued, arrange services and check bags to be rerouted.
                    </span>
                </td>
            </tr>    
            <tr>
                <td colspan="5">
                    <table class="reportcount" cellspacing="0" cellpadding="0" border="0" style="width:60%">
                        <tr>
                            <th width="30%">&#160;</th>
                            <th width="30%">Authorised</th>
                            <th width="30%">Issued</th>
                        </tr>                        
                    </table>
                </td>
            </tr>        
            <tr>
                <td colspan="7">
                    <textarea class="textaform_1" style="margin-top:20px;"></textarea>
                </td>                
            </tr>
        </table>   
        
    <table cellspacing="0" cellpadding="0" border="0" class="mainInfo">
        <tr>
            <th width="22%">Passenger</th>
            <th width="10%">PNR</th>
            <th width="12%">Add Info</th>
            <th width="14%">Flightnumber</th>
            <th width="12%">Onward Flight</th>
            <th width="30%">Services</th>
        </tr>
    </table>
    
    <div class="commentInfoSendmail">
        <textarea class="textaform_2" style="height: 80px; width:80%;">THX FOR COOP BRGDS
<DEPARMENT><CODE>
Anna Sauer
        </textarea>

        
        <input type="button" id="sendmessage" value="SEND MESSAGE">    
        <span class="loading"></span> 
        <div class="loadMessage" style="display:none;float:left;height: 31px;margin:25px 0 25px 70px; width:31px;background-repeat: none;background: rgba(0, 0, 0, 0) url('<?php echo JURI::base()."templates/sfs_j16_hdwebsoft/images/ajax-loader.gif"; ?>')"></div> 
    </div>

    </form>
   
</div>           
          
<script type="text/javascript">
$(document).ready(function() {
    var arrData = [];
    var obj = {};
    var test = "fax";
    <?php foreach ($this->emailDSend as $key => $value) : ?>
        var typeChoose = "";
        if(<?php echo '"'.strtolower($value->typ).'"' ?> == 'email'){
            typeChoose = "Email: " + <?php echo '"'.$value->email.'"'?>;
            obj = {"stationcode": <?php echo '"'.$value->stationcode.'"' ?>,
                "companyname": <?php echo '"'.$value->companyname.'"' ?>,
                "carrier": <?php echo '"'.$value->carrier.'"' ?>,
                "department": <?php echo '"'.$value->department.'"' ?>,
                "grouptype": <?php echo '"'.$value->grouptype.'"' ?>,
                "category": <?php echo '"'.$value->category.'"' ?>,
                "name": <?php echo '"'.$value->name.'" ' ?>,
                "nametype": <?php echo '"'.$value->nametype.'"'?>,
                "typ": typeChoose, 
                "id": <?php echo '"'.$value->id.'"' ?>,
                "name_type": "email",     
                "email": <?php echo '"'.$value->email.'"'?>,         
                "name_search": <?php echo '"'.$value->stationcode.' - '.$value->category. ' - ' .$value->email.'"' ?>};
            arrData.push(obj);
        }
        if(<?php echo '"'.strtolower($value->typ).'"' ?> == 'fax')
        {
            typeChoose = "Fax: " + <?php echo '"'.$value->fax.'"'?>;
            obj = {"stationcode": <?php echo '"'.$value->stationcode.'"' ?>,
                "companyname": <?php echo '"'.$value->companyname.'"' ?>,
                "carrier": <?php echo '"'.$value->carrier.'"' ?>,
                "department": <?php echo '"'.$value->department.'"' ?>,
                "grouptype": <?php echo '"'.$value->grouptype.'"' ?>,
                "category": <?php echo '"'.$value->category.'"' ?>,
                "name": <?php echo '"'.$value->name.'" ' ?>,
                "nametype": <?php echo '"'.$value->nametype.'"'?>,
                "typ": typeChoose,     
                "id": <?php echo '"'.$value->id.'"' ?>, 
                "name_type": "fax",  
                "fax": <?php echo '"'.$value->fax.'"'?>,     
                "name_search": <?php echo '"'.$value->stationcode.' - '.$value->category. ' - ' .$value->email.'"' ?>};
            arrData.push(obj);
        }

        
    <?php endforeach; ?>
    
    $(".demo-input-local-custom-formatters_report").tokenInput(
      arrData, 
      {
          propertyToSearch: "name_search",
          resultsFormatter: function(item){ 
            return "<li>"+
                "<div style='display: inline-block;margin:5px;margin-left:0 auto; width:100%;'>"+
                "<div class='full_name'>" + 
                item.stationcode + " - " + 
                item.carrier + " - " + 
                item.department + " - " + 
                item.grouptype + " - " + 
                item.category + "</div>"+
                "<div class='email'>" + item.name + "</div>"+
                "<div class='email'>" + item.companyname + " - " + item.nametype +  "</div>"+
                "<div class='email'>" + item.typ + "</div>"+
                "</div>"+                
                "</li>"
                },
          tokenFormatter: function(item) {   
            //console.log(item);           
            listArrMail.push(item.id);  
            if(item.name_type == "fax"){
                return "<li><p>" + item.stationcode + " - " + item.companyname + " - " + item.fax +"</p></li>"
            } else{
                return "<li><p>" + item.stationcode + " - " + item.companyname + " - " + item.email +"</p></li>"
            }        
             
        },
    });

    var arrDataBcc = [];
    var objBcc = {};
    <?php foreach ($this->emailDSend as $k => $val) : ?>

        objBcc = {"stationcode": <?php echo '"'.$val->stationcode.'"' ?>,
                "companyname": <?php echo '"'.$val->companyname.'"' ?>,
                "name": <?php echo '"'.$val->name.'"' ?>,
                "email": <?php echo '"'.$val->sitamessage.'"' ?>,
                "name_search": <?php echo '"'.$val->stationcode.' - '.$val->name. ' - ' .$val->sitamessage.'"' ?>};
        arrDataBcc.push(objBcc);
    <?php endforeach; ?>
    
    $(".demo-input-local-custom-formatters_report_bcc").tokenInput(
      arrDataBcc, 
      {
          propertyToSearch: "name_search",
          resultsFormatter: function(item){ return "<li><div style='display: inline-block;margin:5px;margin-left:0 auto;'><div class='full_name'>" + item.stationcode + " - " + item.companyname + " - " + item.name + "</div><div class='email'>" + item.email + "</div></div></li>" },
          tokenFormatter: function(item) {            
            listArrMailBcc.push(item.email);   
            return "<li><p>" + item.stationcode + " - " + item.companyname + " - " + item.name +"</p></li>" },
    });
    
});
</script>


<?php
defined('_JEXEC') or die;
$startDateList = SfsHelperDate::getSearchDate('expire', 'class="inputbox fs-13" style="width:120px;"','date_flight','Y-m-d');
?>
<style type="text/css">
#mainPopupReport{float: left; width:900px;}
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
ul.servicename {list-style: none;}
ul.servicename li{margin: 0;}
.row_le{background: #f8f8f8;}
.changetexthotel{width: 100%;}
</style>
<script type="text/javascript">
    jQuery(function($){
        $("#maflight").on('change', function(event) {
            $flight = $("#maflight").val();            
            $.ajax({
                url:"<?php echo JURI::base().'index.php?option=com_sfs&task=communication.getFillterpassenger'; ?>",
                type:"POST",  
                data: {"id":$flight, "filter":"filterId"},              
                dataType: 'json',                
                success:function(data){                                             
                    if(data != ""){
                        var std = (data.std.split("T"))[1].substring(0,5);
                        var etd = (data.etd.split("T"))[1].substring(0,5);
                        var sta = (data.sta.split("T"))[1].substring(0,5);
                        var eta = (data.eta.split("T"))[1].substring(0,5);
                        var flightFill = data.fltref.split("-")[0];                       
                        var html = "STD: " +std+" <br/>ETD: " +etd+ " <br/>ATD:";
                        var html_ = "STA: " +sta+" <br/>ETA: " +eta+ " <br/>ATA:";
                        $('.time-std-etd').html(html);
                        $('.time-sta-eta').html(html_);

                        
                        var row = "row_le";
                        var htmlShow ='<tr><th width="16%">Passenger</th><th width="13%">PNR</th><th width="13%">Flightnumber</th><th width="13%">Services</th><th width="10%">Flag</th><th width="35%">Communication</th></tr>';
                        $.each(data.passengerInfo,function(index, value) {
                            if(index%2 == 0){
                                row = "row_chan";
                            }else{
                                row = "row_le";
                            }
                            
                            htmlShow += '<tr class="'+row+'">';
                            htmlShow += '<td width="16%">'+value.first_name+' '+value.last_name+' '+value.title+ '</td>';
                            htmlShow += '<td width="13%">'+value.pnr+ '</td>';
                            htmlShow += '<td width="13%">'+data.dep+ " " +flightFill+ '</td>';
                            htmlShow += '<td width="13%"><ul class="servicename">';
                                if(value.name_service != ""){
                                    $.each(value.name_service,function(index, el) {
                                        htmlShow += '<li>'+el.name_service+'</li>';
                                    });
                                }else{
                                    htmlShow += '<li>&#160;</li>';
                                }
                                
                            htmlShow += '</ul></td>';
                            htmlShow += '<td width="10%"></td>';
                            htmlShow += '<td width="35%">';
                                $.each(value.name_service,function(index, el) {
                                    if(parseInt(el.id) == 1){
                                        htmlShow += 'HOTAC WILL BE ARRANGED BY PMI REFRESHMENTVOUCHERS VALUE EUR 5';
                                    }                               
                                });
                            htmlShow +='</td>';
                            
                            htmlShow += '</tr>';
                        });
                        $(".mainInfo").html(htmlShow);

                    }   
                }
            }); 
            
        });

        $("#date_flight").on('change', function(event) {           
            $datef = $("#date_flight").val();
            $.ajax({
                url:"<?php echo JURI::base().'index.php?option=com_sfs&task=communication.getFillterpassenger'; ?>",
                type:"POST",  
                data: {"datef":$datef, "filter":"filterDate"},              
                dataType: 'json',                
                success:function(data){    
                //console.log(data);                                         
                    if(data != ""){
                        var std = (data[0].std.split("T"))[1].substring(0,5);
                        var etd = (data[0].etd.split("T"))[1].substring(0,5);
                        var sta = (data[0].sta.split("T"))[1].substring(0,5);
                        var eta = (data[0].eta.split("T"))[1].substring(0,5);  
                        var flightFill = data[0].fltref.split("-")[0];                          
                        var html = "STD: " +std+" <br/>ETD: " +etd+ " <br/>ATA:";
                        var html_ = "STA: " +sta+" <br/>ETA: " +eta+ " <br/>ATA:";
                        var htmlFlight = "";
                        $.each(data, function(index, value) {
                            htmlFlight += '<option value="'+value.id+'">'+value.fltref.split("-")[0]+'</option>';
                        });
                                                    
                        $('.time-std-etd').html(html);
                        $('.time-sta-eta').html(html_);
                        $('#maflight').html(htmlFlight);
                        
                        if(data[0].passengerInfo != "" ){
                            var row = "row_le";
                            var htmlShow ='<tr><th width="16%">Passenger</th><th width="13%">PNR</th><th width="13%">Flightnumber</th><th width="13%">Services</th><th width="10%">Flag</th><th width="35%">Communication</th></tr>';
                            $.each(data[0].passengerInfo,function(index, value) {
                                if(index%2 == 0){
                                    row = "row_chan";
                                }else{
                                    row = "row_le";
                                }
                                
                                htmlShow += '<tr class="'+row+'">';
                                htmlShow += '<td width="16%">'+value.first_name+' '+value.last_name+' '+value.title+ '</td>';
                                htmlShow += '<td width="13%">'+value.pnr+ '</td>';
                                htmlShow += '<td width="13%">'+data[0].dep+ " " +flightFill+ '</td>';
                                htmlShow += '<td width="13%"><ul class="servicename">';
                                    if(value.name_service != ""){
                                        $.each(value.name_service,function(index, el) {
                                            htmlShow += '<li>'+el.name_service+'</li>';
                                        });
                                    }else{
                                        htmlShow += '<li>&#160;</li>';
                                    }
                                    
                                htmlShow += '</ul></td>';
                                htmlShow += '<td width="10%"></td>';
                                htmlShow += '<td width="35%">';
                                    $.each(value.name_service,function(index, el) {
                                        if(parseInt(el.id) == 1){
                                            htmlShow += 'HOTAC WILL BE ARRANGED BY PMI REFRESHMENTVOUCHERS VALUE EUR 5';
                                        }                               
                                    });
                                htmlShow +='</td>';
                                
                                htmlShow += '</tr>';
                            });
                            $(".mainInfo").html(htmlShow);
                        }else{
                            var htmlShow ='<tr><th width="16%">Passenger</th><th width="13%">PNR</th><th width="13%">Flightnumber</th><th width="13%">Services</th><th width="10%">Flag</th><th width="35%">Communication</th></tr>';
                            $(".mainInfo").html(htmlShow);
                        }


                    }else{                                            
                        var html = "STD:  <br/>ETD:  <br/>ATA:";
                        var html_ = "STA:  <br/>ETA:  <br/>ATA:";
                        var htmlFlight = "";
                        htmlFlight += '<option value="">--No Flight--</option>'; 

                        $('.time-std-etd').html(html);
                        $('.time-sta-eta').html(html_);
                        $('#maflight').html(htmlFlight);
                    }  
                }
            }); 
            
        });

        $('.colTestHotel').on('click', '.textHotel', function(event) {
            var textVal = $(this).text();
            var html = '<textarea id="changetexthotel">'+textVal+'</textarea>';
            html += '<input type="hidden" class="hiddenText" value="'+textVal+'" >';
            $(this).parent().html(html);
            $('#changetexthotel').focus();
        });
            
        
    });
</script>
<div id="mainPopupReport">
    <form>
        <table cellpadding="0" cellspacing="0" border="0" style="float:left; width:100%;background:#E1F1F9; padding: 10px 10px 20px 10px;">
            <tr>
                <td width="15%">Flight</td>
                <td width="15%">Date</td>
                <td width="9%">Origin</td>
                <td width="4%">&#160;</td>
                <td width="4%">&#160;</td>
                <td width="10%">Destination</td>
                <td width="10%">Cause</td>
                <td width="14%">Send to</td>
                <td width="5%">&#160;</td>
            </tr>
            <tr>
                <td>
                    <select id="maflight">
                        <?php foreach ($this->dataPassengerReport as $key => $value): ?>
                            <?php $flight = explode('-',$value->fltref); ?>
                            <option value="<?php echo $value->id; ?>"><?php echo $flight[0]; ?></option>
                        <?php endforeach ?>
                        
                    </select>
                </td>
                <td>
                    <span style="margin-left: 10px;">
                    <?php echo $startDateList; ?></span>
                </td>                    
                <td>
                    <span class="time-std-etd">
                        <?php $std = explode('T',$this->dataPassengerReport[0]->std); ?>
                        <?php $etd = explode('T',$this->dataPassengerReport[0]->etd); ?>
                        STD: <?php echo substr($std[1],0,5); ?><br/>
                        ETD: <?php echo substr($etd[1],0,5); ?><br/>
                        ATD:
                    </span>                     
                </td>
                <td><span class="orides"><?php echo $this->dataPassengerReport[0]->dep; ?></span></td>
                <td><span class="orides">PMI</span></td>
                <td>
                    <span class="time-sta-eta">
                        <?php $sta = explode('T',$this->dataPassengerReport[0]->sta); ?>
                        <?php $eta = explode('T',$this->dataPassengerReport[0]->eta); ?>
                        STA: <?php echo substr($sta[1],0,5); ?><br/>
                        ETA: <?php echo substr($eta[1],0,5); ?><br/>
                        ATA:
                    </span>
                </td>
                <td><select><option>cause</option></select></td>
                <td>
                    <input type="text" name="valaddname" style="margin-left:10px;">
                </td>
                <td style="vertical-align: mi">
                    <div class="addSendto">Add</div>
                    <!-- <input type="button" name="addvalue" value="Add" class="s-button"> -->
                </td>
            </tr>
            <tr>
                <td colspan="7">
                    <textarea class="textaform_1" style="margin-top:20px;"></textarea>
                </td>
                <td><textarea class="textaform_2" style="margin-left:10px;margin-top:20px;"></textarea></td>
                <td>&#160;</td>
            </tr>
        </table>   
    </form>    
    <table cellspacing="0" cellpadding="0" border="0" class="mainInfo">
        <tr>
            <th width="16%">Passenger</th>
            <th width="13%">PNR</th>
            <th width="13%">Flightnumber</th>
            <th width="13%">Services</th>
            <th width="10%">Flag</th>
            <th width="35%">Communication</th>
        </tr>
        
        <?php foreach ($this->dataPassengerReport[0]->passengerInfo as $key => $value): ?>
            <?php $flightIn = explode('-',$this->dataPassengerReport[0]->fltref); ?>
            <tr class="<?php echo $key%2 == 0 ? "row_chan" : "row_le"; ?>">            
                <td><?php echo $value->first_name . " " . $value->last_name . " " . $value->title; ?></td>
                <td><?php echo $value->pnr; ?></td>
                <td><?php echo $this->dataPassengerReport[0]->dep ." ". $flightIn[0]; ?></td>
                <td>
                    <ul class="servicename">
                        <?php foreach ($value->name_service as $k => $v) : ?>
                            <li>
                                <?php echo $v->name_service; ?>
                            </li>                            
                        <?php endforeach; ?>
                    </ul>
                </td>
                <td>&#160;</td>
                <td class="colTestHotel">
                    <?php foreach ($value->name_service as $k => $v) : ?>
                        <?php if((int)$v->id == 1): ?>
                            <span class="textHotel">HOTAC WILL BE ARRANGED BY PMI REFRESHMENTVOUCHERS VALUE EUR 5</span>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    
                </td>           
            </tr>
        <?php endforeach; ?>
       
    </table>
    
    <div class="commentInfoSendmail">
        <textarea style="height: 80px; width:80%;"></textarea>
        <input type="button" value="SEND MESSAGE">
    </div>
</div>           
          
   
<script type="text/javascript">
    jQuery(function($){
        
        $(".colTestHotel").on("blur", "#changetexthotel", function(){            
            var textChange =  $(this).val();
            var textHidden = $(".hiddenText").val();
            
            if(textChange == ""){
                var html = '<span class="textHotel">'+textHidden+'</span>';
            }else{
                var html = '<span class="textHotel">'+textChange+'</span>';
            }
            
            $(this).parent().html(html);
        });
    });
</script>
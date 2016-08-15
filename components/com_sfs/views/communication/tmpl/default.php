<?php
defined('_JEXEC') or die;
JHTML::_('behavior.modal');
echo JPATH_COMPONENT; 
?>
<style type="text/css">
.left_content{float: left; width: 50%; background-color: #D5E2E8;}
.right_content, .right_content_replay, .right_content_new{float: right; width: 47%;}
.right_content, .right_content_replay, .right_content_new{margin: 10px;}
.top_content{
    background: #6799C8;
    height: 40px; 
    border-top-left-radius: 5px;
    border-top-right-radius: 5px;
}
div.info_top_left{
    float: left;width: 35%;
    border-radius: 5px;
    border:1px solid #dfdfdf;
    margin: 10px;
    padding: 6px;
    background-color: #FFFFFF;
    cursor: pointer;
}
div.info_top_left_new{
    float: left;width: 35%;
    border-radius: 5px;
    border:1px solid #dfdfdf;
    margin: 10px;   
    background-color: #FFFFFF;
    cursor: pointer;
}

.info_top_left_new ul{padding: 6px 0;}
.info_top_left_new ul li{margin-left:7px;}

div.info_top_refesh{margin: 13px 15px;float: left; cursor: pointer;}
#reply_mail_bt{float: left; cursor: pointer;}
img{vertical-align:middle;}
div input.inputSearch{ height: 30px; border-radius: 5px;}
span#reply_mail, span#forward_mail{color: #999; cursor: pointer;}
.titleinfo{
    float: left; width: 100%; margin-top: 20px;height: 40px; line-height: 35px;
    border-bottom: 1px solid #78B8F0;
}
.subtitleinfo, .subtitleinfo_{
    float: left; width: 100%;background-color: #f4f6f7;
    padding: 10px 0; border-bottom: 1px solid #dfdfdf;
}

.subtitleinfo:hover, .subtitleinfo_:hover{background: #78B8F0; cursor: pointer;}
.righttitle{float:left; width: 100%; background-color: #dfdfdf;}

.rightinfo{ 
    float:left; width: 100%;border: 1px solid #dfdfdf; height: 180px; padding: 7px 0 0 10px;
    border-bottom-left-radius: 5px;border-bottom-right-radius: 5px;overflow: auto;
}
.rightinforeplay{float:left; width: 100%; border: 1px solid #dfdfdf; height: 60px; padding: 7px 0 0 10px; margin-top: 10px; border-radius: 5px; }
.rightinfo_replay{float: left; width: 100%;}
#sendemailcomm, #sendemailcommnew{float: right;width: 130px;background: #6799c8; padding: 8px 14px; color: #ffffff; font-weight:bold; cursor:pointer; margin-top: 20px;}
ul.select2-selection__rendered li, ul.select2-results__options li{margin: 0px;}
#sendSearch{float: right;background: #6799c8; padding: 8px 14px; color: #ffffff; font-weight:bold; cursor:pointer; margin-top: 20px; width: 50px;text-align: center;}
.searchform{float:left; width:90%;background-color: #f8f8f8; padding: 10px 20px;border-radius: 5px;
position: relative; top:-30px;z-index: 1000;
}
table tr td input{margin-top: 20px;}
#closeSearch{float: right;cursor: pointer;}
.loading{float:right; width: 50%; display: none;}
.btnsend{float:right; width: 50%;}
#sortDate{float: left;width: 20%;cursor:pointer;}
.showdatawithajax{float:left; height: 400px; width: 100%; overflow: auto;background-color: #FFFFFF;}
#sortNewest{float: left;width: 20%;cursor:pointer;}
#emailInfo1{font-family: "Roboto",sans-serif;}
#sbox-window {
    background-color: #fff;
    border: 4px solid #FBE7A0;
    overflow: visible;
    padding: 10px 10px 30px;
    position: absolute;
    text-align: left;
}

/* Menu*/

</style>

<link rel="stylesheet" href="<?php echo JUri::root() . 'media/media/js/jquery-ui.css'; ?>">
<link rel="stylesheet" href="<?php echo JUri::root() . 'media/media/js/token-input.css'; ?>">
<link rel="stylesheet" href="<?php echo JUri::root() . 'media/media/css/menu.css'; ?>">



<script src="<?php echo JUri::root() . 'media/media/js/jquery-1.10.2.js'; ?>"></script>
<script src="<?php echo JUri::root() . 'media/media/js/jquery-ui-11.4.js'; ?>"></script> 
<script src="<?php echo JUri::root() . 'media/media/js/jquery.tokeninput.js'; ?>"></script>
<script src="<?php echo JUri::root() . 'media/media/js/custom.js'; ?>"></script>



<script type="text/javascript">

// <?php      
//     $arr = array();              
//     foreach ($this->emailHotel as $key => $value) {  
//         $arr[] = $value->email;            
//     }
    
// ?> 
$(function() {
    //var arrList = [<?php echo '"'.implode('","',$arr).'"';?>];
    var arrList = [
                      "aero@sfs-web.com",
                      "amsair@sfs-web.com",
                      "aviap@sfs-web.com",
                      "klwhat@sfs-web.com",
                      "mstr@sfs-web.com",
                      "menz@sfs-web.com",
                      "SwissNL@sfs-web.com",
                      "swissbe@sfs-web.com",
                      "tui@sfs-web.com",
                      "neckerm@sfs-web.com"                              
                    ];
    $( "#searchNewEmail" ).autocomplete({
        source: arrList
    });
});

var listArrMail = [];
jQuery(function($){
    $("#reply_mail, #reply_mail_bt").on('click', function(event) {
        var toVal = $('.infoFrom').text();
        var subject = $("#subjectload").val();
        showreplay();
        $('.replayTo').html(toVal);
        $('.replaySub').html(subject);
    });    

    $("#forward_mail_bt ").on('click', function(event) {
        shownewmail();
        var htmlSub = 'Subject:<input type="text" id="subjectnew" value="" style="float:left;width:400px;" />';
        var html = '<textarea id="emailInfo1" name="infoemail" style="width: 100%; height: 180px; padding:10px;"></textarea>';
        $('.rightinfo_replay_new').html(html);
        $('.appsubject').html(htmlSub);    
    });    

     $("#forward_mail").on('click', function(event) {  
        shownewmail();      
        var htmlSub = 'Subject:<input type="text" id="subjectnew" value="'+$('#subjectload').val()+'" style="float:left;width:400px;" />';
        var html = '<textarea id="emailInfo" name="infoemail" style="width: 100%; height: 180px; padding:10px;">'+$('.rightinfo').text()+'</textarea>';
        $('.rightinfo_replay_new').html(html); 
        $('.appsubject').html(htmlSub);    
    }); 


    

    $('#sendemailcomm').click(function(event) {  
        var arr = [];        
        var toRe    = $('.listselect2').text();
        var toRe2    = $('#listselect22').val();
        var info    = $('#emailInfo').val();
        if(toRe2 != undefined){
            arr.push(toRe2);
        }else{
            arr = toRe;
        }
        $('.loading').show();$('.btnsend').hide();
        submitsendmail(arr, info);       
    });   

    $('#sendemailcommnew').click(function(event) {      
        var listmail = listArrMail;
        var info    = $('#emailInfo1').val();    
        var subject = $('#subjectnew').val();      
        $('.loading').show();$('.btnsend').hide();              
        submitsendmail(listmail, info, subject);
    });


    $("#search_comm").click(function(event) {
        $(".searchform").show();
    });

    $('#closeSearch').on('click', function(){
        $(".searchform").hide();
    });

    var count = 0;
    $("#sortDate").on('click', function(event) {
        count++;
        var sortname = "";
        var nameBySort = "byDate";
        if(count%2 == 0){
            $('#sortDate i').attr('class','fa fa-caret-down');
            sortname = "DESC";
        }else{
            $('#sortDate i').attr('class','fa fa-caret-up');
            sortname = "ASC";
        }

        loadAjaxSort(sortname, nameBySort);  
    });

    var count_ = 0;
    $("#sortNewest").on('click', function(event) {
        count_++;
        var sortname = "";
        var nameBySort = "newest";
        if(count_%2 == 0){
            $('#sortNewest i').attr('class','fa fa-long-arrow-down');
            sortname = "DESC";
        }else{
            $('#sortNewest i').attr('class','fa fa-long-arrow-up');
            sortname = "ASC";
        }

        loadAjaxSort(sortname,nameBySort);
    });

    function loadAjaxSort(sortname, nameBySort){
        $.ajax({
            url:"<?php echo JURI::base().'index.php?option=com_sfs&task=communication.sortDataCommunication'; ?>",
            type:"POST",  
            dataType: 'json', 
            data: {"sort":sortname, "val":nameBySort},               
            success:function(data){                                     
                var html = "";
                $.each(data, function(index, value) {
                    if(parseInt(value.status) == 1 ){
                        html += '<div class="subtitleinfo_" onclick="clickShowInfoMail('+value.id+')" style="color: #01b2c3;">';
                        html += '<div style="float: left;width: 10%">';
                        html += '<input type="checkbox" value="" id="check_all">';
                        html += '</div>';
                        html += '<div style="float: left;width: 50%">'+value.name_airline+'</div>';                    
                        html += '<div style="float: left;width: 20%">'+value.created.slice(0,10)+'</div>';
                        html += '<div style="float: left;width: 20%">'+value.created.slice(11,16)+'</div></div>';
                    }else{
                        html += '<div class="subtitleinfo" onclick="clickShowInfoMail('+value.id+')">';
                        html += '<div style="float: left;width: 10%">';
                        html += '<input type="checkbox" value="" id="check_all">';
                        html += '</div>';
                        html += '<div style="float: left;width: 50%">'+value.name_airline+'</div>';                    
                        html += '<div style="float: left;width: 20%">'+value.created.slice(0,10)+'</div>';
                        html += '<div style="float: left;width: 20%">'+value.created.slice(11,16)+'</div></div>';
                    }
                    
                });                  
                $('.showdatawithajax').html(html);                               
            }
        }); 
    }

    function submitsendmail(to, info, subject){
        $.ajax({
            url:"<?php echo JURI::base().'index.php?option=com_sfs&task=communication.sendmailuser'; ?>",
            type:"POST",  
            data: {"info":info, "emailTo":to, "subject": subject},              
            dataType: 'json',                
            success:function(data){                     
                if(parseInt(data.ok) == 1){
                    $('.right_content_new').css('display', 'none');
                    $('.loading').hide();$('.btnsend').show();
                    $('.select2-selection__rendered').empty();
                }              
            }
        }); 
    }

    function showreplay(){
        $('.right_content').css('display', 'none');
        $('.right_content_replay').css('display', 'block');
    }
    function shownewmail(){
        $('.right_content').css('display', 'none');
        $('.right_content_replay').css('display', 'none');
        $('.right_content_new').css('display', 'block');
    }

});


function clickShowInfoMail(id,status){    
    jQuery.ajax({
        url:"<?php echo JURI::base().'index.php?option=com_sfs&task=communication.getDetailEmail'; ?>",
        type:"POST",  
        data: {"id": id, "status": status},              
        dataType: 'json',                
        success:function(data){                     
            //console.log(data);    
            if(status > 0){
                $('.subtitleinfo_').css('color', '#333');
            }                      
            jQuery('.right_content_replay').css('display', 'none');
            jQuery('.right_content_new').css('display', 'none');
            jQuery('.right_content').css('display', 'block');
            jQuery('.infoFrom').html(data.emailFrom);
            jQuery('.infoTo').html(data.emailTo);                
            jQuery('.infoHour').html((data.created).substr(11, 5));
            jQuery('.rightinfo').html(data.info); 
            jQuery('.subject').html('Subject:<input type="text" id="subjectload" value="'+data.subject+'" style="width: 400px;margin-left:20px;">');           
        }
    });  
}
</script>



<div class="heading-block clearfix">
    <div class="heading-block-wrap">
        <h3>Communication</h3>
    </div>
</div>

<div id="sfs-wrapper" class="main fs-14" style="padding: 20px;">
<div class="fix-left col-md-4">
    <div id="firstCheck" class="ui toggle checkbox  ">
        <input name="choose_refreshment" type="radio" id="choose_first" class="choose_refreshment_cash" value="0">
        <input name="" type="hidden" id="" value="0">
    </div>
</div>
	<div class="sfs-main-wrapper-none" >                
            <div class="top_content">&#160;</div>
            <div class="left_content">
                <div style="float: left; width: 100%">
                    <div class="info_top_left" id="forward_mail_bt">
                        <img src="<?php echo JRoute::_('media/media/images/communication/email_button.png'); ?>">
                        <span class="text_cont_top">NEW MESSAGE</span>
                    </div>
                    
                    <div class="info_top_left_new">
                        <ul class="nav topnav bold">                                   
                            <li class="dropdown">
                            <a href="#">
                                <img src="<?php echo JRoute::_('media/media/images/communication/report_button.png'); ?>">
                                NEW REPORT
                            </a>
                            <ul style="display: none;" class="dropdown-menu dropdown-menu-com bold">
                                <li><a rel="{handler: 'iframe', size: {x: 900, y: 480}, onClose: function() {}}" href="<?php echo JRoute::_('index.php?option=com_sfs&view=communication&layout=reportmail_ovb&tmpl=component');?>" class="modal icon-16-user">OVB / OPR MESSAGE</a></li>
                                <li><a rel="{handler: 'iframe', size: {x: 900, y: 480}, onClose: function() {}}" href="<?php echo JRoute::_('index.php?option=com_sfs&view=communication&layout=reportmail&tmpl=component');?>" class="modal icon-16-user">DLY / CANX REPORT MESSAGE</a></li>
                                <li><a href="#" style="border:none;">SUB REPORT</a></li>
                            </ul>
                            </li>                                    
                        </ul>
                    </div>  
                    <div class="info_top_refesh">
                        <img src="<?php echo JRoute::_('media/media/images/communication/refresh.png'); ?>" width="25px;">                        
                    </div>            
                </div>
                    <!-- <div class="info_top_left">
                        <a rel="{handler: 'iframe', size: {x: 900, y: 480}, onClose: function() {}}" href="<?php //echo JRoute::_('index.php?option=com_sfs&view=communication&layout=reportmail&tmpl=component');?>" class="modal icon-16-user">

                            <img src="<?php //echo JRoute::_('media/media/images/communication/report_button.png'); ?>">
                            NEW REPORT
                        </a>                        
                    </div> -->                                    
                <div style="float: left; width: 100%;margin: 0 0 10px 10px;">
                    <input type="text" id="search_comm" class="inputSearch" value="" style="width: 310px;" placeholder="Search">
                    <div class="searchform" style="display:none;">
                        <form>
                            <table cellpadding="0" cellspacing="0" border="0" style="float:left; width:100%;">
                                <tr>
                                    <td colspan="2">
                                       <span id="closeSearch">x</span>
                                    </td>
                                </tr>
                                <tr >
                                    <td colspan="2"><input type="" name="" placeholder="From"></td>
                                </tr>
                                <tr>
                                    <td colspan="2"><input type="" name="" placeholder="To"></td>
                                </tr>
                                <tr>
                                    <td colspan="2"><input type="" name="" placeholder="Subject"></td>
                                </tr>
                                <tr>
                                    <td colspan="2"><input type="" name="" placeholder="Text"></td>
                                </tr>
                                <tr>
                                    <td><input type="" name="" placeholder="Flightnumber" style="width:80%"></td>
                                    <td><input type="" name="" placeholder="Date" style="width:80%"></td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <span id="sendSearch">
                                            <i class="fa fa-search" aria-hidden="true"></i>
                                        </span>
                                        <!-- <input type="button" id="sendSearch" value="Search" /> -->
                                    </td>
                                </tr>
                            </table>
                        </form>
                    </div>
                </div>
                <div class="titleinfo">
                    <div style="float: left;width: 10%">
                        <input type="checkbox" value="" id="check_all">
                    </div>
                    <div style="float: left;width: 50%">&#160;</div>
                    <div id="sortDate">By Date 
                        <i class="fa fa-caret-down" aria-hidden="true"></i>
                        <!-- <i class="fa fa-caret-up" aria-hidden="true"></i> -->
                    </div>

                    <div id="sortNewest">Newest
                        <i class="fa fa-long-arrow-down" aria-hidden="true"></i>
                        <!-- <i class="fa fa-long-arrow-up" aria-hidden="true"></i> -->
                    </div>
                </div><!-- &#65514; -->
                <div class="showdatawithajax">
                <?php foreach ($this->emailNotifi as $key => $value) : ?>
                    <?php if($value->status == 1) :  ?>
                        <div class="subtitleinfo_" onclick="clickShowInfoMail(<?php echo $value->id .','.$value->status;?>)" style="color: #01b2c3;">
                            <div style="float: left;width: 10%">
                                <input type="checkbox" value="" id="check_all">
                            </div>
                            <div style="float: left;width: 50%;">
                                <?php echo $value->name_airline;?>
                            </div>
                            <div style="float: left;width: 20%;">
                                <?php echo substr($value->created, 0, 10);?>                                
                            </div>
                            <div style="float: left;width: 20%;">
                                <?php echo substr($value->created, 11, 16);?> </div>                    
                        </div>
                    <?php else: ?>                        
                        <div class="subtitleinfo" onclick="clickShowInfoMail(<?php echo $value->id .','.$value->status;?>)">
                            <div style="float: left;width: 10%">
                                <input type="checkbox" value="" id="check_all">
                            </div>
                            <div style="float: left;width: 50%">
                                <?php echo $value->name_airline;?>
                            </div>
                            <div style="float: left;width: 20%">
                                <?php echo substr($value->created, 0, 10);?>                                
                            </div>
                            <div style="float: left;width: 20%">
                                <?php echo substr($value->created, 11, 16);?> </div>                    
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>  
                </div>                                
            </div>
            <div class="right_content" style="display:none;">                
                <div class="righttitle">
                    <span style="float: left; width: 70%; padding: 8px 20px;">
                        From: <span class="infoFrom"></span><br />
                        To: <span class="infoTo"></span>                            
                    </span>
                    <span style="float: left; width: 30%; margin-top: 15px;">
                        <span id="reply_mail_bt">
                            <img src="<?php echo JRoute::_('media/media/images/communication/reply-email.png'); ?>" width="30px;"></span>
                        <span class="infoHour" style="margin-left: 30px;">17:55</span>
                    </span>
                    <span style="float: left; width: 100%; margin: 15px 0;">
                        <span class="subject" style="margin-left:20px;"></span>
                    </span>
                </div>     
                <div class="rightinfo">
                    
                </div>
                <div class="rightinforeplay">                        
                    Click here to <span id="reply_mail">Reply</span> or <span id="forward_mail">Forward </span>
                </div>       
                        
            </div>  

            <div class="right_content_replay" style="display:none;">   
                <from method="post" active="">             
                    <div class="righttitle">
                        <span style="float: left; width: 70%; padding: 8px 20px;">
                            To: <span class="replayTo"></span><br />
                            Subject: <span class="replaySub"></span>
                        </span>                    
                    </div>                     
                    <div class="rightinfo_replay">
                        <textarea id="emailInfo" name="infoemail" style="width: 100%; height: 180px;"></textarea>
                    </div>
                    <div class="savereplay">                        
                        <input type="button" id="sendemailcomm" value="SEND" />
                    </div>
                    
                </from>               
            </div> 

            <div class="right_content_new" style="display:none;">   
                <from method="post" active="">             
                    <div class="righttitle">
                        <span style="float: left; width: 100%; padding: 8px 20px;">
                            To:                             
                            <input type="text" class="demo-input-local-custom-formatters" id="listselect2" name="blah" />
                        </span>                    
                    </div>    
                    <div class="righttitle">                        
                        <span class="appsubject" style="float: left; width: 100%; padding: 8px 20px;">
                            Subject:                             
                            <input type="text" id="subjectnew" value="" style="float:left;width:400px;" />
                        </span> 
                    </div>
                    <div class="rightinfo_replay_new">                        
                        <textarea id="emailInfo" name="infoemail" style="width: 100%; height: 180px;"></textarea>
                    </div>
                    <div class="savereplay">   
                        <span class="loading"></span> 
                        <span class="btnsend">
                            <input type="button" id="sendemailcommnew" value="SEND" />
                        </span>                    
                        
                    </div>                    
                </from>               
            </div> 
           
    </div>        
</div>

<script type="text/javascript">
$(document).ready(function() {
    var arrData = [];
    var obj = {};
    <?php foreach ($this->emailDSend as $key => $value) : ?>

        obj = {"stationcode": <?php echo '"'.$value->stationcode.'"' ?>,
                "companyname": <?php echo '"'.$value->companyname.'"' ?>,
                "name": <?php echo '"'.$value->name.'"' ?>,
                "email": <?php echo '"'.$value->sitamessage.'"' ?>,
                "name_search": <?php echo '"'.$value->stationcode.' - '.$value->name. ' - ' .$value->sitamessage.'"' ?>};
        arrData.push(obj);
    <?php endforeach; ?>
    
    $(".demo-input-local-custom-formatters").tokenInput(
      arrData, 
      {
          propertyToSearch: "name_search",
          resultsFormatter: function(item){ return "<li><div style='display: inline-block;margin:5px;margin-left:0 auto;'><div class='full_name'>" + item.stationcode + " - " + item.companyname + " - " + item.name + "</div><div class='email'>" + item.email + "</div></div></li>" },
          tokenFormatter: function(item) {
            listArrMail.push(item.email);
            return "<li><p>" + item.stationcode + " - " + item.companyname + " - " + item.name +"</p></li>" },
    });
});
</script>


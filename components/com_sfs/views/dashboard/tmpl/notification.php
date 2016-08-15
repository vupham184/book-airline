<?php
// No direct access.
//Add a comment to this line
defined('_JEXEC') or die;
 
// Load the tooltip behavior.
// JHtml::_('behavior.tooltip');
// JHtml::_('behavior.formvalidation');
// JHtml::_('behavior.keepalive');
// JHtml::_('behavior.modal');
$user = JFactory::getUser();
?>
<style type="text/css">
body{
    border: none;
}
a.close{display: none;}
h4.alert-heading{display: none;}
h2{margin-left: 10px; color: #6c6d6d;}
hr{border: none; border-top: 1px solid #ced1d2;}
textarea{box-shadow: 1px 2px 4px #888888; border:none;}
input#searchEmail{box-shadow: 1px 2px 4px #888888; border: none;}
#system-message{display:none;}
#mainNotifi{
    float:left;
    width: 100%;
    height: 100%;    
    border:1px solid #dfdfdf; color:#000; border-radius: 3px; padding: 10px;background:#d5e2e8; 
}

.infosendmail{

}

.infoEmail{
    margin-top: 20px;
}

#submitForm{
    float: left;
    width: 130px;
    background: #6799c8; 
    padding: 8px 14px; color: #ffffff; font-weight:bold; cursor:pointer;
}

.yesnotifi{
    margin: 15px 15px 20px 15px;
    background: #ffffff;
    padding: 10px;
    box-shadow: 2px 3px 5px #888888;
}

.nonotifi{
    padding: 10px; 
    margin: 15px 15px 15px 30px; 
    background: #ffffff;
    box-shadow: 2px 3px 5px #888888;
}

.titleNotifi{
    text-align: center;
    font-size: 16px;
    color: #6c6d6d;        
    height: 40px;
}
.subtitle{
    position: fixed;
    background: #d5e2e8;
    width: 440px;
    height: 50px;
    top:0;
    line-height: 50px;
    border-bottom: 1px solid #ced1d2;
}

.notification{display: none; text-align: center; 
    padding: 10px; background: #d6dadc;
    border-radius: 4px;}

span.cssValueFilter{
    background: #6799C8;
    padding: 5px 7px;
    margin-left: 10px;
    border-radius: 3px;
}
.viewArrList{
    margin-top: 10px;
}
#addFilterNotifi{
    background-color: #FF8806;
    color: #ffffff;
    padding: 11px 8px;
    margin-left: 10px;
    margin-right: 20px;
    margin-top: -2px;
}
</style>
<link rel="stylesheet" href="<?php echo JUri::root() . 'media/media/js/jquery-ui.css'; ?>">
<script src="<?php echo JUri::root() . 'media/media/js/jquery-1.10.2.js'; ?>"></script>
<script src="<?php echo JUri::root() . 'media/media/js/jquery-ui-11.4.js'; ?>"></script>
<script type="text/javascript">
    <?php      
        $arr = array();              
        foreach ($this->emailHotel as $key => $value) {  
            $arr[] = $value->email;            
        }
        
    ?> 
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
        $( "#searchEmail" ).autocomplete({
            source: arrList
        });
    });
    
    jQuery(function($){
        $("#submitForm").click(function(){            
            var info    = document.getElementById("infoName").value;
            var emailTo = document.getElementById("searchEmail").value;
            if(!info || !emailTo){
                $(".notification").css({'display':'block','color':'red'});
                $(".notification").html('Please, enter info message or email');
                window.scrollTo(0,0);
                return false;
            }

            $("#waiting").css({'display': 'block'});                        
            $.ajax({
                url:"<?php echo JURI::base().'index.php?option=com_sfs&task=dashboard.sendmailuser'; ?>",
                type:"POST",  
                data: {"info":info, "emailTo":emailTo},              
                dataType: 'json',                
                success:function(data){                     
                    if(data.ok == "1"){
                        $("#waiting").css({'display': 'none'}); 
                        document.getElementById("infoName").value = "";
                        document.getElementById("searchEmail").value = "";
                        $(".notification").remove();$(".headnotifi").append("<div class='notification'></div>");
                        $(".notification").css({'display':'block','color':'#61bd4f'});
                        $(".notification").html('Your sent mail success');
                        window.scrollTo(0,0);
                        return false;
                    }                   
                }
            }); 
        }); 

        
        
        
        $('.viewArrList').on('click','.removeThis',  function(){
            var index = $('.viewArrList .removeThis').index($(this));
            arrListSearch.splice(index, 1);            
            $(this).parent().remove(); 
            filterData(arrListSearch);
        });

        var arrListSearch = [];

        <?php if(!empty($_SESSION['data']) ): ?>
            arrListSearch = <?php echo $_SESSION['data']; ?>;             
            filterData(arrListSearch);     
        <?php else: ?>   
            $('.infoGetFilter').css('display', 'block');    
        <?php endif; ?>
        
        $('#addFilterNotifi').on('click', function(){            

            var dataFilter = $('#inputSearchNotifi').val();                                
            $('#inputSearchNotifi').val("");
            var html = $("<span class='cssValueFilter'>"+dataFilter+"<a href='#' class='removeThis' style='color:#ffffff; padding-left: 5px;'>X</a></span>");
                $('.viewArrList').append(html);

            arrListSearch.push(dataFilter);            
            filterData(arrListSearch);   
        });

        $('#clearAllFilter').on('click', function(){
            arrListSearch = [];
            $('.viewArrList').empty();
            filterData(arrListSearch);
        });

        function filterData(dataArr){
            $.ajax({
                url:"<?php echo JURI::base().'index.php?option=com_sfs&task=dashboard.getFilter'; ?>",
                type:"POST",  
                data: {"info":dataArr},              
                dataType: 'json',                
                success:function(data){  
                                                        
                    $('.infoGetFilter').empty();
                    var html = "";
                    var user = "<?php echo "@" . $user->username; ?>";
                    $.each(data, function(index, value) {
                        html += '<div class="nonotifi">';
                        html += '<p style="font-weight: 600;">'+value.name_airline+'</p>  ';
                        html += '<p style="color:#01b2c3; font-weight: 600;">'+user+'</p>';
                        html += '<p class="filterContent">'+value.info+'</p>';
                        html += '</div>'; 
                    });
                    $('.infoGetFilter').css('display', 'block');
                    $('.infoGetFilter').html(html);
                }
            }); 
        }
       
    });
    
</script>


<div id="mainNotifi">    
    <div>
        <input type="text" id="inputSearchNotifi" value="" style="width: 200px; margin-left: 15px;" >
        <a href="#" id="addFilterNotifi">Add Filter</a>
        <a href="#" id="clearAllFilter">Clear all filters</a>
        <div class="viewArrList">
             <?php foreach (json_decode($_SESSION['data']) as $key => $value) : ?>
                <span class='cssValueFilter'><?php echo $value; ?><a href='#' class='removeThis' style='color:#ffffff; padding-left: 5px;'>X</a></span>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="infoGetFilter" >
        <?php foreach ($this->emailNotifi as $key => $value) : ?>

            <?php if($value->status == 1) :  ?>
                <div class="yesnotifi">
                    <p>Name: <?php echo $value->name_airline ; ?></p>
                    <p>From: <?php echo $value->emailFrom ; ?></p>
                    <p>To: <?php echo $value->emailTo; ?></p>            
                    <p>On: <?php echo $value->created; ?></p>

                    <div>
                        <p style="color:#01b2c3; font-weight: 600;"><?php echo "@" . $user->username; ?></p>
                        <p><?php echo str_replace("\n", "<br />", $value->info); ?></p>
                    </div>
                </div>
            <?php else: ?>
                <div class="nonotifi">      
                    <p style="font-weight: 600;"><?php echo $value->name_airline;?></p>   
                    <p style="color:#01b2c3; font-weight: 600;"><?php echo "@" . $user->username; ?></p>
                    <p class="filterContent"><?php echo str_replace("\n", "<br />", $value->info); ?></p>           
                </div>
            <?php endif; ?>
        <?php endforeach; ?>    
    </div>
    <?php if(count($this->emailNotifi) > 0) : ?> <hr> <?php endif; ?>
    
    <form method="post" action="">
        <div class="headnotifi"><div class="notification"></div></div>        
        <div style="padding: 0 15px 0px 15px;">
            <h2>Sent message</h2>
            <div class="infosendmail">                
                <textarea id="infoName" name="notifi[info]" style="width: 100%; height: 80px; padding: 10px; font-family:verdana, arial, sans-serif; font-size: 12px;" 
                placeholder="Your message here"></textarea>
            </div>
            <div class="infoEmail">                
                <input type="text" id="searchEmail" name="notifi[emailTo]" style="font-size: 12px; height: 30px; padding-left:10px;" 
                placeholder="Search contacts or and email address">                
            </div>
            <div style="display:block" class="loading" ></div>
            <div style="margin-top: 20px">
                
                <input type="button" id="submitForm" value="SEND" />
            </div>
            <input type="hidden" name="task" value="dashboard.sendmailuser" />
            
        </div> 
    </form>
</div>


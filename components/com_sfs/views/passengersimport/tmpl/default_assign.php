<?php 
$app = JFactory::getApplication();
$link_Img = JURI::root().'media/media/images/select-pass-icons';
$document = JFactory::getDocument();
?>
<style>
.content-assign .fix-left{
    margin-left:0;
}
.content-assign .description{
    padding-top: 20px;
    float: left;
}
.content-list-partner{
    height: 300px;
    overflow-y: auto;   
   /*background: #fff;*/
}
.txt-right{
    text-align: right;
    padding-right: 10px;
}
.le{background: #eff0f1; border-bottom: 1px solid #999; float:left; width: 100%; padding: 2px 5px; cursor: pointer;}
.chan{background: #fff; border-bottom: 1px solid #999; float:left; width: 100%; padding: 2px 5px; cursor: pointer;}
div.active{background: #25649F; color: #fff;}
</style>

	<div class='head'>
    	<div>
        	<a href="javascript:void(0);" class="sfs-button pull-right close-content" style="font: inherit; font-size: 14px; margin-top: -3px; margin-right: 5px">Close</a>
        </div>
		<div class="icon fix-left">
			<div><strong>Assign to local<br> station</strong></div>
			<img src="<?php echo $link_Img.'/assign.png' ?>" alt="">
		</div>
		<div class="description">
			<div class="mg0 float-n">Here you can assign the guests to be handled by your local station<br> manager. He will get an email and or site message requesting to log in<br> and handle the guests</div>
            <div>
			<i>Please note the local station has restricted access and can only assign <br> services that are selected by you foe the these passengers</i>
            </div>
		</div>
		<div class="clear"></div>
	</div>
	<div class="content">
    	<table>
            <tr>
                <td style="width:250px; vertical-align:top;" >
                	<i>Stations in your selection</i>
                    <ul class="mg0 content-list-passengers list-station">
                    	
                    </ul>
                </td>
                <td style="width:400px; vertical-align:top;" class="border-left">
                	<div style="width:100%;">
                    	<table width="100%">
                        	<tr>
                            	<td style="width:110px;">
                                	<div style="width:70px;"><i>Assign to:</i></div>
                                </td>
                                <td style="width:300px;">
                                	<span>VARADERO Tour Company</span><br><span>mr Michel Fabinder:etc etc etc</span>
                                </td>
                                <td style="width:110px;">
                                	<button type="button" id="btn-assign" class="small-button" style="width:auto;">ASSIGN</button>
                                </td>
                            </tr>
                            <tr>
                            	<td>
                                </td>
                                <td>
                                	<input id="search_key_partner" type="text" style="width:250px;" >
                                </td>
                                <td style="width:110px;">
                                	<button id="btn-search-partner" type="button" class="small-button" style="width:auto;">Search</button>
                                </td>
                            </tr>
                            <tr>
                            	<td>
                                </td>
                                <td>
                                    <div class="content-list-partner">List of all stations and information</div>
                                   <!--  <select id="list-partner" name="list-partner[]" class="content-list-partner" multiple>
                                        <option value="">List of all stations and information</option>
                                    </select> -->
                                	<!--<ul class="mg0 content-list-partner" style="background-color:#fff; border:1px solid #F7F7F7; height:250px;">
                                    	<li class="liststyle-none mg0">
                                            List of all stations and information
                                        </li>
                                    </ul>-->
                                </td>
                                <td style="width:110px;">
                                </td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
        </table>
        
			
	</div>
<script type="text/javascript">
jQuery(function( $ ){
    jQuery('.open-form-content-assign') .click(function(){
        pas_id = getPassengerCheck($);
        if(pas_id.length>0){
                pas_id.each(function(index,element){
                        checkpassengersamegroup(jQuery('#'+index).attr('data-group'));
                });  
                pas_id = getPassengerCheck($);
                jQuery('.list-station').html('');
                var show_user = true;
                var group_user='';
                pas_id.each(function(index,element){                   
                    var data =eval('(' +jQuery('#'+index).attr('data-info') + ')');
                    if(jQuery('.station-'+data.dep).length==0){
                        jQuery('.list-station').append('<li class="mg0 liststyle-none txt-right station-'+data.dep+'">'+data.dep+'</li>');
                    }
                    if(jQuery('.station-'+data.arr).length==0){
                        jQuery('.list-station').append('<li class="mg0 liststyle-none txt-right station-'+data.arr+'">'+data.arr+'</li>');
                    }

                    //check show partner 
                    if(jQuery('#'+index).attr('data-groupuser')==''){
                        show_user=false;
                    }
                    if(jQuery('#'+index).attr('data-groupuser')!=''){
                        if(group_user==''){
                            group_user=jQuery('#'+index).attr('data-groupuser');
                        }
                        else{
                            if(group_user!=jQuery('#'+index).attr('data-groupuser')){
                                show_user=false;
                            }
                        }
                    }
                }); 
                if(show_user==true && group_user!=''){
                    // get list partner 
                        var users= group_user.split(',');
                        jQuery.ajax({
                        url:"<?php echo JURI::base().'index.php?option=com_sfs&task=ajax.searchPartner&format=raw'; ?>",
                                type:"POST",
                                data:{
                                    search_key:jQuery('#search_key_partner').val(),
                                },
                                dataType: 'json',
                                success:function(response){
                                    jQuery('.content-list-partner').html('');
                                    if(response['successful']==1){ 
                                        jQuery.each( response['data'], function( key, value ) {
                                                var selected='';
                                                jQuery.each(users, function( index, value_id ) {
                                                        if(value.user_id==value_id){
                                                            selected='selected';
                                                        }
                                                });
                                                jQuery('.content-list-partner').append('<option  data-partner-id="'+value.user_id+'" value="'+value.user_id+'" '+selected+'>'+value.companyname+' '+value.name+'</option>');
                                        });
                                    }else{
                                        jQuery('.content-list-partner').html('<option value="" >No data</option>');
                                    }
                                }
                    });
                }
            }
    });
    var dataResult = [];
    var dataChooseUser = [];
    jQuery('#btn-search-partner').click(function(){
        jQuery.ajax({
            url:"<?php echo JURI::base().'index.php?option=com_sfs&task=ajax.searchPartner&format=raw'; ?>",
                    type:"POST",
                    data:{
                        search_key:jQuery('#search_key_partner').val(),
                    },
                    dataType: 'json',
                    success:function(response){
                        dataResult = response['data'];
                        jQuery('.content-list-partner').html('');
                        if(response['successful']==1){ 
                            jQuery.each( response['data'], function( key, value ) {
                              //alert( value.airlineid );
                                var row = "le";
                                if(key%2 == 0){
                                    row = "chan";     
                                }
                                var email = "Email: " + value.email; 
                                if(value.typ == "EMAIL"){
                                    email = "Fax: " + value.fax;
                                }
                                var html = '<div id="searchshow" class="'+row+'"><span class="showsearch">' + value.stationcode+' - '+value.carrier+' - '+value.department+' - '+value.grouptype+' - '+value.category;
                                html += '<br />'+value.name+' - Name';
                                html += '<br />'+email;
                                html += '</span></div>';
                                jQuery('.content-list-partner').append(html);
                            });
                        }else{
                            jQuery('.content-list-partner').html('<option value="" >No data</option>');
                        }
                    }
        });
    });
    

    jQuery("div.content-list-partner").delegate('#searchshow', 'click', function(event) {
        $('div').removeClass('active');
        $(this).addClass('active');
        dataChooseUser = [];
        var data = dataResult[$(this).index()];        
        dataChooseUser.push(data);
    });

    jQuery('#btn-assign').click(function(){
        pas_id = getPassengerCheck($);        
        if(pas_id.length>0){            
            // var list_partner = [];
            // $('#list-partner option').each(function(i) {            
            //         if (this.selected == true) {                    
            //             list_partner.push(this.value);
            //         }
            // });
            if(dataResult.length>0){
                jQuery.ajax({
                    url:"<?php echo JURI::base().'index.php?option=com_sfs&task=ajax.SavePassengerPartner&format=raw'; ?>",
                    type:"POST",
                    data:{
                        list_partner:dataChooseUser,
                        pas_id:pas_id
                    },
                    dataType:'json',
                    success:function(response){
                        if(response.successful == "1"){
                            alert('Assign success!');
                            document.location.reload(true);
                        }else{
                            alert('Assign fail!');
                        }
                    }
                });
            }else{
                alert("Select partner, Please.");  
            }
            
        }
    });

});
</script>
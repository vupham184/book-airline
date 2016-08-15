 <?php 
 /*begin code CPhuc*/
	$link_Img = JURI::root().'media/media/images/select-pass-icons';
/*end code CPhuc*/
?>
 <style>
 	.col1{
		width:150px;
	}
	.fix-left{
		float: left;
	}
	
	.clear{
		clear: both;
	}
    .description{
        font-weight: normal;
    }
	/*
	.container,.add-comment{
		position: absolute;
	}
	.description{
		position: relative;
		right: 333px;
	}
	 .passenger{
		position: relative;
		right: 339px;
	} 
	*/
	p,span,img,input{
		display: inline;
		padding-left: 3px;
		vertical-align: middle;

	}
	.ul-pas{
		margin:0px;
		width:100%;
		text-align:left;
	}
	.ul-pas li{
		list-style:none;
		margin:0px;
	}
	.w320{
		width:320px;
	}
	.wauto{
		width:auto;
	}
	.m0{
		margin:0px;
	}
    .btn-share-room{

    }
    .create-group-left{
        float: left;
        width: 870px;
        padding-top: 30px;
    }
    .create-group-right{
        float: left;       
    }
    .tb-create-group td,.tb-create-group th{
    font-weight: normal;}
    .tb-create-group th{
        padding: 0px 5px;
    }
    .td-center{
        text-align: center;
    }
    .ul-pas .pas-name{
        width: auto;
    }
    .ul-pas .pnr{
        padding-left: 0;
    }
    .create-group-right .small-button.fix-left.btn-share:hover{
        background-color: #FF8806!important;
        color: #fff!important;
    }
    .btn-share-room span{
        color: #fff!important;
    }
    .btn-share-room span.has-Tip{
        color: #000!important;
    }
    .tb-create-group span{
        color: #000!important;
    }
    .create-group-passenger .fix-left{
        margin: 0!important;
    }
    .fix-left-group{
        float: left;
        display: inline;
    }
 </style>
<h3 class="m0">
	<a href="javascript:void(0);" class="sfs-button pull-right close-content" style="font: inherit; font-size: 14px; margin-top: -3px; margin-right: 5px">Close</a>
</h3>
<div class="create-group-left">
    <div class="icon fix-left col1 text-center" style="height: 95px;">
        <div>Create Group</div>
        <img src="<?php echo $link_Img.'/group.png' ?>" alt="">
    </div>    
    <div class="description">
        <div>People travelling together in one group often share a room and taxi or other service you assign this on this page</div>
        <div>
            <img src="<?php echo $link_Img.'/alert-small.png' ?>" alt="">
            <i>Bellow guests are in the same group as they travel with the same PNR plesa check if they are sharing a room together</i>
        </div>
    </div>
    <div style="clear: both;"></div>
    <div class="add-commnent fix-left" style="font-weight: normal;margin: 0!important;">
    <div class="feature create-group-passenger">
        <div class="fix-left-group" >
            <table class="tb-create-group">
                <tr>                    
                    <th ></th>
                    <th ></th>
                    <th >Travel together</th>                    
                    <th ></th>
                </tr>
                <tbody class="ul-pas">
                    
                </tbody>
            </table>           
        </div>
        <div class="create-group-right" style="width: 150px;float: right;">
            <div class="fix-left wauto btn-share-room" style="float: right;">
                    <div>
                        <table>
                            <tr>
                                <td>
                                <a class="small-button fix-left btn-share" id="btn-share-room" style="text-transform:none; padding:5px;width:auto; text-align:left;"href="javascript:void(0);">          
                                    <div style="width: 75px;height: 40px;float: left;font-weight: normal;">
                                        <span class="fix-right">Travel together</span>
                                    </div>
                                </a>
                                <span class="has-Tip" style="width: 22px; float: left;">
                                        <div class="tooltip-s">
                                            <div class="tooltip-content" style="cursor:default;font-weight: normal;">
                                                Passengers that have the same PNR are always travelling together so they can not be ungrouped.
                                                <br/>
                                                They can however choose to share the hotelroom or choose to stay in seperate rooms. If they stay in seperate rooms we will always try to book them in the same hotel but unfortunately we cannot guarantee this.
                                            </div>
                                        </div>
                                        <img style="margin-top: 20px;margin-left: 10px;" class="fix-left" src="<?php echo $link_Img.'/help.png'?>" alt="">
                                    </span>
                                </td>               
                            </tr>                            
                            <!--<tr>
                                <td>
                                    <a class="small-button fix-left btn-share" id="btn-seperate-rooms" style="text-transform:none; padding:5px; width:auto; text-align:left;" href="javascript:void(0);">     
                                    <img style="float: left;margin-top: 5px;" class="fix-left" src="<?php echo $link_Img.'/hotel.png'?>" alt=""><img  style="float: left;margin-top: 5px;" class="fix-left" src="<?php echo $link_Img.'/hotel.png'?>" alt=""><div style="width: 83px;float: left;line-height: 13px;font-weight: normal;"><span class="fix-right">Travel together but will need seperate rooms</span></div>
                                    
                                    </a>
                                </td>
                            </tr>-->
                            <tr>
                                <td>
                                    <a class="small-button fix-left btn-share" id="btn-remove-pass" style="text-transform:none; padding:5px; width:auto; text-align:left;" href="javascript:void(0);">  
                                        <div style="font-weight: normal;width: 75px;height: 40px;">
                                            <span class="fix-right">Do NOT travel together</span>
                                        </div>                              
                                    </a>    
                                    <span class="has-Tip" style="width: 22px; float: left;">
                                        <div class="tooltip-s">
                                            <div class="tooltip-content" style="cursor:default;font-weight: normal;">
                                                Passengers that have the same PNR are always travelling together so they can not be ungrouped.
                                                <br/>
                                                They can however choose to share the hotelroom or choose to stay in seperate rooms. If they stay in seperate rooms we will always try to book them in the same hotel but unfortunately we cannot guarantee this.
                                            </div>
                                        </div>
                                        <img style="margin-top: 20px;margin-left: 10px;" class="fix-left" src="<?php echo $link_Img.'/help.png'?>" alt="">
                                    </span>
                                </td>                        
                            </tr>
                            <!--<tr>
                                <td>
                                    <a class="small-button fix-left btn-share" id="btn-not-share" style="text-transform:none; padding:5px; width:auto; text-align:left;" href="javascript:void(0);">     
                                        <div style="font-weight: normal;width: 85px;">
                                            <span class="fix-right">Do NOT share a room together</span>
                                        </div>
                                    </a>    
                                    
                                    <span class="has-Tip" style="width: 22px; float: left;">
                                        <div class="tooltip-s">
                                            <div class="tooltip-content" style="cursor:default;font-weight: normal;">
                                                Passengers that have the same PNR are always travelling together so they can not be ungrouped.
                                                <br/>
                                                They can however choose to share the hotelroom or choose to stay in seperate rooms. If they stay in seperate rooms we will always try to book them in the same hotel but unfortunately we cannot guarantee this.
                                            </div>
                                        </div>
                                        <img style="margin-top: 20px;margin-left: 10px;" class="fix-left" src="<?php echo $link_Img.'/help.png'?>" alt="">
                                    </span>
                                </td>                        
                            </tr>-->
                        </table>                                
                    </div>            
                        
                </div>
        </div>
        <div style="width: 180px"></div>
    </div><!--End feature-->
</div><!--End add-commnent-->
<div style="width: 180px"></div>
</div>

<script type="text/javascript">
    jQuery(function($){
            $('#btn-share-room').click(function(){
                var pas_id_group=[];
                //var pas_id_chk='';
                //var group_id=$('#group-service').val();
                $('.ul-pas input[type="checkbox"]').each(function(index, element) {
                if ( $(this).is(':checked') ) {
                    pas_id_group.push($(this).val());
                }
                });
                //pas_id_chk = getPassengerCheck($);
                jQuery.ajax({
                    url:"<?php echo JURI::base().'index.php?option=com_sfs&task=ajax.createGroupPassenger&format=raw'; ?>",
                    type:"POST",
                    data:{
                        //pas_id_sha:     pas_id_sha,
                        pas_id_group:pas_id_group,
                        //pas_id_group:   pas_id_sha,
                        //group_id:group_id
                    },
                    dataType: 'json',
                    success:function(data){
                        //alert(data['successful']);
                        if(data['successful']==1){
                            alert('Create group successful!');
                            document.location.reload(true);
                        }
                    }
                });
            });
            $('#btn-remove-pass').click(function(){
                var pas_id_rm=[];
                var group_id=$('#group-service').val();
                $('.ul-pas input[type="checkbox"]').each(function(index, element) {
                    if ( $(this).is(':checked') ) {                        
                        pas_id_rm.push($(this).val());
                    }
                });
                
                jQuery.ajax({
                    url:"<?php echo JURI::base().'index.php?option=com_sfs&task=ajax.removePassengerInGroup&format=raw'; ?>",
                    type:"POST",
                    data:{
                        pas_id_rm: pas_id_rm,                        
                        group_id:group_id
                    },
                    dataType: 'json',
                    success:function(data){
                        if(data['successful']==1){
                            $.each(pas_id_rm,function(index,value){
                                $('#tr-'+value).remove();
                            });
                        }
                    }
                });
            });

           /* $('#btn-seperate-rooms').click(function(){
                var pas_id_sep=[];
                var pas_id_chk='';
                var group_id=$('#group-service').val();
                $('.ul-pas input[type="checkbox"]').each(function(index, element) {
                    if ( $(this).is(':checked') ) {                        
                        pas_id_sep.push($(this).val());
                    }
                });   
                //get passenger check
                pas_id_chk = getPassengerCheck($);
                jQuery.ajax({
                    url:"<?php echo JURI::base().'index.php?option=com_sfs&task=ajax.processTravelTogetherSeperateRoom&format=raw'; ?>",
                    type:"POST",
                    data:{
                        pas_id_sep: pas_id_sep,
                        pas_id_group:   pas_id_chk,
                        group_id:group_id
                    },
                    dataType: 'json',
                    success:function(data){
                        
                    }
                });         
            });
            $('#btn-not-share').click(function(){
                var pas_id_not_sha=[];
                var pas_id_chk='';
                var group_id=$('#group-service').val();
                $('.ul-pas input[type="checkbox"]').each(function(index, element) {
                    if ( $(this).is(':checked') ) {                        
                        pas_id_not_sha.push($(this).val());
                    }
                });   
                //get passenger check
                pas_id_chk = getPassengerCheck($);
                jQuery.ajax({
                    url:"<?php echo JURI::base().'index.php?option=com_sfs&task=ajax.processNotShareRoom&format=raw'; ?>",
                    type:"POST",
                    data:{
                        pas_id_not_sha: pas_id_not_sha,
                        pas_id_group:   pas_id_chk,
                        group_id:group_id
                    },
                    dataType: 'json',
                    success:function(data){
                        
                    }
                }); 
            });*/
        });     

</script>
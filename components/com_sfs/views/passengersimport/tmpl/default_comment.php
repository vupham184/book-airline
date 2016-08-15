<?php 
$app = JFactory::getApplication();
$link_Img = JURI::root().'media/media/images/select-pass-icons';
$document = JFactory::getDocument();

 ?>
 <style>
	/*.fix-left{
		float: left;
		padding: 20px;
	}
	.fix-right{
		float: right;
		padding: 20px;
		margin-right: 200px;
	}*/
	.mgl20{
		margin-left:20px;
	}
	.mgb20{
		margin-bottom:20px;
	}
	
 </style>
 	<div>
        <a href="javascript:void(0);" class="sfs-button pull-right close-content" style="font: inherit; font-size: 14px; margin-top: -3px; margin-right: 5px">Close</a>
    </div>
 	<div class="icon fix-left">
 		<div><strong>Add an internal<br>comment</strong></div>
 		<img src="<?php echo $link_Img.'/comment.png' ?>" alt="">
 	</div>
 	<div class="add-commnet fix-right mgl20">
 		<div class="mgb20">
        Any comments are only for internal use and will be shown on the guests<br> details page of the guests you have selected
 		</div>
 		<div class="" style="position:relative;">
 				<textarea id="internal_comment" class="" style="width:70%; height:60px;" 
                placeholder="Put your internal commnent here"></textarea>
				<br>
				<i >Currently <span id="count-characters-comment" >0</span> characters</i>
                <span class="loading pull-right save-comment-loading"></span>
				<a class="small-button save-comment" href="<?php ?>" style="float: right;width: 155px;">POST COMMENT</a>
				<input type="hidden" name="passenger_ids_comment" id="passenger_ids_comment" value="" />
 		</div>
 	</div>
 	<!--<script type="text/javascript">
 		jQuery(function($){
 			$('.save-comment').click(function(){
				jQuery.ajax({
	                url:"<?php echo JURI::base().'index.php?option=com_sfs&task=ajax.addCommentPassenger&format=raw'; ?>",
	                type:"POST",
	                data:{
	                    passenger_ids_comment: jQuery('#passenger_ids_comment').val(),	                    
	                   	internal_comment: jQuery('#internal_comment').val()	                    
	                },
	                dataType: 'text',
	                success:function(response){
	                    
	                }
            	});
 			});
 		}); 		
 	</script>-->

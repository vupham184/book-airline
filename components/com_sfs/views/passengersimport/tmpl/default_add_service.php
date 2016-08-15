<?php 
$app = JFactory::getApplication();
$link_Img = JURI::root().'media/media/images/select-pass-icons';
$document = JFactory::getDocument();
?>
<style>
	/*.container{
		overflow: hidden;
	}*/
	/*
	.fix-right{
		float: right;
		margin-left: 30px;
	}
	.fix-left{
		float: left;
		padding-left: 30px
	}
	.clear{
		clear: both;
	}
	*/
	.col-md-3{
		width: 25%;
	}
	.col-md-9{
		width: 75%;
	}
	.list-passengers{
		border-right: 2px solid #4DBBC7;
		min-height: 320px;
	}
	.head,.content{
		padding-top: 30px;
	}
</style>
<div class="container">

	<div class='head'>
		<div class="icon fix-left">
			<h3><strong>Add/Change<br>service</strong></h3>
			<img src="<?php echo $link_Img.'/add-service.png' ?>" alt="">
		</div>
		<div class="description fix-left">
			<h3>With this service you can send a message to the selected guests,please<br> note that only the guests with an Cell phone number or email can recieve<br> this message</h3>
			<i>Please note all selected guests will only be eligable for the services selected<br>here the previous selected services will automatically be deselected</i>
		</div>
		<div class="clear"></div>
	</div>
	<div class="clear"></div>
	<div class="content">
		

	</div>
</div>
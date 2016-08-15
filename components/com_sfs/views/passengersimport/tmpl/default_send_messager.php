<?php 
$app = JFactory::getApplication();
$link_Img = JURI::root().'media/media/images/select-pass-icons';
$document = JFactory::getDocument();
?>
<style>
	/*.container{
		overflow: hidden;
	}*/
	
	.fix-left{
		float: left;
		padding-left: 0px
	}
	.clear{
		clear: both;
	}

	.head{
		border-bottom: 4px solid #4DBBC7;
	}
	.head,.content{
		padding-top: 0px;
	}
	.content-list-passengers{
		height:300px;
		overflow-y: scroll;
	}
	.mg0{
		margin:0px;
	}
	.mgt{
		margin-top:25px;
	}
	
	.mgl-20{
		margin-left:20px;
	}
	.border-left{
		border-left: 2px solid #4DBBC7;
	}
	.mgt15{
		margin-top:15px;
	}
	.pdt10{
		padding-top:10px;
	}
	.float-n{
		float:none;
	}
	.liststyle-none{
		list-style:none;
	}
</style>

	<div class='head'>
    	<div>
        	<a href="javascript:void(0);" class="sfs-button pull-right close-content" style="font: inherit; font-size: 14px; margin-top: -3px; margin-right: 5px">Close</a>
        </div>
		<div class="icon fix-left">
			<span><strong>Send message<br> to guests</strong></span><br />
			<img src="<?php echo $link_Img.'/messge.png' ?>" alt="">
		</div>
		<div class="description fix-left">
			<div class="mg0 pdt10 float-n">With this service you can send a message to the selected guests,please<br> note that only the guests with an Cell phone number or email can recieve<br> this message
            </div>
            <div class="float-n">
			<i>Please note all selected guests will only be eligable for the services selected<br>here the previous selected services will automatically be deselected</i>
            </div>
		</div>
		<div class="clear"></div>
	</div>
	<div class="clear"></div>
	<div class="content mg0 mgt15 ">
        <table>
            <tr>
                <td style="width:250px; vertical-align:top;" >
                    <ul class="mg0 content-list-passengers">
                    </ul>
                </td>
                <td style="width:400px; vertical-align:top;" class="border-left">
                	<div style="width:100%;">
                    <input type="hidden" id="telphone" name="tel" />
                    <textarea id="sms_text_message" style="height:80px; width:100%; margin-bottom:10px;" maxlength="150" placeholder="Text message max 150 characters,email may be longer"></textarea>
                    </div>
                    <div style="width:100%; position:relative;">
                    <i class="fix-left">Currently <span id="count-characters">150</span> characters</i>
                    <span class="loading pull-right send-sms-loading"></span>
                    <a class="small-button pull-right send_SMS_text_message" href="<?php ?>" style="width: 143px;margin-left: 48px;
                    ">SEND MESSAGER</a>
                    </div>
                </td>
            </tr>
        </table>
	</div>

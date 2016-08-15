<?php

    defined('_JEXEC') or die;

    /*
    JHtml::_('behavior.keepalive');
    JHtml::_('behavior.tooltip');
    JHtml::_('behavior.modal');
    JHtml::_('behavior.formvalidation');
    */
    
    JHTML::_('script', JURI::root() . 'administrator/components/com_sfs/assets/js/jquery.validate.min.js');
    $user = JFactory::getUser();
	$session= JFactory::getSession();
?>
<script src="http://192.168.11.168:8003/sfs/components/com_sfs/assets/js/management.js" type="text/javascript"></script>
<script src="/sfs/media/system/js/modal.js" type="text/javascript"></script>

<script type="text/javascript">
jQuery(document).ready(function(){

	var validator = jQuery("#roomForm").validate({
												 
		        errorClass: "jquery_error",
        errorElement: "div",
        wrapper: "div",  // a wrapper around the error message
        errorPlacement: function(error, element) {
            var position   = element.position();
			var left       = parseInt(position.left) + element.width();
            error.insertBefore(element)
            error.addClass('message');  // add a class to the wrapper
            error.css('position', 'absolute');
            error.css('left', left+'px');
            error.css('top', position.top+'px');
        },
        // rules for field names
        rules: {

                recive_username: "required", 
                recive_password: "required",
				renew_password: {
									equalTo: "#new_password"

								}

        },

        // inline error messages for fields above
        messages: {

                recive_username: "Enter your user name", 
                recive_password: "Enter your password ",
				renew_password: "Your pass word not match",

        }

    });

});
function checkuser()
{
	var ajax= new XMLHttpRequest();
	
	user = document.getElementById('new_user').value;
	
	ajax.open("GET","index.php?option=com_sfs&task=airlineregister.checkuser&user="+user,true);
	ajax.send(null);
	ajax.onreadystatechange = function () 
	{
		if (ajax.readyState == 4)
		{
			if ((ajax.responseText) == 0)
			{
			document.getElementById('submit').disabled='disabled';
			document.getElementById('respont').innerHTML = 'Not valid';	
			return false;
			}
			else
			{	
			document.getElementById('submit').disabled='';
			document.getElementById('respont').innerHTML = 'Ok';		
			return true;
			}
			
		}
	}
	
};
</script>
<div class="registration>">
    <div class="com-hotel">
        <div id="form-signup">
        
            <!-- Nearest airport form -->
            <form id="roomForm" name="roomForm" action="<?php echo JRoute::_('index.php?option=com_sfs'); ?>" method="post" class="form-validate" >
                    <h3>Personal User and Password</h3>
                 <div class="hotel-area">
                  <div class="hotel-management hotel-form">                       
                  		 <fieldset class="airport" style="padding: 40px 60px !important; width: 770px;">
                                    
                            <table width="100%" border="0" cellpadding="0" cellspacing="0" class="table-hotel-2" >
                            
                              <tr>
                                <td width="235"><h3 style="text-align:left;">Username and password</h3></td>
                                <td ><p class="table-left">Your recive username:</p></td>
                                <td ><input type="text" name="recive_username" id="recive_username"  value="<?php //echo $session->get('email') ?>"  /> </td>
                              </tr>
                              <tr>
                                <td>&nbsp;</td>
                                <td><p class="table-left">Your recive password:</p></td>
                                <td><input type="text" name="recive_password" id="recive_password"  value="<?php //echo $session->get('password') ?>"   /></td>
                              </tr>
                              <tr>
                                <td>&nbsp;</td>
                                <td><p class="table-left">New user:</p></td>
                                <td><input type="text" name="new_user" id="new_user" onchange="checkuser()"  /><span style="position: absolute; background:#FF0; padding-top	:5px; padding-bottom:5px" id="respont"></span></td>
                              </tr>
                              <tr>
                                <td>&nbsp;</td>
                                <td><p class="table-left">New password:</p></td>
                                <td><input type="password" name="new_password" id="new_password"  /></td>
                              </tr>
                              <tr>
                                <td>&nbsp;</td>
                                <td><p class="table-left">Retype your new password:</p></td>
                                <td><input type="password" name="renew_password" id="renew_password"/></td>
                              </tr>
                            </table>
                            </fieldset>
                    </div>
                </div>
                <div class="hotel-area-bottom">             
                </div>
                 <div class="hotel-message">
                    Enter your username and password that you was recived from your email and chance it.
                </div>
                <div class="hotel-button multi-button">
                    <input type="submit" id="submit" class="button" value="Save">
               
                </div>
                <input type="hidden" name="airport_id" value="<?php echo $session->get('airport_id'); ?>"  />
            <input type="hidden" name="task" value="airlineregister.changeuser" />
            </form>
        <!-- End nearest airport form -->
        </div>
    </div>
</div>

<?php
defined('_JEXEC') or die;
$app = JFactory::getApplication();
JHtml::_('behavior.keepalive');
JHtml::_('behavior.modal');
$jsonUrl  = JURI::base().'index.php?option=com_sfsuser&task=login.check&format=json';
$popupUrl = JURI::base().'index.php?option=com_sfsuser&view=login&layout=popupform&tmpl=component';
//$jsonUrl  = JString::str_ireplace('http:', 'https:', $jsonUrl);
//$popupUrl  = JString::str_ireplace('http:', 'https:', $popupUrl);
?>
<script type="text/javascript">
window.addEvent('domready', function(){
    var sfsLoginForm = document.id('loginForm');
    sfsLoginForm.getElements('[type=text]').each(function(el){
        new OverText(el);
    });
    sfsLoginFormValidate = new Form.Validator(sfsLoginForm);
    $('login-button').addEvent('click', function(e){
        e.stop();
        if( sfsLoginFormValidate.validate() ) {
            username = sfsLoginForm.username.value;
            password = sfsLoginForm.password.value;
            var jsonRequest = new Request.JSON({url: '<?php echo $jsonUrl;?>', onSuccess: function(guest){
                if( guest.status == 0 ) {
                    SqueezeBox.open('<?php echo $popupUrl?>', {handler: 'iframe', size: {x: 525, y: 210}});
                } else {
                    sfsLoginForm.submit();
                }
            }}).get({'username': username, 'password': password});
        } else {
            alert('Please enter your username and password');
        }
    });
    // preventDefault when press ESC
    window.addEventListener("keydown", function(e){
        if(e.keyCode === 27 || (e.keyCode === 13 && sfsLoginForm.password.value == '')) {
            e.preventDefault();
        }
    });
})
</script>

<div class="heading-block descript clearfix">
    <div class="heading-block-wrap">
        <h3>Login Form</h3>
        <p class="descript-txt"></p>
    </div>
</div>

<div class="main sfs-main-wrapper "><div class="sfs-orange-wrapper"><div class="sfs-white-wrapper">

<?php
$username = "";
$sfsLoginStatus = $app->getUserState('sfsLoginStatus');
if($sfsLoginStatus) :
    $data = $app->getUserState('users.login.form.data');
    $username = $data['username'];
?>
	<div class="fs-14" style="color:red;">Login denied! Your account has either been blocked or is not activated yet</div>
<?php
endif;
$app->setUserState('sfsLoginStatus', null);
?>

<div class="login">

	<form action="<?php echo JRoute::_('index.php'); ?>" method="post" name="loginForm" id="loginForm">

		<fieldset>

			<div class="login-fields">
				<label for="username" id="username-lbl">User Name</label>
				<input type="text" size="25" class="required" value="<?php echo $username;?>" id="username" name="username">
			</div>

			<div class="login-fields">
				<label class="" for="password" id="password-lbl">Password</label>
				<input type="password" size="25" class="required" value="" id="password" name="password">
			</div>

            <div style="margin:5px 5px 0 0;" class="clearfix">
				<!-- <a class="button-primary float-right" href="#" id="login-button">
					<span><?php //echo JText::_('JLOGIN'); ?></span>
				</a> -->
				<button type="submit" class="btn orange lg pull-right" href="#" id="login-button">
					<?php echo JText::_('JLOGIN'); ?>
				</button>
			</div>
			
			<input type="hidden" name="option" value="com_sfsuser" />
			<input type="hidden" name="task" value="user.login" />
			<input type="hidden" name="Itemid" value="104" />
			<input type="hidden" name="return" value="<?php echo base64_encode('index.php?option=com_sfs&view=dashboard&Itemid=103'); ?>" />
			<?php echo JHtml::_('form.token'); ?>
		</fieldset>
	</form>
</div>

<div>
	<ul>
		<li>
			<a href="<?php echo JRoute::_('index.php?option=com_users&view=reset'); ?>">
				Forgot your password?
			</a>
		</li>
		<li>
			<a href="<?php echo JRoute::_('index.php?option=com_users&view=remind'); ?>">
				Forgot your username?
			</a>
		</li>
	</ul>
</div>


<div class="width100 float-left fs-14" style="padding:10px 0">
   Not a registered user of SFS yet, please <a href="index.php?option=com_sfs&view=home&Itemid=127">sign up</a>.
</div>


</div></div></div>
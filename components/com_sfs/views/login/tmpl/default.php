<?php
defined('_JEXEC') or die;
JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidation');
?>

<div class="heading-block descript clearfix">
    <div class="heading-block-wrap">
        <h3>Login</h3>
        <p class="descript-txt"></p>
    </div>
</div>

<div id="sfs-wrapper" class="main">

    <div class="sfs-main-wrapper">
        <div class="sfs-orange-wrapper hotel-form">
        
            <div class="sfs-white-wrapper floatbox">

                    <form action="<?php echo JRoute::_('index.php?option=com_sfs&task=user.login'); ?>" method="post" class="form-validate">
                        <fieldset>
                            <div id="sfs_formLogin">
                                <div class="label">
                                    Received Username
                                </div>
                                <div style="width:50px; float:left"><input type="text" name="old_username" value="<?php //echo $old_username;?>" class="required" /></div>
                            </div>
                            <div id="sfs_formLogin">
                                <div class="label">
                                    Received Password
                                </div>
                                <div style="width:50px; float:left"><input type="password" name="old_password" value="<?php //echo $old_password;?>" class="required" /></div>
                            </div>
                            <div id="sfs_formLogin">
                                <div class="label">
                                    New Username
                                </div>
                                <div style="width:50px; float:left"><input type="text" name="new_username" value="" class="validate-username required" /></div>
                            </div>
                            <div id="sfs_formLogin">
                                <div class="label">
                                    New Password
                                </div>
                                <div style="width:50px; float:left"><input type="password" name="new_password" value="" class="validate-password required" /></div>
                            </div>
                            <div id="sfs_formLogin">
                                <div class="label">
                                    Re-type New Password
                                </div>
                                <div style="width:50px; float:left"><input type="password" name="new_password2" value="" class="validate-password required" /></div>
                            </div>
                			
                			<div class="s-button float-right">
                            	<button type="submit" class="validate s-button">Confirm and continue</button>
                            </div>
                            <?php echo JHtml::_('form.token'); ?>
                        </fieldset>
                    </form>
                                        
            </div>
            
        </div>
    </div>

</div>
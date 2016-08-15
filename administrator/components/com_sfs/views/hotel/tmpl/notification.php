<?php
// No direct access.
defined('_JEXEC') or die;

// Load the tooltip behavior.
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
JHtml::_('behavior.modal');
?>
<style type="text/css">
    body{
        border: none;
    }
</style>
<div class="width-100">
    <h2>Your Messages / Notifications</h2>    
    <hr/>
    <div style="border-left: 2px solid #01b2c3; padding: 5px;">
        <p>From: Kenny</p>
        <p>To: Pham Vu</p>
        <p>Re: Test Notification</p>
        <p>On: </p>

        <div>
            <p>@PhamVu</p>
            <p>Dear Vu,</p>

            Thanks.
        </div>
    </div>

    <hr/>
    <div style="padding: 5px;">
        <h2>Send messange</h2>
        <div>
            <h5>Your message:</h5>
            <textarea style="width: 350px; height: 150px;"></textarea>
        </div>
        <div>
            <h5>Address Email</h5>
            <input type="text" value="" name="" style="width:250px; height: 20px">
        </div>
        <div style="margin-top: 20px">
            <input type="submit" id="" value="Send Mail" style="background: #01b2c3; padding: 8px 14px; color: #fff; font-weight:600" />
        </div>
        
    </div>    
</div>



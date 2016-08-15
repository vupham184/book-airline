<?php
defined('_JEXEC') or die;

?>
<div class="heading-block descript clearfix">
	<div class="heading-block-wrap">
		<h3>Raw post xml</h3>
	</div>
</div>
<div id="sfs-wrapper" class="main">
<form action="<?php echo JRoute::_('index.php?option=com_sfs&task=api.loadxml'); ?>" method="post" >

<div class="sfs-main-wrapper" style="padding:0 1px 0 1px ; margin-bottom:15px;">
    <div class="sfs-orange-wrapper">
        <div class="sfs-white-wrapper">
        	<table cellspacing="0" cellpadding="0" border="0">
                <tr valign="top">
                    <td style="vertical-align: top;">
                        <div class="frm-left">
                        <input type="file" name="passenger_push"  />
                        </div>
                    </td>
           		</tr>
                <tr valign="top">
                	<td>
                    <div class="frm-right">
                        <div class="floatbox">
                        <button type="submit" class="btn orange lg"><?php echo JText::_('COM_SFS_GENERATE')?></button>
                        </div>
                    </div>
                    </td>
            	</tr>
            </table>
        </div>
    </div>
</div>

<input type="hidden" name="task" value="api.loadxml" />

<input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid');?>" />

<?php echo JHtml::_('form.token'); ?>

</form>


</div>
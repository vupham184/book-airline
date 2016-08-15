<?php
defined('_JEXEC') or die;
// Load the tooltip behavior.
JHtml::_('behavior.keepalive');
?>
<form action="<?php echo JRoute::_('index.php?option=com_sfs'); ?>" method="post" name="adminForm" id="status-form">

	<fieldset>
		<div class="fltrt">
			<button type="submit">
				Copy
			</button>			
			<button onclick="  window.parent.SqueezeBox.close();" type="button">
				Close
			</button>
		</div>
		<div class="configuration">
			Copy New Airline
		</div>
	</fieldset>

    <div style="padding: 20px;">
        <h2>Copy New Airline</h2>
        <table>
            <tr>
                <td>Iatacode</td>
                <td><?php echo SfsHelper::getIatacodeCopyAirlineField('iatacode_id', 0);?></td>
            </tr>
            <tr>
                <td>Affiliation Code</td>
                <td><input type="text" name="affiliation_code" class="inputbox required"></td>
            </tr>
        </table>
        <input type="hidden" name="tmpl" value="component" />
        <input type="hidden" name="task" value="airline.copyAirline" />
        <input type="hidden" name="id" value="<?php echo $this->item->id?>" />
        <?php echo JHtml::_('form.token'); ?>
    </div>
</form>
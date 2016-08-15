<?php
defined('_JEXEC') or die;
JHtml::_('behavior.tooltip');
$airlines = $this->airline->getServicingAirlines(true,'b.code ASC');
?>

<script type="text/javascript">
window.addEvent('domready', function(){
	$$('button.editcontract-button').each(function(ecbutton) {
		ecbutton.addEvent('click', function(e) {
			e.stop();
			var aid = ecbutton.getProperty('rel');
			var contractText = $('contract'+aid);
			contractText.removeClass('displaynone');
			$('plaintext'+aid).addClass('displaynone');

			ecbutton.addClass('displaynone');
			$('savecontract'+aid).removeClass('displaynone');
			
		});
	});
});
</script>
<div class="heading-block descript clearfix">
	<div class="heading-block-wrap">
		<h3>Contract Details</h3>
	</div>
</div>
<div id="sfs-wrapper" class="fs-14 contractdetails main">

    <div class="">
    <div class="sfs-orange-wrapper">
        
    <div class="sfs-white-wrapper floatbox" style="padding: 0;margin:0;border:1px solid #82ADF1;border-bottom:0px solid #82ADF1">
        <table class="airblocktable" width="100%" cellspacing="0" cellpadding="0" border="0">
        <tr>
            <th width="100">Airline code</th>
            <th width="150">Airline name</th>
            <th>Contract details</th>
        </tr>
        <?php
        foreach ($airlines as $item): ?>
        <tr valign="top">
            <td>
                <?php echo $item->code;?>
            </td>
            <td>
                <?php echo $item->name;?>
            </td>
            <td>
                <form method="post" action="<?php echo JRoute::_('index.php');?>" id="contractAirlineID<?php echo $item->airline_id;?>">
                	<?php
					$text = !empty($item->contract_details) ? $item->contract_details : 'No specific details';
                    ?>
                    <div class="float-left" style="width:80%;">
                		<textarea name="contractdetails" style="width:90%; height:70px;" class="displaynone" id="contract<?php echo $item->airline_id;?>"><?php echo !empty($item->contract_details) ? $item->contract_details : ''?></textarea>
                		<span id="plaintext<?php echo $item->airline_id;?>"><?php echo $text?></span>
                	</div>
                	<div class="float-left" style="width:20%;">
                		<div class="mid-button">
	                		<button type="button" class="editcontract-button" rel="<?php echo $item->airline_id;?>" style="width:70px;text-indent:22px">Edit</button>
	                		<button type="submit" class="displaynone" id="savecontract<?php echo $item->airline_id;?>" style="width:70px;text-indent:22px">Save</button>
	                	</div>
                	</div>
                	<input type="hidden" name="airline_id" value="<?php echo $item->airline_id;?>" />
                	<input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid')?>" />
                	<input type="hidden" name="option" value="com_sfs" />
                	<input type="hidden" name="task" value="contractdetails.save" />
					<?php echo JHtml::_('form.token'); ?>
                </form>
            </td>
        </tr>
        <?php endforeach;?>
        </table>
    </div>
                    
    </div>
    </div>

    <div class="sfs-below-main">
    	<div class="s-button">
	        <a href="<?php echo JRoute::_('index.php?option=com_sfs&view=dashboard&Itemid='.JRequest::getInt('Itemid')) ?>" class="s-button"><?php echo JText::_('COM_SFS_BACK');?></a>
        </div>
    </div>
	<div class="clear"></div>


</div>
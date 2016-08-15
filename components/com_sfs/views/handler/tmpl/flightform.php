<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.keepalive');
JHtml::_('behavior.modal');
JHtml::_('behavior.formvalidation');

$startDateList = SfsHelperDate::getSearchDate('start_prev' , 'class="inputbox" style="width:150px;"');
$endDateList   = SfsHelperDate::getSearchDate('end_prev' , 'class="inputbox" style="width:150px;"');

$sess = JFactory::getSession();
$sData = $sess->get('flightform_data');

$airline = SFactory::getAirline();
$airline_current = SAirline::getInstance()->getCurrentAirport();
$airport_current_id = $airline_current->id;
$airlineName = '';
if($airline->grouptype == 3) {
	$selectedAirline = $airline->getSelectedAirline();
	$airlineName = 	$selectedAirline->name;
}
?>

<script type="text/javascript">
	window.addEvent('domready', function() {
		<?php
		if( isset($airline->params['show_addpassengercomment']) && (int)$airline->params['show_addpassengercomment'] == 1 ) :
		?>
		$('closecomment').addEvent('click',function(){
			$('flightcommentarea').setStyle('display','none');
		});
		$('opencomment').addEvent('click',function(){
			$('flightcommentarea').setStyle('display','block');
		});
		<?php endif;?>

		$('editdates').addEvent('click',function(){			
			$('schar').removeClass('displaynone');
			$('rangeinput').removeClass('displaynone');
			$('rangetext').addClass('displaynone');
			$('editdates').addClass('displaynone');			
			document.getElementById("rangecheck").checked = false;
		});

		$('rangecheck').addEvent('change', function(e){
			e.stop();

			if( $('rangecheck').checked ) {
				$('schar').addClass('displaynone');
				$('rangeinput').addClass('displaynone');
				// reset to default
				$('date_start_prev').selectedIndex = 1;
				$('date_end_prev').selectedIndex = 1;
				$('rangetext').removeClass('displaynone');
				$('editdates').addClass('display');	
				$('editdates').removeClass('displaynone');			
			} else {
				$('schar').removeClass('displaynone');
				$('rangeinput').removeClass('displaynone');
				$('rangetext').addClass('displaynone');
				// $('editdates').removeClass('displaynone');
				$('editdates').addClass('displaynone');
			}
		});

		
		var rangetext = $('date_start_prev').getSelected().get('text')+' ending: '+$('date_end_prev').getSelected().get('text');

		$('rangetext').set('text',rangetext);

	});

	
</script>
<style type="text/css">
	#editdates{
		color: blue;
		cursor: pointer;		
		margin-top: 4px;
		margin-left: -150px;
		float: left;
	}
</style>
	<!-- Helper tooltip -->


<div class="heading-block clearfix">
    <div class="heading-block-wrap">
        <h3><?php if($airlineName) echo $airlineName.': ';?><?php echo JText::_('COM_SFS_AIRLINE_FLIGHT_PAGE_TITLE');?></h3>        
    </div>
</div>

<div class="main">			
	<form action="<?php echo JRoute::_('index.php?option=com_sfs&view=handler')?>" method="post" class="form-validate sfs-form form-horizone">	
		<div class="form-group" data-step="1" data-intro="<?php echo SfsHelper::getTooltipTextEsc('check_passengers_stay_at_night', $classField, 'airline');?>">
			<div style="width: 70%; float: left;">
				<input type="checkbox" id="rangecheck" checked="checked" /> Passengers are for the night<span id="schar" class="displaynone">(s)</span> starting: <span id="rangetext"></span><span class="displaynone" id="rangeinput"><?php echo $startDateList?> ending: <?php echo $endDateList;?> </span>
			</div>
			<div id="editdates">Edit Dates</div>
		</div>
		
		<div class="form-group vertical-align">
			<div class="form-control" data-step="2" data-intro="<?php echo SfsHelper::getTooltipTextEsc('number_of_stranded_passengers', $classField, 'airline');?>">
				<label><?php echo JText::_('COM_SFS_AIRLINE_FLIGHT_NUMBER_OF_SEATS');?>:</label>
				<div class="form-group-label-right">
					<input type="text" name="stranded_seats" value="<?php echo isset($sData) ? $sData->seats : ''?>" class="required validate-numeric smaller-size" style="width:50px" />
					<span class="label-right"><?php echo JString::strtolower(JText::_('COM_SFS_PASSENGERS'));?></span>
				</div>
			</div>
			<div style="float:left" data-step="3" data-intro="<?php echo SfsHelper::getTooltipTextEsc('flight_class', $classField, 'airline');?>">
				<div class="form-control">
					<label><?php echo JText::_('COM_SFS_AIRLINE_FLIGHT_NUMBER');?>:</label>
					<input type="text" name="flight_code" value="<?php echo isset($sData) ? $sData->flight_code : ''?>" size="3" class="required short-size" />
				</div>
				
				<div class="form-control">
					<label><?php echo JText::_('COM_SFS_AIRLINE_FLIGHT_CLASS');?>:</label>
						<?php
							if( isset($sData) && $sData->flight_class )
							{
								$classField = SfsHelperField::getFlightClass($sData->flight_class);
							} else {
								$classField = SfsHelperField::getFlightClass();
							}
						    echo SfsHelper::htmlTooltip('flight_class', $classField, 'airline');
						?>
				</div>
			</div>		
		</div>
		    	
        <div class="form-group vertical-align">        														
        	<div class="form-control" data-step="4" data-intro="<?php echo SfsHelper::getTooltipTextEsc('flight_delay_code', 'help-icon', 'airline');?>">
		        <label style="display: inline-block"><?php echo JText::_('COM_SFS_AIRLINE_IATA_STRANDED_CODE');?>:</label>
                <a href="<?php echo JRoute::_('index.php?option=com_sfs&view=article&id='.$this->params->get('article_delay_code').'&tmpl=component&Itemid='.JRequest::getInt('Itemid'));?>" rel="{handler: 'iframe', size: {x: 675, y: 520}}" class="modal button" style="float:none;padding:3px 3px;text-decoration:none;"><?php echo SfsHelper::htmlTooltip('flight_delay_code', 'help-icon', 'airline');?></a>
		        <div class="form-group-label-right">
					<input type="text" name="iata_stranged_code" value="<?php echo isset($sData) ? $sData->delay_code : ''?>" class="required" style="width:200px;" />
				</div>
			</div>
			<div class="form-control" data-step="5" data-intro="<?php echo SfsHelper::getTooltipTextEsc('flight_add_comment_link', JText::_('COM_SFS_AIRLINE_FLIGHT_NUMBER_ADD_COMMENT'), 'airline');?>">
			<?php
			if( isset($airline->params['show_addpassengercomment']) && (int)$airline->params['show_addpassengercomment'] == 1 ) :
			?>			
			<span id="opencomment" style="text-decoration:underline; margin-top:25px; cursor: pointer; display: block">
				<?php echo JText::_('COM_SFS_AIRLINE_FLIGHT_NUMBER_ADD_COMMENT');?>
			</span>
			<div class="clear flightcommentarea" id="flightcommentarea" style="margin-top:10px;">
				<span class="flightcommentarea-title"><?php echo JText::_('COM_SFS_AIRLINE_FLIGHT_NUMBER_ADD_COMMENT');?></span>:<br />
				
				<p><?php echo JText::_('COM_SFS_ADDCOMMENT_NOTE')?></p>

				<textarea name="comment" style="width:390px;height:90px;"><?php echo isset($sData) ? $sData->comment : ''?></textarea>
				
				<div class="floatbox midmargintop">
					<div class="s-button float-right">
	            		<button type="button" id="closecomment" class="s-button"><?php echo JText::_('Save and Close')?></button>
	            	</div>
				</div>
			</div>
			<?php endif;?>	
			</div>		
		</div>
		<div class="form-group">
		  <button type="submit" class="btn orange sm" data-step="6" data-intro="<?php echo SfsHelper::getTooltipTextEsc('btn_upload', $classField, 'airline');?>"><?php echo JText::_('Upload')?></button>
		</div>

		<input type="hidden" name="task" value="handler.addflight" />
		<input type="hidden" name="airport" value="<?php echo $airport_current_id;?>" />
		<input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid')?>" />
		<?php echo JHtml::_('form.token'); ?>
	</form>		
</div>

<?php $sess->clear('flightform_data'); ?>
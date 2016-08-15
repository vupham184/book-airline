<?php
defined('_JEXEC') or die;
$document = JFactory::getDocument();
$document->addScript(JURI::base().'components/com_sfs/assets/js/report.js');

$post = JRequest::get('post');

if( empty($post['date_from']) ) {
	$post['date_from'] = null;
}

if( empty($post['date_to']) ) {
	$post['date_to'] = null;
}

if( !isset($post['gh_airline'] )  ) {
	$post['gh_airline'] = 0;
}

JHtml::_('behavior.keepalive');

$airline = SFactory::getAirline();
$airlineName = '';
if($airline->grouptype == 3) {
	//$selectedAirline = $airline->getSelectedAirline();
	//$airlineName = 	$selectedAirline->name;
}

JHtml::_('behavior.modal');
?>
<script type="text/javascript">
window.addEvent('domready', function(){
	new Form.Validator(document.id('airlineReportForm'));
});

/*jQuery(function( $ ){
	$('.new-report-airline').click(function(e) {
		$('#newReportAirline_date_from').val( $('#date_from').val() );
		$('#newReportAirline_date_to').val( $('#date_to').val() );
		$('#newReportAirline').submit();
    });;
});*/
</script>

<div class="heading-block descript clearfix">
    <div class="heading-block-wrap">
        <h3><?php if($airlineName) echo $airlineName.': ';?><?php echo JText::_('COM_SFS_REPORT_MAKE_YOUR_REPORT')?></h3>
        <div class="descript-txt"></div>
    </div>
</div>

<div id="sfs-wrapper" class="main report-airline">
	
	<div class="">
		<div class="sfs-orange-wrapper report_air">
			<div class="sfs-white-wrapper floatbox">
                <input type="hidden" value="<?php echo $this->check_userkey->secret_key;?>" name="uk" id="uk_secret_key">
				<form name="airlineReportForm" id="airlineReportForm" action="<?php echo JRoute::_('index.php?option=com_sfs&view=report&layout=airline' . ( $this->check_userkey->secret_key != '' ) ? "&uk=" . $this->check_userkey->secret_key : '' );?>" method="post">
					<div class="frm-left">
					
						<table cellspacing="0" cellpadding="0" border="0">
							<tr valign="top">
								<td style="vertical-align: top;">
									
									<div class="fs-16 midmarginbottom"><?php echo JText::_('COM_SFS_REPORT_MAKE_YOUR_SELECTION');?></div>
																		
									<table cellspacing="0" cellpadding="0" border="0" class="fs-14">
										<tr>
											<td width="90"><?php echo JText::_('COM_SFS_FROM')?></td>
											<td>
												<?php SfsHelperField::getCalendar('date_from',$post['date_from'],'calendar required');?>
											</td>
										</tr>
										<tr><td colspan="2" height="15"></td></tr>
										<tr>
											<td><?php echo JText::_('Until')?></td>
											<td>
												<?php SfsHelperField::getCalendar('date_to',$post['date_to'],'calendar required');?>
											</td>
										</tr>
									</table>
								</td>
								
								<?php
								if($airline->grouptype == 3):
								?>
								<td style="vertical-align: top; padding-left:100px;">
									<h4 style="margin-top: 0">Select Airline</h4>
									<select name="gh_airline" class="inputbox" style="width:150px;">
										<option value="0">All</option>
										<?php
										$ghAirlines = $airline->getServicingAirlines();
										foreach ($ghAirlines as $air) :
										?>
											<option value="<?php echo $air->airline_id;?>"><?php echo $air->name;?></option>
										<?php endforeach;?>
									</select>
								</td>
								<?php endif;?>
								
							</tr>
						</table>
											
					</div>
					
					<div class="frm-right">
						<div class="floatbox">
               <button type="submit" class="btn orange lg"><?php echo JText::_('COM_SFS_GENERATE')?></button>
						</div>
                        
					</div>
					
	        		<input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid');?>" />
	        		<?php echo JHtml::_('form.token'); ?>
				</form>
				
			</div>
		</div>
	</div>
	<div class="clear"></div>

	<?php if ( !empty($post['date_from']) && !empty($post['date_to']) ): ?>
	<script type="text/javascript">
		<!--
		window.addEvent('domready', function(){
			var dateFrom = '<?php echo $post['date_from']?>';
			var dateTo = '<?php echo $post['date_to']?>';
			var ghAirline = '<?php echo $post['gh_airline']?>';
			var uk_secret_key = '<?php echo isset( $_GET['uk'] ) ? '&uk=' . $_GET['uk'] : '';?>';
			airlineReport(dateFrom,dateTo,ghAirline, uk_secret_key);
		});
		//-->
	</script>
	<div id="your-report" class="airline-report">
		
        <div class="sfs-above-main">
        	<h3><?php echo JText::_('COM_SFS_REPORT_YOUR_REPORTS') ?></h3>
        </div>
        
        <div class="">
        <div class="sfs-orange-wrapper">
		
			<div id="tophotels" class="clear floatbox">
            	
                <div class="report-column">
					<div id="total_booking_overview"></div>
					
				</div>
                
				<div class="report-column">
					<div id="roomnights"></div>
					
				</div>
				
				<div class="report-column">
				<div style="background-color:aliceblue;height:20px;"><br/></div>
					<div id="average"></div>
				</div>
				<div class="report-column last">
				<div style="background-color:aliceblue;height:20px;"><br/></div>
					<div id="revenue"></div>
				</div>
			</div>
			
			<div class="clear floatbox" style="margin-top:30px;">
				<div class="report-column">
				<div style="background-color:aliceblue;height:20px;"><br/></div>
					<div class="column-first">
						<div id="iatacode"></div>
					</div>
				</div>
				<div class="report-column">
				<div style="background-color:aliceblue;height:20px;"><br/></div>
					<div class="column-center">
						<div id="marketpicked"></div>
					</div>
				</div>
				<div class="report-column">
				<div style="background-color:aliceblue;height:20px;"><br/></div>
					<div class="column-center">
						<div id="transportation"></div>
					</div>
				</div>
				<div class="report-column" style="margin:0;">
				<div style="background-color:aliceblue;height:20px;"><br/></div>
					<div class="column-last">
						<div id="initial-blocked"></div>
					</div>
				</div>
			</div>
		</div>
       
        </div>
	</div>
	<?php endif;?>
	
</div>

<div class="main-bottom-block">
	<div class="s-button float-left">
		<a href="<?php echo JRoute::_( SfsHelperRoute::getSFSRoute('dashboard') );?>" class="s-button">
			<?php echo JText::_('COM_SFS_BACK');?>
		</a>
	</div>
</div>
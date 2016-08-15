<?php
defined('_JEXEC') or die;
$document = JFactory::getDocument();
$document->addScript(JURI::base().'components/com_sfs/assets/js/report.js');

$month_array = array(
	1 => JText::_('JANUARY'),
	2 => JText::_('FEBRUARY'),
	3 => JText::_('MARCH'),
	4 => JText::_('APRIL'),
	5 => JText::_('MAY'),
	6 => JText::_('JUNE'),
	7 => JText::_('JULY'),
	8 => JText::_('AUGUST'),
	9 => JText::_('SEPTEMBER'),
	10 => JText::_('OCTOBER'),
	11 => JText::_('NOVEMBER'),
	12 => JText::_('DECEMBER')
);

$m_from = JRequest::getInt('m_from');
$y_from = JRequest::getInt('y_from');
$m_to = JRequest::getInt('m_to');
$y_to = JRequest::getInt('y_to');

?>

<div class="heading-block clearfix">
    <div class="heading-block-wrap">
        <h3><?php echo JText::_('COM_SFS_REPORT_MAKE_YOUR_REPORT')?></h3>        
    </div>
</div>

<div class="main">
	<h3><?php echo JText::_('COM_SFS_REPORT_MAKE_YOUR_SELECTION')?></h3>
	<div id="hotel-report">    				
		<div class="form-group" style="overflow:hidden">
			<form name="hotelReportForm" action="<?php echo JRoute::_('index.php?option=com_sfs&view=report');?>" method="post" class="sfs-form form-vertical report-form">				
				<div data-step="1" data-intro="<?php echo SfsHelper::getTooltipTextEsc('select_date_form', $text, 'hotel'); ?>" class="col w60">
					<div class="col w50">
						<div class="form-group">
							<label><?php echo JText::_('COM_SFS_FROM')?></label>
							<div class="select-group">
								<?php echo SfsHelperField::getMonthField('m_from',$m_from)?>
								<?php echo SfsHelperField::getYearField('y_from',$y_from)?>							
							</div>
						</div>
					</div>
					<div class="col w50">
						<div class="form-group">
							<label><?php echo JText::_('Until')?></label>
							<div class="select-group">
								<?php echo SfsHelperField::getMonthField('m_to',$m_to)?>
								<?php echo SfsHelperField::getYearField('y_to',$y_to)?>
							</div>
						</div>
					</div>
				</div>
				
				<div class="col w20">					
					<button type="submit" class="btn orange lg" data-step="2" data-intro="<?php echo SfsHelper::getTooltipTextEsc('button_generate', $text, 'hotel'); ?>"><?php echo JText::_('COM_SFS_GENERATE')?></button>					
				</div>
					
				<input type="hidden" name="task" value="report.generatehr" />
	    		<input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid');?>" />
	    		<?php echo JHtml::_('form.token'); ?>
			</form>
		</div>
		<?php if ( $m_from && $y_from && $m_to && $y_to && $this->data_count ): ?>
			<script type="text/javascript">
				<!--
				window.addEvent('domready', function(){
					var m_from = '<?php echo $m_from;?>';
					var y_from = '<?php echo $y_from;?>';
					var m_to = '<?php echo $m_to;?>';
					var y_to = '<?php echo $y_to;?>';
					hotelReport(m_from,y_from,m_to,y_to);
				});
				//-->
			</script>
		
			<div id="your-report" style="margin-top:25px;">
                <div class="sfs-above-main">
					<h3><?php echo JText::_('COM_SFS_REPORT_YOUR_REPORTS')?></h3>
                </div>
                <div class="">
                <div class="sfs-orange-wrapper">
                
					<p class="fs-14">Report Period: <?php echo $month_array[$m_from].' '.$y_from.' till '.$month_array[$m_to].' '.$y_to;?></p>
										
					<div class="clear floatbox">
						<div class="report-column">
							<div id="roomnights"></div>
						</div>
						<div class="report-column">
							<div id="average"></div>
						</div>
						<div class="report-column last">
							<div id="revenue"></div>
						</div>
					</div>
				</div>
				</div>
			</div>
		<?php endif;?>
	</div>
	<div class="main-bottom-block">		
		<a href="<?php echo JRoute::_( SfsHelperRoute::getSFSRoute('dashboard') );?>" class="btn orange sm">
			<?php echo JText::_('COM_SFS_BACK');?>
		</a>		
	</div>
</div>

<?php
defined('_JEXEC') or die;

$document = JFactory::getDocument();
$document->addStylesheet( JURI::base().'components/com_sfs/assets/css/print.css', 'text/css' , 'print' );

$type = JRequest::getInt('type');
$points = JRequest::getVar('points');
$dates = JRequest::getVar('dates');
?>
<div id="sfs-print-wrapper">

	<div style="position:relative; padding-top:35px;overflow: auto;">
		<div style="position: fixed; left:614px; top:0;">
			<div class="heading-buttons">
				<button onclick="window.print();return false;" type="button" class="sfs-button float-left">
					Print
				</button>
				<button onclick="window.parent.SqueezeBox.close();" type="button" class="sfs-button float-left">
					Close
				</button>
			</div>
		</div>	
		
		<center>
			<img src="<?php echo JURI::base().'index.php?option=com_sfs&task=report.airlinechart&type='.$type.'&format=raw&points='.$points.'&dates='.$dates;?>" />
		</center>
	</div>

</div>
<?php
defined('_JEXEC') or die('Restricted access');
?>
<div class="heading-block descript clearfix">
	<div class="heading-block-wrap">
		<h3><?php echo JText::_("com_sfs_WELCOME");?></h3>
	</div>
</div>
<div class="airline-welcome1 floatbox12">
    <div class="airline-welcome-inner12">
		<?php
    	$document	= JFactory::getDocument();
		$renderer	= $document->loadRenderer('module');
		$modules	= JModuleHelper::getModules('latestnews');
		$params		= array('style' => 'raw');
		foreach ($modules as $module) {
			echo '<h3>'.$module->title.'</h3>';
			echo $renderer->render($module, $params);
		}
		?>
    </div>
</div>

<div id="sfs-wrapper" class="fs-14 main">
	
	<div class="sfs-main-wrapper">
	<div class="sfs-orange-wrapper">
	<div class="sfs-white-wrapper">

		<div>
			Stranded Flight Solutions, brings a number of services on and around airports together on the SFS platform. If you are a service provider or an airline or ground handler, you are invited to sign up or <a href="http://strandedflightsolutions.com/index.php?option=com_content&view=article&id=73&Itemid=158" target="_blank">contact the SFS sales team</a> for additional information.
			<p>Please select the sign up button that matches your business</p>
		</div>
				
		<div class="sfs-row" style="margin-bottom: 10px;margin-top:20px;width:500px;">
			<div class="float-left" style="line-height: 180%;">
				Airlines, please sign up here:
			</div>
			<div class="float-right">
				<a href="<?php echo JRoute::_('index.php?option=com_sfs&view=airlineregister&Itemid='.JRequest::getInt('Itemid'));?>" class="btn orange lg"><span>Airline sign up</span></a>
			</div>
		</div>
		
		<div class="sfs-row" style="margin-bottom: 10px;margin-top:20px;width:500px;">
			<div class="float-left" style="line-height: 180%;">
				Ground handlers, please sign up here:
			</div>
			<div class="float-right">
				<a href="<?php echo JRoute::_('index.php?option=com_sfs&view=ghregister&Itemid='.JRequest::getInt('Itemid'))?>" class="btn orange lg"><span>Ground handlers sign up</span></a>
			</div>
		</div>
		
		<div class="sfs-row" style="margin-bottom: 10px;margin-top:20px;width:500px;">
			<div class="float-left" style="line-height: 180%;">
				Hotels, please sign up here:
			</div>
			<div class="float-right">
				<a href="<?php echo JRoute::_('index.php?option=com_sfs&view=hotelregister&Itemid='.JRequest::getInt('Itemid'))?>" class="btn orange lg"><span>Hotel sign up</span></a>
			</div>
		</div>
		
		<div class="sfs-row" style="margin-bottom: 10px;margin-top:20px;width:500px;">
			<div class="float-left" style="line-height: 180%;">
				Bus transportation suppliers, please sign up here:
			</div>
			<div class="float-right">
				<a href="<?php echo JRoute::_('index.php?option=com_sfs&view=busregister&Itemid='.JRequest::getInt('Itemid'))?>" class="btn orange lg"><span>Bus sign up</span></a>
			</div>
		</div>
								
		<div class="sfs-row" style="margin-bottom: 10px;margin-top:20px;width:500px;">
			<div class="float-left" style="line-height: 180%;">
				Taxi companies, please sign up here:
			</div>
			<div class="float-right">
				<a href="<?php echo JRoute::_('index.php?option=com_sfs&view=taxiregister&Itemid='.JRequest::getInt('Itemid'))?>" class="btn orange lg"><span>Taxi sign up</span></a>
			</div>
		</div>
		
	</div>
	</div>
	</div>


</div>

<?php
defined('_JEXEC') or die;
?>
<div id="sfs-wrapper" class="fs-14">

    <div class="heading-block clearfix">
        <div class="heading-block-wrap">
            <h3><?php echo JText::_('COM_SFS_SELECT_AIRLINE');?></h3>
        </div>
    </div>
	<div class="main">
		<div class="sfs-orange-wrapper">
			<div class="sfs-white-wrapper">
				<form action="<?php echo JRoute::_('index.php?option=com_sfs')?>" method="post">
					<?php
					$session = JFactory::getSession();
					$profiles = $session->get('airline_profile');
					
					if( ! empty($profiles) ) : ?>
						<?php echo JText::_('COM_SFS_SELECT_YOUR_AIRLINE');?>:
						<select name="airline_id">
							<?php foreach ( $profiles as $k => $v) :?>
							<option value="<?php echo $k;?>"><?php echo $v;?></option>
							<?php endforeach;;?>
						</select>
					<?php endif;?>
					
					<button type="submit" class="btn orange lg"><?php echo JText::_('COM_SFS_GO');?></button>
								
					<input type="hidden" name="task" value="airlineprofile.changeairline"  />
					<?php echo JHtml::_('form.token');?>
				</form>
			</div>
		</div>
	</div>
	
</div>


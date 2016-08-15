<?php
defined('_JEXEC') or die;
?>
<div class="heading-block descript clearfix">
	<div class="heading-block-wrap">
		<h3>Select Hotel</h3>
	</div>
</div>
<div id="sfs-wrapper" class="main">
	<div class="sfs-main-wrapper">
		<div class="sfs-orange-wrapper">
			<div class="sfs-white-wrapper">
				<form action="<?php echo JRoute::_('index.php?option=com_sfs')?>" method="post">
					<?php
					$session = JFactory::getSession();
					$profiles = $session->get('hotel_profile');
					
					if( ! empty($profiles) ) : ?>
						Select your hotel:
						<select name="hotel_id">
							<?php foreach ( $profiles as $k => $v) :?>
							<option value="<?php echo $k;?>"><?php echo $v;?></option>
							<?php endforeach;;?>
						</select>
					<?php endif;?>
					
					<button type="submit" class="btn orange lg" >Go</button>
					
					<?php echo JHtml::_('form.token');?>
					<input type="hidden" name="task" value="selecthotel" />
				</form>
			</div>
		</div>
	</div>
	
</div>


<?php
/**
 * @version		$Id: default.php 20196 2011-01-09 02:40:25Z ian $
 * @package		Joomla.Site
 * @subpackage	com_users
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @since		1.5
 */

defined('_JEXEC') or die;

if ($this->user->get('guest')):
	// The user is not logged in.
	echo $this->loadTemplate('login');
	
	//lchung
	?>
	<?php 
	require_once(str_replace("com_sfsuser","com_sfs/libraries", JPATH_COMPONENT.DS.'Browser.php' ) );
	$ObjBrowser = new Browser();
	$browser = $ObjBrowser->getBrowser();
	if ( $browser !='Firefox' && $browser !='Chrome' && $browser !='Safari' ) :?>
	
	
	<a  id="a-browsernotsupported" href="#browsernotsupported" rel="boxed" style="display:none;">Show content browser not supported</a> 
	
	<div id="div-browsernotsupported" style="display:none;">
		<?php 
		echo $this->loadTemplate('browsernotsupported');
		?>
	</div>
	
	<script type="text/javascript">
	
		SqueezeBox.assign($$('a[rel=boxed][href^=#]'), {
			size: {x: 700, y: 400}
		});
	
		document.getElementById('a-browsernotsupported').click();
	</script>
	
	<style>
	.sfs-main-wrapper{
		border:0px solid #99ccff;
		border-radius:4px;
	}
	</style>
	<?php endif;?>
<?php 
	//End lchung
else:
    $app = JFactory::getApplication();
    $app->redirect(JRoute::_(JURI::root().'index.php'));
endif;

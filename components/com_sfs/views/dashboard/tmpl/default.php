<?php
defined('_JEXEC') or die;

JHTML::_('behavior.modal');
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');

$user = JFactory::getUser();
$istype = (!$user->get('guest')) ? 'logout' : 'login';
require_once(JPATH_COMPONENT.DS.'libraries/Browser.php' );
$ObjBrowser = new Browser();
$browser = $ObjBrowser->getBrowser();

if ( $istype != 'logout' && $browser !='Firefox' && $browser !='Chrome' && $browser !='Safari' ) :
	echo $this->loadTemplate('browsernotsupported');
else:

	if( SFSAccess::isHotel( $this->user ) ) {
		echo $this->loadTemplate('hotel');
	} else if ( SFSAccess::isAirline( $this->user ) ) {
		echo $this->loadTemplate('airline');
	} else if( SFSAccess::isBus()) {
		echo $this->loadTemplate('bus');
	} else if( SFSAccess::isTaxi()) {
		echo $this->loadTemplate('taxi');
	}
	
	//lchung
	?>
	<?php 
	
	if ( $browser !='Firefox' && $browser !='Chrome' && $browser !='Safari' ) :;?>

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
	
endif;
<?php
/**
 * @package		AdminPraise3
 * @author		AdminPraise http://www.adminpraise.com
 * @copyright	Copyright (c) 2008 - 2011 Pixel Praise LLC. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */
 
 /**
 *    This file is part of AdminPraise.
 *    
 *    AdminPraise is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU General Public License for more details.
 *
 *    You should have received a copy of the GNU General Public License
 *    along with AdminPraise.  If not, see <http://www.gnu.org/licenses/>.
 *
 **/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
require_once(dirname(__FILE__).'/lib/stainless.php');
$stainless = AdminPraise3Tools::getInstance();

$templateTheme    = $stainless->get('templateTheme');
$mainframe = &JFactory::getApplication();

// redirect to fallback template for fallback components
$fallbackComponents = $stainless->get('fallbackComponents');
$fallbackTemplate = $stainless->get('fallbackTemplate');
if((in_array($stainless->get('option'), $fallbackComponents)) && $fallbackTemplate){ 
$this->template = $fallbackTemplate;
$params_ini = file_get_contents(JPATH_ROOT.DS.'administrator'.DS.'templates'.DS.$fallbackTemplate.DS.'params.ini');
$active_params = new JParameter($params_ini);

foreach($active_params->_registry['_default']['data'] as $name=>$value) :
$this->params->set($name, $value);
endforeach;

if($fallbackTemplate == "stainless"){
	print '<style type="text/css">div.icon a{height:90px !important;}</style>';
	$this->params->set('switchSidebar',$active_params->get('switchSidebar'));
	$this->params->set('showSidebar',$active_params->get('showSidebar'));
}
require_once('templates'.DS.$fallbackTemplate.DS.'index.php');
return;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>" >
<head>
<jdoc:include type="head" />

<link href="templates/<?php echo $this->template ?>/css/layout.css" rel="stylesheet" type="text/css" />
<link href="templates/<?php echo  $this->template ?>/css/template.css" rel="stylesheet" type="text/css" />

<meta name="viewport" content="width=device-width, minimum-scale=0.2, maximum-scale=1.0" />
<link rel="apple-touch-icon" href="templates/<?php echo $this->template ?>/images/apple-touch-icon.png" />

<script type="text/javascript" src="templates/<?php echo $this->template ?>/js/stainless.js"></script>
<script type="text/javascript">
	window.addEvent('domready', function () {
		document.getElementById('form-login').username.select();
		document.getElementById('form-login').username.focus();
	});
</script>

<?php echo $stainless->generateLoginStyles(); ?>

</head>
<body id="login" class="<?php echo $stainless->get('templateColor'). " " .$stainless->get('templateTheme');?>">
<div id="ap-login">
	<div id="content-box">
		<div class="padding">
			<div id="element-box" class="login">
				<div>
					<jdoc:include type="component" />
					<div class="clr"></div>
				</div>
				<div id="ap-login-logo">
					<span id="ap-login-icon"></span>
				</div>
			</div>
			<noscript>
				<?php echo JText::_('WARNJAVASCRIPT') ?>
			</noscript>
			<div class="clr"></div>
		</div>
	</div>
</div>
	<div id="ap-footer" class="ap-padding">
		
		<div class="clear">&nbsp;</div>
	</div>
<div id="hiddenDiv"><jdoc:include type="message" />
</div>
</body>
</html>

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
defined('_JEXEC') or die('Restricted access');
$template_path = dirname(__FILE__);
require_once($template_path . '/lib/stainless.php');

$mainframe = &JFactory::getApplication();
$stainless = &AdminPraise3Tools::getInstance();

// redirect to fallback template for fallback components
$fallbackComponents = $stainless->get('fallbackComponents');
$fallbackTemplate = $stainless->get('fallbackTemplate');
if ((in_array($stainless->get('option'), $fallbackComponents)) && $fallbackTemplate) {
	$this->template = $fallbackTemplate;
	$params_ini = file_get_contents(JPATH_ROOT . DS . 'administrator' . DS . 'templates' . DS . $fallbackTemplate . DS . 'params.ini');
	$active_params = new JParameter($params_ini);

	foreach ($active_params->_registry['_default']['data'] as $name => $value) :
		$this->params->set($name, $value);
	endforeach;

	if ($fallbackTemplate == "stainless") {
		print '<style type="text/css">div.icon a{height:90px !important;}</style>';
		$this->params->set('switchSidebar', $active_params->get('switchSidebar'));
		$this->params->set('showSidebar', $active_params->get('showSidebar'));
	}
	require_once('templates' . DS . $fallbackTemplate . DS . 'index.php');
	return;
}

$stainless->checkLogin();

$logoutLink = JRoute::_('index.php?option=com_login&task=logout&' . JUtility::getToken() . '=1');
$isIpad = (bool) strpos($_SERVER['HTTP_USER_AGENT'], 'iPad');

$minWidth = '';
$apShort = '';
$apMainMarginLeft = '';
$templateColor = $stainless->get('templateColor');
$templateTheme = $stainless->get('templateTheme');
$option = $stainless->get('option');
$apTask = " ap-task-" . $stainless->get('task');
$ap_task = $stainless->get('ap_task');
$apView = " ap-view-" . $stainless->get('view');
$apSection = " ap-section-" . $stainless->get('section');
$apLayout = " ap-layout-" . $stainless->get('layout');

$apType = " ap-type-" . $stainless->get('type') . "" . $stainless->get('scope');
if ($stainless->get('showSidebar')) {
	$minWidth = " minwidth";
}
if ($stainless->get('shortHeader')) {
	$apShort = " ap-short";
}
$browser = $stainless->get('browser');

if ($stainless->get('showSideComponentList')) {
	$apMainMarginLeft = 'ap-main-marginleft';
}
if ($stainless->get('browser') == "ie6" || $stainless->get('browser') == "ie7" || $stainless->get('browser') == "ie8") {
	$altToolbar = 1;
} else {
	$altToolbar = $stainless->get('altToolbar');
}

$apTallClass = '';
if ($this->countModules('status') != 0) {
	$apTallClass = 'class="ap-tall"';
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>" id="minwidth" >
	<head>
		<jdoc:include type="head" />

		<?php JHTML::_('behavior.modal'); ?>

		<?php echo $stainless->generateStyles(); ?>

		<link href="templates/<?php echo $this->template ?>/css/layout.css" rel="stylesheet" type="text/css" />
		<link href="templates/<?php echo $this->template ?>/css/template.css" rel="stylesheet" type="text/css" />
		<?php 
		$componentCSS = "templates/" . $this->template . "/css/components/" . $option . ".css";
		if (file_exists($componentCSS)) : ?>
			<link href="<?php echo $componentCSS;?>" rel="stylesheet" type="text/css" />
		<?php endif; ?>

		<?php if (stristr($stainless->get('browser'), 'ie') === FALSE) { ?>
			<style type="text/css">
				@media all and (min-width: 1230px) {
					#cpanel-inner .module{width:23.3%;}
				}
				@media all and (max-width: 1230px) and (min-width: 949px) {
					#ap-submenu li a{font-size:12px;}
				}
				@media all and (max-width: 949px) and (min-width: 0px) {
					#ap-menu{width:40px;}
					#ap-menu a span.component-label,
					#ap-menu a span.parent-name,
					#ap-menu a span.no-parent{display:none;}
					#ap-main.ap-main-marginleft{margin-left:40px;}
					#ap-mainmenu li a{padding:6px 12px 0 12px;}
					#cpanel-inner .module{width:47.5%;}
					#ap-menu .ap-avatar img{width:30px;}
					#ap-submenu li a{font-size:11px;}
				}
				@media all and (max-width: 820px) and (min-width: 0px) {
					#cpanel-inner .module.half,#cpanel-inner .module{width:95%;}
					#ap-mainmenu li a{padding:6px 6px 0 6px;}
					#ap-submenu{padding:5px 5px 0 5px;}
					#ap-content .component-list li.parent{width:80px;margin:16px 0 0 18px;}
					#ap-content .component-list .component-image{width:70px;height:70px;}
					#ap-content .component-list{background-image:none;}
					.com_content.ap-task-add form[name="adminForm"] td[valign="top"],
					.com_content.ap-task-edit form[name="adminForm"] td[valign="top"],
					.com_flexicontent.ap-task- form[name="adminForm"] td[valign="top"],
					.com_flexicontent.ap-view-item form[name="adminForm"] td[valign="top"],
					.adminFormK2Container td:first-of-type,.adminFormK2Container td#adminFormK2Sidebar{
						display:list-item;
						list-style:none;
						width:99%;
					}
					#cpanel.k2AdminCpanel,#k2AdminStats{width:99%;float:none;}
					#ap-menu .parent-name,
					#ap-title{display:none;}
				}
				@media all and (max-width: 320px) and (min-width: 0px) {
					#ap-mainmenu li,#ap-mainmenu li a{float:none;}
					#ap-userstats,#ap-mainmenu{margin-left:2px;}
					#cpanel-inner .module{margin-left:7px;}
					#ap-mainmenu{overflow:visible;height:auto;}
					#toolbar{position:relative;top:0;left:60px;}
					#ap-mainmenu li.home-item span.parent-name{text-indent:0;width:auto;background:none;}
					#ap-userstats{padding-right:2px;}
					#ap-content .component-list li.parent{width:60px;margin:16px 0 0 18px;}
					#ap-content .component-list .component-image{width:50px;height:50px;}
					#ap-content .component-list{background-image:none;}
					#ap-mainmenu li.home-item span.parent-name,
					#ap-mainmenu li.home-item.active span.parent-name {background:none !important;}
					#ap-mainmenu, .com_cpanel.list_components #ap-mainmenu{display:none;}
					.com_cpanel #ap-mainmenu{display:block;}
					#quickAddContentForm textarea{height:80px;width:80%;}
					.adminlist th,
					.adminlist tr td,
					form[name="adminForm"] select{display:none;}
					.adminlist tr td:nth-of-type(3),
					.adminlist tr td:nth-of-type(4),
					.adminlist tr td:nth-of-type(5){display: block;}
					.adminlist td,.adminform td{display:list-item;float:left;list-style:none;max-width:110px;}
					.adminform td{clear:both;}
					.adminlist td.order{float:right;}
					a[onclick *="accessregistered"],
					a[onclick *="accessspecial"],
					a[onclick *="accesspublic"],
					#ap-myeditor,
					#ap-quicklink,
					#ap-menu,
					#alt-toolbar #toolbar td span {display:none;}
					.adminlist tr td{border-bottom:0;}
					.com_cpanel .adminlist td,.com_cpanel form[name="adminForm"] select{display:block !important;max-width:320px !important;float:none !important;}
					#ap-mainmenu li.admin-item,
					#ap-mainmenu li.tools-item{display:none;}
					#ap-userstats{padding:2px;margin:0 0 4px 0;}
					#ap-userstats a{font-size:9px;}
					#ap-quicklink .parent, #ap-myeditor .parent{margin:0 0 0 1px;}
					span#quickadd_section,span#quickadd_category,span.quickadd_state,span.quickadd_frontpage{display:block;margin:0;clear:both;}
					.quickAddTable .key,.quickAddTable input,.quickAddTable label{float:left;}
					#ap-mainmenu{background: none !important;margin-bottom: 10px;}
					#ap-mainmenu li:first-of-type{
						-moz-border-radius:5px 5px 0 0;
						-webkit-border-top-left-radius:5px;
						-webkit-border-top-right-radius:5px;
						border-radius:5px 5px 0 0;
					}
					#ap-mainmenu li:last-of-type{
						-moz-border-radius:0 0 5px 5px;
						-webkit-border-bottom-left-radius:5px;
						-webkit-border-bottom-right-radius:5px;
						border-radius:0 0 5px 5px;
					}
					#ap-main.ap-main-marginleft{margin-left: 0;}
					#ap-mainmenu li ul.component-list, #ap-mainmenu li ul.submenu {left: 0;width:90%;}
					#ap-mainmenu li ul li.submenu-arrow {left: 10px;}
					#text_toolbar,#text,#title.inputbox{min-width: 260px;}
					#quickAddContentForm #text{height: 90px !important;}
					.module-content #title.inputbox{min-width: 60px;}
					#alt-toolbar #toolbar td{width: 40px !important;padding:0;}
					#toolbar,#alt-toolbar #toolbar table.toolbar{max-width: 280px;padding:0;margin:0;}
					#ap-sitename{font-size:11px !important;}
					#ap-logo img{height: 16px;margin:2px 0 0 5px;}
					.quickadd_itemtype th{width: 100%;}
				}

			</style>
		<?php } ?>

		<!--[if IE 9]>
				<link href="templates/<?php echo $this->template ?>/css/ie9.css" rel="stylesheet" type="text/css" />
		<![endif]-->
		<!--[if IE 8]>
				<link href="templates/<?php echo $this->template ?>/css/ie8.css" rel="stylesheet" type="text/css" />
		<![endif]-->
		<!--[if lt IE 8]>
				<link href="templates/<?php echo $this->template ?>/css/ie7.css" rel="stylesheet" type="text/css" />
		<![endif]-->

		<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0" />

		<link rel="apple-touch-icon" media="screen and (resolution: 163dpi)" href="templates/<?php echo $this->template ?>/images/<?php echo $stainless->get('templateTheme') ?>/iOS-57.png" />
		<link rel="apple-touch-icon" media="screen and (resolution: 132dpi)" href="templates/<?php echo $this->template ?>/images/<?php echo $stainless->get('templateTheme') ?>/iOS-72.png" />
		<link rel="apple-touch-icon" media="screen and (resolution: 326dpi)" href="templates/<?php echo $this->template ?>/images/<?php echo $stainless->get('templateTheme') ?>/iOS-114.png" />

		<?php if ((stristr($stainless->get('browser'), 'ie') === FALSE) && ($stainless->get('option') == 'com_cpanel')) : ?>
			<script type="text/javascript" src="templates/<?php echo $this->template ?>/js/flexcroll.js"></script>
			<link href="templates/<?php echo $this->template ?>/js/flexcrollstyles.css" rel="stylesheet" type="text/css" />
		<?php endif; ?>
		
			<script type="text/javascript">
				window.addEvent('domready', function() {
					adminPraiseLiveSite = "<?php echo JURI::base(); ?>";
					adminPraiseCheckFrame();
				});
			</script>
			<script type="text/javascript" src="templates/<?php echo $this->template ?>/js/ap-index.js"></script>
			<script type="text/javascript">
				<!--
				function toggle_overflow(id) {
					var e = document.getElementById(id);
					if(e.style.overflow == 'visible')
						e.style.overflow = 'hidden';
					else
						e.style.overflow = 'visible';
				}
				//-->
			</script>

		<?php if ($stainless->get('option') == 'com_cpanel') : ?>	
			<script type="text/javascript" src="templates/<?php echo $this->template ?>/js/draganddrop.js"></script>
		<?php endif; ?>
		
	</head>
	<body id="minwidth-body" class="ap3 <?php echo "$templateColor $templateTheme 
								$option $apTask $ap_task $apView $apSection $apType 
								$minWidth $apShort $apLayout $browser"; ?>">
		<div id="ap-container">
			<?php if ($stainless->get('showStatusBar')) : ?>
				<div id="ap-userstats">
					<?php if (file_exists($stainless->get('logoFile'))) : ?>
						<a href="<?php echo JURI::root(); ?>administrator" id="ap-logo">
							<img src="<?php echo $stainless->get('logoFile'); ?>" />
						</a>
					<?php endif; ?>

					<ul class="ap-userstats-menu">
						<?php if (!$stainless->get('showSideComponentList') && JFactory::getUser()->authorise('core.manage', 'com_users')) : ?>
							<li><?php echo $stainless->get('profileLink'); ?></li>
						<?php endif; ?>

						<li class="last logout-link">
							<a href="<?php echo $logoutLink; ?>">
								<?php echo JText::_('LOGOUT'); ?> <?php echo $user->username; ?>
							</a>
						</li>
					</ul>
					<jdoc:include type="modules" name="adminpraise_search" />
					<a href="<?php echo JURI::root(); ?>administrator" id="ap-sitename">
						<?php echo $mainframe->getCfg('sitename'); ?> 
					</a>
					<?php if ($stainless->get('option') != "com_cpanel") : ?>
						<div id="ap-title">

							<?php
							// Get the component title div
							$title = $mainframe->get('JComponentTitle');
							// Create component title
							if ($stainless->get('ap_task') == "list_components") {
								$title = JText::_('COMPONENTS');
							} else if ($stainless->get('ap_task') == "admin") {
								$title = JText::_('ADMINISTRATION');
							}
							// Take out any special characters and clean up page name
							$title = strip_tags($title);
							$title = str_replace("!", "", $title);
							$title = str_replace("|", ":", $title);
							$title = trim($title);
							// Add h2
							$title = "<h2>".$title."</h2>";
							// Echo title if it exists
							if ($title) {
								echo $title;
							}
							?>

							<div class="clear"></div>
						</div>
					<?php endif; ?>
					<div class="clr"></div>
				</div>
				<div class="clr"></div>
			<?php endif; ?>
			<?php $stainless->checkAdminMenuHealth(); ?>
			<div class="clear"></div>

			<div id="ap-mainmenu">
				<jdoc:include type="modules" name="adminpraise_menu" />
				<jdoc:include type="modules" name="adminpraise_tools" />
				<div class="clear"></div>
			</div>
			<?php if (($stainless->get('showSubmenu')) && ($stainless->get('option') != "com_cpanel") && ($stainless->get('option') != "com_easyblog")) : ?>
				<div id="ap-submenu">
					<?php if (!JRequest::getInt('hidemainmenu')) : ?>		
						<jdoc:include type="modules" name="submenu" id="submenu-box" />
					<?php endif; ?>

					<?php echo $stainless->renderTemplateModule('mod_submenu'); ?>

					<div class="clear"></div>
				</div>
			<?php endif; ?>

			<div id="ap-middle">

				<?php if ($stainless->get('showSideComponentList')) : ?>
					<div id="ap-menu">
						<ul class="ap-avatar">
							<li>
								<?php if (JFactory::getUser()->authorise('core.manage', 'com_users')) : ?>
									<?php echo $stainless->get('profileAvatar'); ?>
								<?php endif ?>
								<a href="<?php echo $logoutLink; ?>">
									<?php echo JText::_('LOGOUT'); ?>
								</a>
							</li>
						</ul>
						<?php echo $stainless->renderTemplateModule('mod_sessionbar'); ?>
						<div class="panel">
							<jdoc:include type="modules" name="adminpraise_panel" />
						</div>

						<div class="clr"></div>
					</div>
				<?php endif; ?>
				<div id="ap-main" class="ap-static-wrapper <?php echo $apMainMarginLeft; ?>">
					<div class="ap-main-inner">
						<div id="ap-static">
							<div id="toolbar-box"></div>
							<jdoc:include type="message" />
							<div class="clear"></div>
						</div>
						<div id="ap-mainbody" <?php echo $apTallClass; ?>>
							<?php if ($stainless->get('option') != "com_cpanel") : ?>
								<div id="alt-toolbar">
									<jdoc:include type="modules" name="toolbar" />
								</div>
							<?php endif; ?>
							
							<?php if (($stainless->get('option') == "com_easyblog")) : ?>
								<div id="submenu-box">
									<?php if (!JRequest::getInt('hidemainmenu')) : ?>		
										<jdoc:include type="modules" name="submenu" id="submenu-box" />
									<?php endif; ?>
								
									<div class="clear"></div>
								</div>
							<?php endif; ?>
							

							<div id="ap-content">	
								<div id="ap-content-inner">	

									<jdoc:include type="modules" name="aptop" />
									<?php if ($stainless->get('option') == "com_cpanel" && !$stainless->get('ap_task_set')) {
										$stainless->setUserStateOnDashboard(); ?>
										<div id="cpanel-inner">
											<jdoc:include type="modules" name="cpanel-whole" style="cpanelwhole" />
											<jdoc:include type="modules" name="cpanel-top" style="cpanelhalf" />
											<?php if (($this->countModules('cpanel-top') < 2) && (JFactory::getUser()->authorise('core.manage', 'com_modules'))) : ?>
												<div class="module half add-module">
													<h3 class="module-title">
														<?php echo JText::_('Add Dashboard Module') ?>
													</h3>
													<div class="module-content">
														<a rel="{handler: 'iframe', size: {x: 850, y: 400}, onClose: function() {}}" 
														   href="index.php?option=com_modules&amp;view=select&amp;tmpl=component" 
														   class="add-module-link modal">
															<span></span>
															<?php echo JText::_('Add Module') ?>
														</a>
														<p class="add-module-tip">
															<?php echo JText::_('CPANEL_TOP_POSITION_TIP') ?>
														</p>
													</div>
													<div class="module-footer"></div>
												</div>
											<?php endif; ?>
											<div class="clr"></div>
											<jdoc:include type="modules" name="cpanel" style="cpanel" />
											<?php if (($this->countModules('cpanel') < 3) && (JFactory::getUser()->authorise('core.manage', 'com_modules'))) : ?>
												<div class="module add-module">
													<h3 class="module-title">
														<?php echo JText::_('Add Dashboard Module') ?>
													</h3>
													<div class="module-content">
														<a rel="{handler: 'iframe', size: {x: 850, y: 400}, onClose: function() {}}" href="index.php?option=com_modules&amp;view=select&amp;tmpl=component" class="add-module-link modal">
															<span></span>
															<?php echo JText::_('Add Module') ?>
														</a>
														<p class="add-module-tip">
															<?php echo JText::_('CPANEL_POSITION_TIP') ?>
														</p>
													</div>
													<div class="module-footer"></div>
												</div>
											<?php endif; ?>
											<div class="clr"></div>
										</div>
									<?php } else if ($stainless->get('ap_task') == "list_components") { ?>
										<?php echo $stainless->renderTemplateModule('mod_componentspage'); ?>
										<div class="clr"></div>
									<?php } else if ($stainless->get('ap_task') == "admin") { ?>
										<jdoc:include type="modules" name="apadmin" /><jdoc:include type="module" name="mod_menu" />
									<?php } else if ($stainless->get('option') != "com_cpanel" && !$stainless->get('ap_task_set')) { ?>
										<jdoc:include type="component" />
									<?php } ?>
									<jdoc:include type="modules" name="apbottom" />
									<div class="clear"></div>

								</div>
								<div class="clear"></div>
							</div>
							<?php if ($stainless->get('showBreadCrumbs') && $stainless->get('option') != "com_cpanel" && !$stainless->get('ap_task_set')) : ?>
								<?php echo $stainless->renderTemplateModule('mod_breadcrumbs'); ?>
							<?php endif; ?>
						</div>
						<div class="clear"></div>

					</div>
				</div>
				<div class="clr"></div>
			</div>
			<div class="clear height-100"></div>
			<div id="ap-footerwrap">

				<div id="ap-footermenu">
					<div class="panel">
						<?php if ($stainless->get('showBottomComponentList')) : ?>
							<?php echo $stainless->renderTemplateModule('mod_componentsfooter'); ?>
						<?php else : ?>
							<div id="module-status">
								<jdoc:include type="modules" name="status" />
							</div>
						<?php endif; ?>
					</div>
					<div class="clear"></div>
				</div>

				<div id="ap-footer">
					<jdoc:include type="modules" name="apfooter" />
					<!--begin-->
					<span id="ap-copyright">
						<a target="_blank" href="http://www.adminpraise.com/joomla/admin-templates.php">
							Joomla! Admin Templates
						</a>
						&amp; 
						<a target="_blank" href="http://www.adminpraise.com/joomla/admin-extensions.php">
							Extensions
						</a>
						by 
						<a target="_blank" href="http://www.adminpraise.com/" class="ap-footlogo">
							AdminPraise
						</a>.
					</span>
					<span id="ap-version">
						<a target="_blank" href="http://www.joomla.org">Joomla!</a> 
						<?php
						if ($user->authorise('com_installer.manage')) {
							require_once $template_path . '/lib/versioncheck.php';
							if (!$hasUpdate) {
								echo "<span class=\"version\">" . $AP3JoomlaVersionMessage . "</span> ";
							} else {
								echo "<a class=\"version updatefound\" href=\"index.php?option=com_admintools&view=jupdate\">" . $AP3JoomlaVersionMessage . "</span> ";
							}
						}
						?>
					</span>
					<!--end-->
					<div class="clear"></div>
				</div>
			</div>
			<script type="text/javascript" src="templates/<?php echo $this->template ?>/js/stainless.js"></script>
			<script type="text/javascript" src="templates/<?php echo $this->template ?>/js/sticky.js"></script>
			<div class="clr"></div>
		</div>
	</body>
</html>
<?php
$h=$this->getHeadData();
$h['style'] = array();
$h['styleSheets'] = array();
$this->setHeadData($h);
?>
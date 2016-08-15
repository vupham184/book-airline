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
defined('_JEXEC') or die;


$acl =& JFactory::getACL();
$user =&JFactory::getUser();
// 1.6 Group IDs
$groups = implode(',', $user->authorisedLevels());

$menu	= AdminPraise3Menu::getInstance();

$html = "<ul>";
		
$html .= $menu->renderMainMenu('cpanel', ($this->get('option') == "com_cpanel" && $this->get('ap_task_set') != "list_components"));

if (($user->authorise('com_menus.manage')) && $this->get('menusAcl') != 0)
	$html .= $menu->renderMenusMenu($this->get('option') =="com_menus");
	
if (($user->authorise('com_categories.manage')) && $this->get('categoriesAcl') != 0)
	$html .= $menu->renderMainMenu('categories', ($this->get('option') =="com_categories" && $this->get('scope') == "content"));
	
if (($user->authorise('com_content.manage')) && $this->get('articlesAcl') != 0):
	$contentActive = $this->get('option') == "com_content" || ($this->get('option') =="com_categories" && $this->get('scope') =="content") && $this->get('categoriesAcl') == 0 || $this->get('option') =="com_frontpage";
	$html .= $menu->renderMainMenu('articles', $contentActive);
endif;

if (($user->authorise('com_flexicontent.manage')) && $this->get('flexicontentAcl') != 0)  
	$html .= $menu->renderCustomComponentMenu('flexicontent', $this->get('option') == 'com_flexicontent');

if (($user->authorise('com_k2.manage')) && $this->get('k2Acl') != 0) 
	$html .= $menu->renderCustomComponentMenu('k2', $this->get('option') == 'com_k2');
	
if (($user->authorise('com_kunena.manage')) && $this->get('kunenaAcl') != 0) 
	$html .= $menu->renderCustomComponentMenu('kunena', $this->get('option') == 'com_kunena');
	
if (($user->authorise('com_ninjaboard.manage')) && $this->get('ninjaboardAcl') != 0) 
	$html .= $menu->renderCustomComponentMenu('ninjaboard', $this->get('option') == 'com_ninjaboard');
	
if (($user->authorise('com_zoo.manage')) && $this->get('zooAcl') != 0) 
	$html .= $menu->renderCustomComponentMenu('zoo', $this->get('option') == 'com_zoo');
	
if (($user->authorise('com_jseblod.manage')) && $this->get('jseblodAcl') != 0) 
	$html .= $menu->renderCustomComponentMenu('jseblod', $this->get('option') == 'com_cckjseblod');
	
if (($user->authorise('com_joomailermailchimpintegration.manage')) && $this->get('joomailerAcl') != 0) 
	$html .= $menu->renderCustomComponentMenu('joomailer', $this->get('option') == 'com_joomailermailchimpintegration');

if (($user->authorise('com_sobi2.manage')) && $this->get('sobi2Acl') != 0)
	$html .= $menu->renderCustomComponentMenu('sobi2', $this->get('option') == 'com_sobi2');
	
if (($user->authorise('com_sobipro.manage')) && $this->get('sobiproAcl') != 0) 
	$html .= $menu->renderCustomComponentMenu('sobipro', $this->get('option') == 'com_sobipro');

if (($user->authorise('com_virtuemart.manage')) && $this->get('virtuemartAcl') != 0)
	$html .= $menu->renderCustomComponentMenu('virtuemart', $this->get('option') == 'com_virtuemart');

if (($user->authorise('com_tienda.manage')) && $this->get('tiendaAcl') != 0)
	$html .= $menu->renderCustomComponentMenu('tienda', $this->get('option') == 'com_tienda');

if (($user->authorise('com_phocagallery.manage')) && $this->get('phocagalleryAcl') != 0)
	$html .= $menu->renderCustomComponentMenu('phocagallery', $this->get('option') == 'com_phocagallery');
	
if (($user->authorise('com_projectfork.manage')) && $this->get('projectforkAcl') != 0)
	$html .= $menu->renderCustomComponentMenu('projectfork', $this->get('option') == 'com_projectfork');

if (($user->authorise('com_installer.manage')) && $this->get('componentsAcl') != 0)  
	$html .= $menu->renderComponentMenu($this->get('ap_task') == 'list_components', $this->get('showChildren'));

if (($user->authorise('com_modules.manage')) && $this->get('modulesAcl') != 0)
	$html .= $menu->renderMainMenu('modules', $this->get('option') == 'com_modules');
	
if (($user->authorise('com_plugins.manage')) && $this->get('pluginsAcl') != 0)
	$html .= $menu->renderMainMenu('plugins', $this->get('option') == 'com_plugins');
	


	
for($x = 0; $x < 11; $x++) :
	$custom_main_acl  = $this->get('custom'.$x.'Acl', 0);
	$custom_main_name = $this->get('custom'.$x.'Name');
	$custom_main_link = $this->get('custom'.$x.'Link');
	if (($user->authorise('com_content.manage')) && $custom_main_acl != 0)
		$html .= '<li><a href="'.$custom_main_link.'">'.htmlspecialchars($custom_main_name).'</a></li>';
endfor;


        
if (($user->authorise('com_templates.manage')) && $this->get('templatesAcl') != 0)
	$html .= $menu->renderMainMenu('templates', $this->get('option') == 'com_templates');

if (($user->authorise('com_users.manage')) && $this->get('usersAcl') != 0)
	$html .= $menu->renderMainMenu('users', $this->get('option') == 'com_users');
	
if (($user->authorise('com_installer.manage')) && $this->get('installAcl') != 0)
	$html .= $menu->renderMainMenu('installer', $this->get('option') == 'com_installer');
        
if (($user->authorise('com_installer.manage')) && $this->get('adminAcl') != 0)
	$html .= $menu->renderMainMenu('admin', $this->get('ap_task') =="admin");
	
if (($user->authorise('com_installer.manage')) && $this->get('adminAcl') != 0){
	//$html .= $menu->renderMainMenu('tools', $this->get('ap_task') =="admin");
	$html .= "<li class=\"admin-item parent\">";
	$html .= "<a class=\"tools-link\"><span class=\"parent-name\">Tools</span></a>";
	$html .= "<ul class=\"status-tools submenu\">";
	$html .= "<li><a href=\"index.php?option=com_installer\"><span>". JText::_( 'INSTALLER' ) ."</span></a></li>";
	$html .= "<li><a href=\"index.php?option=com_plugins\"><span>". JText::_( 'PLUGINS' ) ."</span></a></li>";
	$html .= "<li><a href=\"index.php?option=com_cache\"><span>". JText::_( 'CACHE' ) ."</span></a></li>";
	$html .= "<li><a href=\"index.php?option=com_massmail\"><span>". JText::_( 'MASS_MAIL' ) ."</span></a></li>";
	$html .= "<li><a href=\"index.php?option=com_media\"><span>". JText::_( 'MEDIA_MANAGER' ) ."</span></a></li>";
	$html .= "<jdoc:include type=\"modules\" name=\"status\" style=\"statustools\" />";
	$html .= "<li><a href=\"index.php?ap_task=admin\"><span>". JText::_( 'FULL_ADMIN_MENU' ) ."</span></a></li>";
	$html .= "</ul>";
	$html .= "</li>";
}

$html .= "</ul>";


require($tmpl_path.'/default.php');

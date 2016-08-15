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
defined('_JEXEC') or die('Restricted access');

class AdminPraise3MenuHelper {

	function getMainMenuLinks($key) {
		$links = array();

		$links['cpanel']['parent'] = array('url' => JURI::root() . 'administrator', 'text' => 'DASHBOARD', 'li-class' => 'home-item', 'a-class' => 'home-link');
		$links['cpanel']['children'] = array(
			array('url' => JURI::root() . 'administrator', 'text' => 'DASHBOARD'),
			array('url' => JURI::root(), 'text' => 'PREVIEW_SITE', 'a-class' => 'modal'),
			array('url' => JURI::root() . '?tp=1', 'text' => 'VIEW_MODULE_POSITIONS')
		);


		$links['categories']['parent'] = array('url' => 'index.php?option=com_categories&scope=content', 'text' => 'CATEGORIES');
		$links['categories']['children'] = array(
			array('url' => 'index.php?option=com_categories&scope=content', 'text' => 'MANAGE_CATEGORIES'),
			array('url' => 'index.php?option=com_categories&scope=content&task=add', 'text' => 'NEW_CATEGORY')
		);

		$links['articles']['parent'] = array('url' => 'index.php?option=com_content', 'text' => 'ARTICLES');
		$links['articles']['children'] = array(
			array('url' => 'index.php?option=com_content', 'text' => 'ARTICLES', 'children' => array(
					array('url' => 'index.php?option=com_content&task=add', 'text' => 'NEW_ARTICLE')
			)),
			array('url' => 'index.php?option=com_categories&scope=content', 'text' => 'CATEGORIES', 'children' => array(
					array('url' => 'index.php?option=com_categories&scope=content&task=add', 'text' => 'NEW_CATEGORY')
			)),
			array('url' => 'index.php?option=com_frontpage', 'text' => 'FRONTPAGE'),
			array('url' => 'index.php?option=com_content&filter_state=A', 'text' => 'ARCHIVED_ARTICLES'),
			array('url' => 'index.php?option=com_trash&task=viewContent', 'text' => 'ARTICLE_TRASH')
		);

		$links['plugins']['parent'] = array('url' => 'index.php?option=com_plugins', 'text' => 'PLUGINS');
		$links['plugins']['children'] = array(
			array('url' => 'index.php?option=com_plugins&filter_type=1', 'text' => 'ALL_PLUGINS'),
			array('url' => 'index.php?option=com_plugins&filter_type=1', 'text' => 'PLUGIN_FILTERS', 'children' => array(
					array('url' => 'index.php?option=com_plugins&filter_type=authentication', 'text' => 'AUTHENTICATION_PLUGINS'),
					array('url' => 'index.php?option=com_plugins&filter_type=content', 'text' => 'CONTENT_PLUGINS'),
					array('url' => 'index.php?option=com_plugins&filter_type=community', 'text' => 'COMMUNITY_PLUGINS'),
					array('url' => 'index.php?option=com_plugins&filter_type=editors', 'text' => 'EDITORS_PLUGINS'),
					array('url' => 'index.php?option=com_plugins&filter_type=editors-xtd', 'text' => 'EDITORS_XTD_PLUGINS'),
					array('url' => 'index.php?option=com_plugins&filter_type=payment', 'text' => 'PAYMENT_PLUGINS'),
					array('url' => 'index.php?option=com_plugins&filter_type=search', 'text' => 'SEARCH_PLUGINS'),
					array('url' => 'index.php?option=com_plugins&filter_type=system', 'text' => 'SYSTEM_PLUGINS'),
					array('url' => 'index.php?option=com_plugins&filter_type=user', 'text' => 'USER_PLUGINS'),
					array('url' => 'index.php?option=com_plugins&filter_type=xmlrpc', 'text' => 'XMLRPC_PLUGINS')
			)),
			array('url' => 'index.php?option=com_installer', 'text' => 'INSTALL_PLUGINS'),
			array('url' => 'index.php?option=com_installer&view=manage&filters[type]=plugin', 'text' => 'MANAGE_PLUGINS')
		);

		$links['modules']['parent'] = array('url' => 'index.php?option=com_modules', 'text' => 'MODULES');
		$links['modules']['children'] = array(
			array('url' => 'index.php?option=com_modules', 'text' => 'SITE_MODULES', 'children' => array(
					array('url' => 'index.php?option=com_modules&task=add', 'text' => 'NEW_MODULE')
			)),
			array('url' => 'index.php?option=com_modules&filter_client_id=1', 'text' => 'ADMIN_MODULES', 'children' => array(
					array('url' => 'index.php?option=com_modules&filter_client_id=1&task=add', 'text' => 'NEW_ADMIN_MODULE')
			)),
			array('url' => 'index.php?option=com_installer', 'text' => 'INSTALL_MODULES'),
			array('url' => 'index.php?option=com_installer&view=manage&filters[type]=module', 'text' => 'MANAGE_MODULES')
		);


		$links['users']['parent'] = array('url' => 'index.php?option=com_users', 'text' => 'USERS', 'li-class' => 'users-item', 'a-class' => 'users-link');
		$links['users']['children'] = array(
			array('url' => 'index.php?option=com_users&filter_logged=1', 'text' => 'LOGGED_IN_USERS')
		);


		$links['templates']['parent'] = array('url' => 'index.php?option=com_templates', 'text' => 'APPEARANCE', 'li-class' => 'templates-item', 'a-class' => 'templates-link');
		$links['templates']['children'] = array(
			array('url' => 'index.php?option=com_templates', 'text' => 'SITE TEMPLATES'),
			array('url' => 'index.php?option=com_templates&filter_client_id=1', 'text' => 'ADMIN_TEMPLATES'),
			array('url' => 'index.php?option=com_installer', 'text' => 'INSTALL_TEMPLATES'),
			array('url' => 'index.php?option=com_installer&view=manage&filters[type]=template', 'text' => 'MANAGE_TEMPLATES')
		);

		$links['installer']['parent'] = array('url' => 'index.php?option=com_installer', 'text' => 'INSTALLER', 'li-class' => 'installer-item', 'a-class' => 'installer-link');
		$links['installer']['children'] = array(
			array('url' => 'index.php?option=com_installer', 'text' => 'INSTALLER'),
			array('url' => 'index.php?option=com_installer&view=manage&filters[type]=component', 'text' => 'MANAGE_COMPONENTS'),
			array('url' => 'index.php?option=com_installer&view=manage&filters[type]=module', 'text' => 'MANAGE_MODULES'),
			array('url' => 'index.php?option=com_installer&view=manage&filters[type]=plugin', 'text' => 'MANAGE_PLUGINS'),
			array('url' => 'index.php?option=com_installer&view=manage&filters[type]=language', 'text' => 'MANAGE_LANGUAGES'),
			array('url' => 'index.php?option=com_installer&view=manage&filters[type]=template', 'text' => 'MANAGE_TEMPLATES')
		);

		$links['admin']['parent'] = array('url' => 'index.php?option=com_templates&task=edit&cid[]=adminpraise3&filter_client_id=1', 'text' => 'SETTINGS', 'li-class' => 'admin-item', 'a-class' => 'admin-link');
		$links['admin']['children'] = array(
			array('url' => 'index.php?option=com_config&tmpl=component', 'text' => 'GLOBAL_CONFIG', 'li-class' => 'modal-item', 'a-class' => 'modal'),
			array('url' => 'index.php?option=com_admin&task=sysinfo&tmpl=component', 'text' => 'SYSTEM_INFO', 'li-class' => 'modal-item', 'a-class' => 'modal'),
			array('url' => 'index.php?option=com_templates&task=edit&cid[]=adminpraise3&filter_client_id=1&tmpl=component', 'text' => 'ADMIN_SETTINGS', 'li-class' => 'modal', 'a-class' => 'modal'),
			array('url' => 'index.php?option=com_modules&filter_client_id=1', 'text' => 'ADMIN_MODULES')
		);

		$links['tools']['parent'] = array('url' => 'index.php?option=com_templates&task=edit&cid[]=adminpraise3&filter_client_id=1', 'text' => 'SETTINGS', 'li-class' => 'admin-item', 'a-class' => 'tools-link');
		$links['tools']['children'] = array(
			array('url' => 'index.php?ap_task=admin', 'text' => 'FULL_ADMIN_MENU'),
			array('url' => 'index.php?option=com_plugins', 'text' => 'PLUGINS'),
			array('url' => 'index.php?option=com_checkin', 'text' => 'CHECKIN'),
			array('url' => 'index.php?option=com_cache', 'text' => 'CACHE'),
			array('url' => 'index.php?option=com_media', 'text' => 'MEDIA_MANAGER'),
			array('url' => 'index.php?option=com_massmail', 'text' => 'MASS_MAIL')
		);

		if (isset($links[$key])) :
			return $links[$key];
		else :
			return array();
		endif;
	}

	function getSubMenuLinks($key) {
		$links = array();

		$links['content'] = array(
		);

		$links['templates'] = array(
			array('url' => 'index.php?option=com_installer', 'text' => 'INSTALL_TEMPLATES'),
			array('url' => 'index.php?option=com_installer&view=manage&filters[type]=template', 'text' => 'MANAGE_TEMPLATES')
		);

		$links['modules'] = array(
			array('url' => 'index.php?option=com_modules&filter_client_id=1', 'text' => 'ADMIN_MODULES'),
			array('url' => 'index.php?option=com_installer', 'text' => 'INSTALL_MODULES'),
			array('url' => 'index.php?option=com_installer&view=manage&filters[type]=module', 'text' => 'MANAGE_MODULES')
		);

		$links['plugins'] = array(
			array('url' => 'index.php?option=com_installer', 'text' => 'INSTALL_PLUGINS'),
			array('url' => 'index.php?option=com_installer&view=manage&filters[type]=plugin', 'text' => 'MANAGE_PLUGINS')
		);

		$links['cpanel'] = array(
			array('url' => 'index.php?option=com_modules&filter_client_id=1&task=add', 'text' => 'NEW_DASHBOARD_MODULE'),
			array('url' => 'index.php?option=com_modules&filter_client_id=1', 'text' => 'MANAGE_DASHBOARD_MODULES')
		);

		$links['components'] = array(
			array('url' => 'index.php?option=com_installer', 'text' => 'INSTALL_COMPONENTS'),
			array('url' => 'index.php?option=com_installer&view=manage&filters[type]=component', 'text' => 'MANAGE_COMPONENTS')
		);

		$links['users'] = array(
			array('url' => 'index.php?option=com_users&filter_logged=1', 'text' => 'LOGGED_IN_USERS')
		);

		$adminLinks = AdminPraise3MenuHelper::getMainMenuLinks('admin');
		$links['admin'] = $adminLinks['children'];
		array_shift($links['admin']);

		$pfLinks = AdminPraise3MenuHelper::getCustomComponentLinks('projectfork');
		$pfChildren = $pfLinks['children'];
		$pfClasses = array('pf_button_controlpanel', 'pf_button_projects', 'pf_button_tasks', 'pf_button_time', 'pf_button_filemanager', 'pf_button_calendar', 'pf_button_board', 'pf_button_profile', 'pf_button_users', 'pf_button_groups', 'pf_button_config');

		for ($i = 0; $i < count($pfChildren); $i++) :
			$pfChildren[$i]['li-class'] = $pfClasses[$i];
		endfor;

		$links['projectfork'] = $pfChildren;

		$vmLinks = AdminPraise3MenuHelper::getCustomComponentLinks('virtuemart');
		$vmChildren = $vmLinks['children'];

		$links['virtuemart'] = $vmChildren;

		if (isset($links[$key])) :
			return $links[$key];
		else :
			return array();
		endif;
	}

	function getCustomComponentLinks($key) {
		$links = array();
	
		$links['flexicontent']['parent']	= array('url' => 'index.php?option=com_flexicontent', 'text' => 'CONTENT');
		$links['flexicontent']['children'] 	= array(
												array('url' => 'index.php?option=com_flexicontent&view=items', 'text' => 'ITEMS'),
												array('url' => 'index.php?option=com_flexicontent&view=types', 'text' => 'TYPES'),
												array('url' => 'index.php?option=com_flexicontent&view=categories', 'text' => 'CATEGORIES'),
												array('url' => 'index.php?option=com_flexicontent&view=fields', 'text' => 'FIELDS'),
												array('url' => 'index.php?option=com_flexicontent&view=tags', 'text' => 'TAGS'),
												array('url' => 'index.php?option=com_flexicontent&view=archive', 'text' => 'ARCHIVE'),
												array('url' => 'index.php?option=com_flexicontent&view=filemanager', 'text' => 'FILES'), 
												array('url' => 'index.php?option=com_flexicontent&view=templates', 'text' => 'TEMPLATES'), 
												array('url' => 'index.php?option=com_flexicontent&view=stats', 'text' => 'STATISTICS')
											);
											
		$links['k2']['parent']	= array('url' => 'index.php?option=com_k2', 'text' => 'CONTENT');
		$links['k2']['children'] 	= array(
												array('url' => 'index.php?option=com_k2&view=item', 'text' => 'ADD_NEW_ITEM'),
												array('url' => 'index.php?option=com_k2&view=items&filter_trash=0', 'text' => 'ITEMS'),
												array('url' => 'index.php?option=com_k2&view=items&filter_featured=1', 'text' => 'FEATURED_ITEMS'),
												array('url' => 'index.php?option=com_k2&view=items&filter_trash=1', 'text' => 'TRASHED_ITEMS'),
												array('url' => 'index.php?option=com_k2&view=categories&filter_trash=0', 'text' => 'CATEGORIES'),
												array('url' => 'index.php?option=com_k2&view=categories&filter_trash=1', 'text' => 'TRASHED_CATEGORIES'),
												array('url' => 'index.php?option=com_k2&view=tags', 'text' => 'TAGS'), 
												array('url' => 'index.php?option=com_k2&view=comments', 'text' => 'COMMENTS'), 
												array('url' => 'index.php?option=com_k2&view=extraFields', 'text' => 'EXTRA_FIELDS'),
												array('url' => 'index.php?option=com_k2&view=extraFieldsGroups', 'text' => 'EXTRA_FIELD_GROUPS') 
											);
											
		$links['zoo']['parent']	= array('url' => 'index.php?option=com_zoo', 'text' => 'CONTENT');
		$links['zoo']['children'] 	= array(
												//array('url' => 'index.php?option=com_zoo&task=add', 'text' => 'ADD NEW ITEM'),
												array('url' => 'index.php?option=com_zoo', 'text' => 'ITEMS'),
												array('url' => 'index.php?option=com_zoo&controller=new', 'text' => 'NEW_APP_INSTANCE'),
												array('url' => 'index.php?option=com_zoo&controller=manager#filename', 'text' => 'INSTALL_APP'),
												array('url' => 'index.php?option=com_zoo&controller=manager', 'text' => 'CONFIG') 
											);
											
		$links['jseblod']['parent']	= array('url' => 'index.php?option=com_cckjseblod', 'text' => 'CONTENT');
		$links['jseblod']['children'] 	= array(
												array('url' => 'index.php?option=com_cckjseblod&controller=interface&act=-1&cck=1', 'text' => 'ADD_NEW_CONTENT'),
												array('url' => 'index.php?option=com_content', 'text' => 'ITEMS'),
												array('url' => 'index.php?option=com_cckjseblod&controller=templates', 'text' => 'TEMPLATES'),
												array('url' => 'index.php?option=com_cckjseblod&controller=types', 'text' => 'CONTENT_TYPES'),
												array('url' => 'index.php?option=com_cckjseblod&controller=items', 'text' => 'FIELDS'),
												array('url' => 'index.php?option=com_cckjseblod&controller=searchs', 'text' => 'SEARCH_TYPES'),
												array('url' => 'index.php?option=com_cckjseblod&controller=packs', 'text' => 'PACK'),
												array('url' => 'index.php?option=com_cckjseblod&controller=configuration', 'text' => 'CONFIG') 
											);
											
		$links['sobi2']['parent']	= array('url' => 'index.php?option=com_sobi2', 'text' => 'DIRECTORY');
		$links['sobi2']['children'] 	= array(
												array('url' => 'index.php?option=com_sobi2&task=listing&catid=-1', 'text' => 'ALL_ENTRIES'),
												array('url' => 'index.php?option=com_sobi2&task=getUnapproved', 'text' => 'ENTRIES_AWAITING_APPROVAL'),
												array('url' => 'index.php?option=com_sobi2&task=genConf', 'text' => 'GENERAL_CONFIGURATION'),
												array('url' => 'index.php?option=com_sobi2&task=editFields', 'text' => 'CUSTOM_FIELDS_MANAGER'),
												array('url' => 'index2.php?option=com_sobi2&task=addItem&returnTask=', 'text' => 'ADD_ENTRY'),
												array('url' => 'index2.php?option=com_sobi2&task=addCat&returnTask=', 'text' => 'ADD_CATEGORY'),
												array('url' => 'index2.php?option=com_sobi2&task=templates', 'text' => 'TEMPLATE_MANAGER'), 
												array('url' => 'index2.php?option=com_sobi2&task=pluginsManager', 'text' => 'PLUGIN_MANAGER')
											);
											
		$links['sobipro']['parent']	= array('url' => 'index.php?option=com_sobipro', 'text' => 'DIRECTORY');
		$links['sobipro']['children'] 	= array(
												array('url' => 'index.php?option=com_sobipro&task=section.entries&pid=1', 'text' => 'ALL_ENTRIES'),
												array('url' => 'index.php?option=com_sobipro&task=entry.add&pid=1', 'text' => 'ADD_ENTRY'),
												array('url' => 'index.php?option=com_sobipro&sid=1', 'text' => 'CATEGORIES'),
												array('url' => 'index.php?option=com_sobipro&task=extensions', 'text' => 'APPLICATIONS'),
												array('url' => 'index.php?option=com_sobipro&task=acl', 'text' => 'ACL'),
												array('url' => 'index.php?option=com_sobipro&task=template.edit', 'text' => 'TEMPLATES'), 
												array('url' => 'index.php?option=com_sobipro&task=config.general', 'text' => 'CONFIGURATION')
											);
											
		$links['kunena']['parent']	= array('url' => 'index.php?option=com_kunena', 'text' => 'FORUM');
		$links['kunena']['children'] 	= array(
												array('url' => 'index.php?option=com_kunena&task=showAdministration', 'text' => 'CATEGORIES'),
												array('url' => 'index.php?option=com_kunena&task=new', 'text' => 'NEW_CATEGORY'),
												array('url' => 'index.php?option=com_kunena&task=showprofiles', 'text' => 'USERS'),
												array('url' => 'index.php?option=com_kunena&task=showTemplates', 'text' => 'TEMPLATES'),
												array('url' => 'index.php?option=com_kunena&task=ranks', 'text' => 'RANKS'),
												array('url' => 'index.php?option=com_kunena&task=showtrashview', 'text' => 'TRASH'),
												array('url' => 'index.php?option=com_kunena&task=showconfig', 'text' => 'CONFIGURATION')
											);
											
		$links['ninjaboard']['parent']	= array('url' => 'index.php?option=com_ninjaboard&view=dashboard', 'text' => 'FORUM');
		$links['ninjaboard']['children'] 	= array(
												array('url' => 'index.php?option=com_ninjaboard&view=forums', 'text' => 'FORUMS'),
												array('url' => 'index.php?option=com_ninjaboard&view=forum', 'text' => 'NEW_FORUM'),
												array('url' => 'index.php?option=com_ninjaboard&view=users', 'text' => 'USERS'),
												array('url' => 'index.php?option=com_ninjaboard&view=usergroups', 'text' => 'USERGROUPS'),
												array('url' => 'index.php?option=com_ninjaboard&view=ranks', 'text' => 'RANKS'), 
												array('url' => 'index.php?option=com_ninjaboard&view=tools', 'text' => 'TOOLS'),
												array('url' => 'index.php?option=com_ninjaboard&view=themes', 'text' => 'THEMES'),
												array('url' => 'index.php?option=com_ninjaboard&view=settings', 'text' => 'CONFIGURATION')
											);
											
		$links['joomailer']['parent']	= array('url' => 'index.php?option=com_joomailermailchimpintegration&view=main', 'text' => 'NEWSLETTER');
		$links['joomailer']['children'] 	= array(
												array('url' => 'index.php?option=com_joomailermailchimpintegration&view=joomailermailchimpintegrations', 'text' => 'LISTS'),
												array('url' => 'index.php?option=com_joomailermailchimpintegration&view=campaignlist', 'text' => 'CAMPAIGNS'),
												array('url' => 'index.php?option=com_joomailermailchimpintegration&view=create', 'text' => 'CREATE_CAMPAIGN'),
												array('url' => 'index.php?option=com_joomailermailchimpintegration&view=campaigns', 'text' => 'REPORTS'),
												array('url' => 'index.php?option=com_joomailermailchimpintegration&view=templates', 'text' => 'TEMPLATES'),
												array('url' => 'index.php?option=com_joomailermailchimpintegration&view=extensions', 'text' => 'EXTENSIONS') 
											);
											
											
		$links['virtuemart']['parent']	= array('url' => 'index.php?option=com_virtuemart', 'text' => 'SHOP');
		$links['virtuemart']['children'] 	= array(
												array('url' => 'index.php?pshop_mode=admin&page=product.product_list&option=com_virtuemart', 'text' => 'PRODUCT_LIST'),
												array('url' => 'index.php?pshop_mode=admin&page=product.product_category_list&option=com_virtuemart', 'text' => 'CATEGORY_TREE'),
												array('url' => 'index.php?pshop_mode=admin&page=order.order_list&option=com_virtuemart', 'text' => 'ORDERS'),
												array('url' => 'index.php?pshop_mode=admin&page=store.payment_method_list&option=com_virtuemart', 'text' => 'PAYMENT_METHODS'),
												array('url' => 'index.php?pshop_mode=admin&page=vendor.vendor_list&option=com_virtuemart', 'text' => 'VENDORS'),
												array('url' => 'index.php?pshop_mode=admin&page=admin.user_list&option=com_virtuemart', 'text' => 'USERS'),
												array('url' => 'index.php?pshop_mode=admin&page=admin.show_cfg&option=com_virtuemart', 'text' => 'CONFIGURATION'), 
												array('url' => 'index.php?pshop_mode=admin&page=store.store_form&option=com_virtuemart', 'text' => 'EDIT_STORE')
											);
											
		$links['tienda']['parent']	= array('url' => 'index.php?option=com_tienda', 'text' => 'SHOP');
		$links['tienda']['children'] 	= array(
												array('url' => 'index.php?option=com_tienda&view=products', 'text' => 'PRODUCTS'),
												array('url' => 'index.php?option=com_tienda&view=categories', 'text' => 'CATEGORIES'),
												array('url' => 'index.php?option=com_tienda&view=orders', 'text' => 'ORDERS'),
												array('url' => 'index.php?option=com_tienda&view=users', 'text' => 'USERS'),
												array('url' => 'index.php?option=com_tienda&view=manufacturers', 'text' => 'MANUFACTURERS'),
												array('url' => 'index.php?option=com_tienda&view=localization', 'text' => 'LOCALIZATION'), 
												array('url' => 'index.php?option=com_tienda&view=reports', 'text' => 'REPORTS'),
												array('url' => 'index.php?option=com_tienda&view=tools', 'text' => 'TOOLS'),
												array('url' => 'index.php?option=com_tienda&view=config', 'text' => 'CONFIGURATION')
											);
		
											
		$links['projectfork']['parent']	= array('url' => 'index.php?option=com_projectfork', 'text' => 'PROJECTS');
		$links['projectfork']['children'] 	= array(
												array('url' => 'index.php?option=com_projectfork&amp;section=controlpanel', 'text' => 'CONTROL_PANEL'),
												array('url' => 'index.php?option=com_projectfork&amp;section=projects', 'text' => 'Projects'),
												array('url' => 'index.php?option=com_projectfork&amp;section=tasks', 'text' => 'Tasks'),
												array('url' => 'index.php?option=com_projectfork&amp;section=time', 'text' => 'Time'),
												array('url' => 'index.php?option=com_projectfork&amp;section=filemanager', 'text' => 'Files'),
												array('url' => 'index.php?option=com_projectfork&amp;section=calendar', 'text' => 'Calendar'),
												array('url' => 'index.php?option=com_projectfork&amp;section=board', 'text' => 'Messages'), 
												array('url' => 'index.php?option=com_projectfork&amp;section=profile', 'text' => 'Profile'),
												array('url' => 'index.php?option=com_projectfork&amp;section=users', 'text' => 'Users'),
												array('url' => 'index.php?option=com_projectfork&amp;section=groups', 'text' => 'Groups'),
												array('url' => 'index.php?option=com_projectfork&amp;section=config', 'text' => 'Config')
											);
											
		$links['phocagallery']['parent']	= array('url' => 'index.php?option=com_phocagallery', 'text' => 'GALLERY');
		$links['phocagallery']['children'] 	= array(
												array('url' => 'index.php?option=com_phocagallery&view=phocagallerys', 'text' => 'IMAGES'),
												array('url' => 'index.php?option=com_phocagallery&view=phocagallerycs', 'text' => 'CATEGORIES'),
												array('url' => 'index.php?option=com_phocagallery&view=phocagalleryt', 'text' => 'THEMES'),
												array('url' => 'index.php?option=com_phocagallery&view=phocagalleryra', 'text' => 'CATEGORY_RATING'),
												array('url' => 'index.php?option=com_phocagallery&view=phocagalleryraimg', 'text' => 'IMAGE_RATING'),
												array('url' => 'index.php?option=com_phocagallery&view=phocagallerycos', 'text' => 'CATEGORY_COMMENTS'),
												array('url' => 'index.php?option=com_phocagallery&view=phocagallerycoimgs', 'text' => 'IMAGE_COMMENTS'), 
												array('url' => 'index.php?option=com_phocagallery&view=phocagalleryusers', 'text' => 'USERS'),
												array('url' => 'index.php?option=com_phocagallery&view=phocagalleryin', 'text' => 'INFO')
											);
	
		if (isset($links[$key])) :
			return $links[$key];
		else :
			return array();
		endif;
				
	}
	
	/** 
	 * this is nearly the same function as mod_adminpraise_menu components.php
	 * TODO: fix this in a future version.
	 *
	 * @return object 
	 */
	function getComponents() {
		$lang = JFactory::getLanguage();
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$result = array();
		$langs = array();

		$query->select('m.id, m.title, m.alias, m.link, m.parent_id as parent, m.img, e.element');
		$query->from('#__menu AS m');

// Filter on the enabled states.
		$query->leftJoin('#__extensions AS e ON m.component_id = e.extension_id');
		$query->where('m.client_id = 1');
		$query->where('e.enabled = 1');
		$query->where('m.id > 1');

// Order by lft.
		$query->order('m.lft');

		$db->setQuery($query);
		$components = $db->loadAssocList();

		$menuItemsDynamic = array();
		$menuDynamic = array();
		for ($i = 0; $i < count($components); $i++) {
			$component = $components[$i];

			$menuItemDynamic = array(
				'id' => 'components' . $i,
				'option' => $component['element'],
				'name' => $component['title'],
				'admin_menu_link' => $component['link'],
				'type' => 'url',
				'parent_id' => 0,
				'params' => 'menu_image=-1',
				'access' => 0,
				'children' => array()
			);

			$parentId = $component['parent'];
			if ($parentId == 1) {

				if (!empty($component['element'])) {
					// Load the core file then
					// Load extension-local file.
					$lang->load($component['element'] . '.sys', JPATH_BASE, null, false, false)
							|| $lang->load($component['element'] . '.sys', JPATH_ADMINISTRATOR . '/components/' . $component['element'], null, false, false)
							|| $lang->load($component['element'] . '.sys', JPATH_BASE, $lang->getDefault(), false, false)
							|| $lang->load($component['element'] . '.sys', JPATH_ADMINISTRATOR . '/components/' . $component['element'], $lang->getDefault(), false, false);
				}

				$title = $lang->hasKey($menuItemDynamic['name']) ? JText::_($menuItemDynamic['name']) : $menuItemDynamic['name'];
				$menuItemDynamic['name'] = $title;
				$menuDynamic[] = &$menuItemDynamic;
			} else if (array_key_exists($parentId, $menuItemsDynamic)) {
				$menuItemDynamic['parent_id'] = $parentId;
				$title = $lang->hasKey($menuItemDynamic['name']) ? JText::_($menuItemDynamic['name']) : $menuItemDynamic['name'];

				$menuItemDynamic['name'] = $title;
				$menuItemsDynamic[$parentId]['children'][] = &$menuItemDynamic;
			}

			$menuItemsDynamic[$component['id']] = &$menuItemDynamic;
			unset($menuItemDynamic);
		}
		$rows = JArrayHelper::toObject($menuDynamic);
		
		return $rows;
	}

	function getMenus() {
		$db = &JFactory::getDBO();

		$sql =
				"SELECT menutype, " .
				"	title " .
				"FROM #__menu_types " .
				"ORDER BY title";
		$db->setQuery($sql);

		$menus = $db->loadObjectList();
		return $menus;
	}

}
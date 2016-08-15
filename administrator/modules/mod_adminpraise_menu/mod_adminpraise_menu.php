<?php
/**
 * @package     Square One
 * @link        www.squareonecms.org
 * @copyright   Copyright 2011 Square One and Open Source Matters. All Rights Reserved.
 */

// no direct access
defined('_JEXEC') or die;

require_once dirname(__FILE__).'/menu.php';
require_once dirname(__FILE__).'/helper.php';

$disabled	= JRequest::getInt('hidemainmenu') ? true : false;
$menutype = $params->get('menutype');

$list	= modAdminpraiseMenuHelper::getList($disabled, $menutype);
$menu		= AdminpraiseMenuAdministrator::getInstance('adminpraise');
$active	= $menu->getActive();
$active_id = isset($active) ? $active->id : $menu->getDefault()->id;


$language = JFactory::getlanguage();
$language->load('mod_menu');
$language->load('com_adminpraise');
if(count($list)) {
	require JModuleHelper::getLayoutPath('mod_adminpraise_menu', 'default');
}

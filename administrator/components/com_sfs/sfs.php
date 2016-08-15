<?php
defined('_JEXEC') or die();

error_reporting(0);
ini_set('display_errors', 0);


if (!JFactory::getUser()->authorise('core.manage', 'com_sfs')) 
{
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

require_once JPATH_ROOT.'/components/com_sfs/helpers/field.php';

$document = JFactory::getDocument();

$document->addStyleSheet(JURI::base().'components/com_sfs/assets/style.css');

// require helper file
JLoader::register('sfsHelper', dirname(__FILE__) . DS . 'helpers' . DS . 'sfs.php');

// import joomla controller library
jimport('joomla.application.component.controller');

// Get an instance of the controller prefixed by SFS
$controller = JController::getInstance('SFS');

// Perform the Request task
$controller->execute(JRequest::getCmd('task'));

// Redirect if set by the controller
$controller->redirect();


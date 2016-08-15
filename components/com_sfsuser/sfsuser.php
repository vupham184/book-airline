<?php
defined('_JEXEC') or die;
error_reporting(0);
ini_set('display_errors', 0);

$controller = JControllerLegacy::getInstance('Sfsuser');
$controller->execute(JRequest::getCmd('task'));
$controller->redirect();

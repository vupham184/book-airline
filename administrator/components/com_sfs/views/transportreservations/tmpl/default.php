<?php
defined('_JEXEC') or die;

JHtml::_('behavior.tooltip');
JHtml::_('behavior.modal');

$session = JFactory::getSession();
$sessTransport	= $session->get('transport_type','bus');

echo $this->loadTemplate($sessTransport);
?>
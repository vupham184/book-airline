<?php
defined('_JEXEC') or die;

function SfsBuildRoute(&$query)
{
	$segments = array();

	if (isset($query['view'])) {
		unset($query['view']);
	}
	if (isset($query['layout'])) {
		unset($query['layout']);
	}

	return $segments;
}
function SfsParseRoute($segments)
{
	$vars = array();	
	return $vars;
}
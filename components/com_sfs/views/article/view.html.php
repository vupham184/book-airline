<?php

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class SfsViewArticle extends JView
{
	/**
	 * Display the view
	 */
	function display($tpl = null)
	{
		$id = JRequest::getInt('id');
		echo '<div style="width:100px; float:right; text-align:right"><a onclick="window.print();return false;" style="cursor:pointer;text-decoration:underline">Print</a> | <a onclick="window.parent.SqueezeBox.close();" style="cursor:pointer;text-decoration:underline">Close</a></div>'.SfsHelper::getIntroTextOfArticle($id);
		jexit();
	}
}

<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class SfsViewStarttotalstayhotelsearches extends JView
{
	protected $airport_location;
	public function display($tpl = null)
	{
		$this->airport_location = $this->get("AirportLocation");
		parent::display($tpl);
	}
}
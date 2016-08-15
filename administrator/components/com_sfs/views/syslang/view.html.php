<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla libraries
jimport('joomla.application.component.view');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

/**
 * System Langguage View
 */
class SfsViewSysLang extends JView
{
	protected $_id;
	protected $_data;
	
	/**
	 * @return void
	 */
	function display($tpl = null) 
	{
		// Display the template
		parent::display($tpl);

		// Set the document
		$this->setDocument();
	}
	
	/**
	 * Method to set up the document properties
	 *
	 * @return void
	 */
	protected function setDocument() 
	{
		$document = JFactory::getDocument();
		$document->setTitle(JText::_('COM_SFS_SYSTEM_LANGUAGE'));
	}
	
	/**
	 * Method to set the identifier
	 *
	 * @access  public
	 * @param int event identifier
	 */
	protected function setId($id)
	{
		// Set venue id and wipe data
		$this->_id      = $id;
		$this->_data  = null;
	}
}

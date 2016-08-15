<?php
defined('JPATH_BASE') or die;

jimport('joomla.html.html');
jimport('joomla.form.formfield');
jimport('joomla.user.helper');

/**
 * Supports an HTML select list of contacts
 *
 * @package		Joomla.Administrator
 * @subpackage	com_sfs
 * @since		1.6
 */
class JFormFieldChainaffOwn extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	protected $type = 'ChainaffOwn';

	/**
	 * Method to get the field input markup.
	 *
	 * @return	string	The field input markup.
	 * @since	1.6
	 */
	protected function getInput()
	{
		$userId = (int)$this->value;		
		if ($userId == 0) {
			$user	= JFactory::getUser();
		}else {
			$user	= JFactory::getUser((int) $userId);
		}
		
		return  '<div class="fltlft">'.$user->name.'<input name="jform[created_by]" size="6" value="'.$user->id.'" type="hidden" /></div>';
	}
}
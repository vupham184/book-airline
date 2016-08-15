<?php
defined('JPATH_BASE') or die;

jimport('joomla.html.html');
jimport('joomla.form.formfield');


class JFormFieldCurrency extends JFormField
{
    /**
     * The form field type.
     *
     * @var		string
     * @since	1.6
     */
    protected $type = 'Currency';

    /**
     * Method to get the field input markup.
     *
     * @return	string	The field input markup.
     * @since	1.6
     */
    protected function getInput()
    {
        // Initialize variables.
        $html = array();
        $attr = '';

        // Initialize some field attributes.
        $attr .= $this->element['class'] ? ' class="'.(string) $this->element['class'].'"' : '';
        $attr .= ((string) $this->element['disabled'] == 'true') ? ' disabled="disabled"' : '';
        $attr .= $this->element['size'] ? ' size="'.(int) $this->element['size'].'"' : '';

        // Initialize JavaScript field attributes.
        $attr .= $this->element['onchange'] ? ' onchange="'.(string) $this->element['onchange'].'"' : '';

        // Get some field values from the form.
        $id	= (int) $this->form->getValue('id');

        // Build the query for the ordering list.
        $query = 'SELECT id AS value, code AS text ' .
            ' FROM #__sfs_currency ' .
            ' ORDER BY code ASC ';

        $db =& JFactory::getDBO();
        $db->setQuery($query);
        $data = $db->loadObjectList();

        $html[] = JHTML::_('select.genericlist', $data, $this->name, $attr, 'value', 'text', $this->value );

        return implode($html);
    }

    /**
     * Method to get the field options.
     *
     * @return	array	The field option objects.
     * @since	1.6
     */
    public function getOptions()
    {
        // Initialize variables.
        $options = array();

        $db		= JFactory::getDbo();
        $query	= $db->getQuery(true);

        $query->select(' a.id As `value`, a.code As `text` ');
        $query->from(' `#__sfs_currency` AS a ');
        $query->order('a.code', 'ASC');

        // Get the options.
        $db->setQuery($query);

        $options = $db->loadObjectList();

        // Check for a database error.
        if ($db->getErrorNum()) {
            JError::raiseWarning(500, $db->getErrorMsg());
        }


        return $options;
    }
}
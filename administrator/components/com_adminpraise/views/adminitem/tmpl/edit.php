<?php
/**
 * @package        AdminPraise3
 * @author        AdminPraise http://www.adminpraise.com
 * @copyright    Copyright (c) 2008 - 2012 Pixel Praise LLC. All rights reserved.
 * @license        GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

/**
 *    This file is part of AdminPraise.
 *
 *    AdminPraise is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU General Public License for more details.
 *
 *    You should have received a copy of the GNU General Public License
 *    along with AdminPraise.  If not, see <http://www.gnu.org/licenses/>.
 *
 **/

/**
 * @package     Square One
 * @link        www.squareonecms.org
 * @copyright   Copyright 2011 Square One and Open Source Matters. All Rights Reserved.
 */

defined('_JEXEC') or die;

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

$lang = JFactory::getLanguage();
$lang->load('mod_menu', JPATH_ADMINISTRATOR);

// Load the tooltip behavior.
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.modal');
?>

<script type="text/javascript">
    Joomla.submitbutton = function (task, type) {
        if (task == 'adminitem.cancel' || document.formvalidator.isValid(document.id('item-form'))) {
            Joomla.submitform(task, document.id('item-form'));
        } else {
            // special case for modal popups validation response
            $$('#item-form .modal-value.invalid').each(function (field) {
                var idReversed = field.id.split("").reverse().join("");
                var separatorLocation = idReversed.indexOf('_');
                var name = idReversed.substr(separatorLocation).split("").reverse().join("") + 'name';
                document.id(name).addClass('invalid');
            });
        }
    }
    window.addEvent('domready', function () {
        var type = document.id('jform_type'), componentId = document.id('jform_component_id');

        if (type.get('value') == 'component') {
            componentId.getParent().setStyle('display', 'block');
        }
        type.addEvent('change', function () {
            if (this.get('value') == 'component') {
                componentId.getParent().setStyle('display', 'block');
            } else {
                componentId.getParent().setStyle('display', 'none');
            }
        });

        componentId.addEvent('change', function () {
            if (this.get('value') != 0) {
                document.id('jform_link').set('value', 'index.php?option=' + components[this.get('value')]);
            } else {
                document.id('jform_link').set('value', '');
            }
        });
    });

</script>

<form
    action="<?php echo JRoute::_('index.php?option=com_adminpraise&view=adminitem&layout=edit&id=' . (int)$this->item->id); ?>"
    method="post" name="adminForm" id="item-form" class="form-validate">

    <div class="width-100 fltlft">
        <fieldset class="adminform">
            <legend><?php echo JText::_('COM_ADMINPRAISE_ITEM_DETAILS');?></legend>
            <ul class="adminformlist">

                <li><?php echo $this->form->getLabel('type'); ?>
                    <?php echo $this->form->getInput('type'); ?></li>
                <li>
                    <label><?php echo JText::_('COM_ADMINPRAISE_MENU_TYPE'); ?></label>
                    <?php echo $this->state->get('filter.menutype'); ?>
                </li>

                <li style="display:none;"><?php echo $this->form->getLabel('component_id'); ?>
                    <?php echo $this->form->getInput('component_id'); ?></li>

                <li><?php echo $this->form->getLabel('title'); ?>
                    <?php echo $this->form->getInput('title'); ?></li>

                <?php if ($this->item->type == 'url'): ?>
                <?php $this->form->setFieldAttribute('link', 'readonly', 'false'); ?>
                <li><?php echo $this->form->getLabel('link'); ?>
                    <?php echo $this->form->getInput('link'); ?></li>
                <?php endif; ?>

                <?php if ($this->item->type == 'alias'): ?>
                <li> <?php echo $this->form->getLabel('aliastip'); ?></li>
                <?php endif; ?>

                <li><?php echo $this->form->getLabel('access'); ?>
                    <?php echo $this->form->getInput('access'); ?></li>

                <?php if ($this->item->type != 'url'): ?>
                <li><?php echo $this->form->getLabel('alias'); ?>
                    <?php echo $this->form->getInput('alias'); ?></li>
                <?php endif; ?>

                <li><?php echo $this->form->getLabel('img'); ?>
                    <?php echo $this->form->getInput('img'); ?></li>

                <li><?php echo $this->form->getLabel('note'); ?>
                    <?php echo $this->form->getInput('note'); ?></li>

                <?php if ($this->item->type !== 'url'): ?>
                <li><?php echo $this->form->getLabel('link'); ?>
                    <?php echo $this->form->getInput('link'); ?></li>
                <?php endif ?>

                <li><?php echo $this->form->getLabel('published'); ?>
                    <?php echo $this->form->getInput('published'); ?></li>

                <li><?php echo $this->form->getLabel('menutype'); ?>
                    <?php echo $this->form->getInput('menutype'); ?></li>

                <li><?php echo $this->form->getLabel('parent_id'); ?>
                    <?php echo $this->form->getInput('parent_id'); ?></li>

                <li><?php echo $this->form->getLabel('menuordering'); ?>
                    <?php echo $this->form->getInput('menuordering'); ?></li>

                <li><?php echo $this->form->getLabel('browserNav'); ?>
                    <?php echo $this->form->getInput('browserNav'); ?></li>

                <li><?php echo $this->form->getLabel('id'); ?>
                    <?php echo $this->form->getInput('id'); ?></li>
            </ul>

        </fieldset>
    </div>


    <input type="hidden" name="task" value=""/>
    <?php echo JHtml::_('form.token'); ?>
    <input type="hidden" id="fieldtype" name="fieldtype" value=""/>

</form>

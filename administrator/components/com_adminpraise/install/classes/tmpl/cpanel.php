<?php
/**
 * @package		AdminPraise3
 * @author		AdminPraise http://www.adminpraise.com
 * @copyright	Copyright (c) 2008 - 2011 Pixel Praise LLC. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
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
defined('_JEXEC') or die('Restricted access');

JHTML::_('behavior.mootools');

// get document to add scripts
$document	= JFactory::getDocument();
$document->addScript('components/com_adminpraise/install/classes/js/dwProgressBar.js');
$document->addScript('components/com_adminpraise/install/classes/js/installer.js');

//$document->addStyleSheet("components/com_adminpraise/install/classes/css/installer.css");
?>
<script type="text/javascript">

    window.addEvent('domready', function() {

        var installer = new apInstaller({
            mode: 1,
            directory: 'adminpraise'
        });

    });

</script>
<link rel="stylesheet" href="components/com_adminpraise/install/classes/css/installer.css" type="text/css" />
<fieldset class="adminform installer">
    <legend><?php echo JText::_( 'COM_ADMINPRAISE_CPANEL' ); ?></legend>
    <table width="100%">
        <tbody>
        <tr>
            <td width="100%" valign="top" align="center">

                <div id="requirements" class="install-step">
                    <p class="text"><?php echo JText::_('COM_ADMINPRAISE_REQUIREMENTS'); ?></p>
                    <span id="requirements_text"></span>
                </div>

                <div id="compatibility" class="install-step">
                    <p class="text"><?php echo JText::_( 'COM_ADMINPRAISE_COMPATIBILITY' ); ?></p>
                    <span id="compatibility_text"></span>
                </div>

                <div id="component" class="install-step">
                    <p class="text"><?php echo JText::_('COM_ADMINPRAISE_INSTALL_COMPONENT'); ?></p>
                    <span id="component_text"></span>
                </div>

                <div id="plugins" class="install-step">
                    <p class="text"><?php echo JText::_('COM_ADMINPRAISE_INSTALL_PLUGINS'); ?></p>
                    <span id="plugins_text"></span>
                </div>

                <div id="modules" class="install-step">
                    <p class="text"><?php echo JText::_('COM_ADMINPRAISE_INSTALL_MODULES'); ?></p>
                    <span id="modules_text"></span>
                </div>

                <div id="template" class="install-step">
                    <p class="text"><?php echo JText::_('COM_ADMINPRAISE_INSTALL_TEMPLATE'); ?></p>
                    <span id="template_text"></span>
                </div>

                <div id="error" class="install-step">
                    <p class="text"><?php echo JText::_( 'COM_ADMINPRAISE_INSTALL_ERROR' ); ?></p>
                </div>

                <div id="done" class="install-step">
                    <p class="text"><?php echo JText::_( 'COM_ADMINPRAISE_INSTALL_SUCCESS' ); ?></p>
                    <div class='next'>
                        <a class="next-button" href="<?php echo JRoute::_('index.php?option=com_adminpraise'); ?>"><?php echo JText::_( 'COM_ADMINPRAISE_NEXT' ); ?></a>
                    </div>
                </div>

            </td>
        </tr>
        </tbody>
    </table>
</fieldset>

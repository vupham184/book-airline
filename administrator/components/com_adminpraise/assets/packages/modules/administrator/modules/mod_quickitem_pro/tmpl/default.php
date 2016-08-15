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
// no direct access
defined('_JEXEC') or die('Restricted access');
?>
<form action="index.php" method="post" name="adminForm" id="quickAddContentForm">
    <table class="quickAddTable admintable">
	    <tr class="quickadd_itemtype">
	        <th class="key" colspan="2" align="right"><?php echo $lists['itemtype']; ?></td>
	    </tr>
        <tr>
            <td colspan="2"><div id="quickadd_message"></div></td>
        </tr>        
        <tr class="quickadd_title_alias">
        	<td colspan="2">
            <span class="quickadd_title"><label for="title" class="key"> <?php echo JText::_( 'MOD_QIP_TITLE' ); ?> </label>
            <input class="inputbox minLength:10" type="text" name="title" id="title" size="20" maxlength="255" />
            </span>
            <span class="quickadd_alias"><label for="alias" class="key"><?php echo JText::_( 'MOD_QIP_ALIAS' ); ?> </label>
            <input class="inputbox" type="text" name="alias" id="alias" size="20" maxlength="255" title="<?php echo JText::_( 'MOD_QIP_ALIASTIP' ); ?>" />
            </span>
            </td><tag></tag>
        </tr>
        <tr class="quickadd_categories">
            <td colspan="2">
            	<div id="quickadd_catsList">
                            <span class="quickadd_key"><label for="cid" class="key"><?php echo JText::_( 'MOD_QIP_CATEGORY' ); ?></label></span>
                            <span><?php echo $lists['default']; ?></span>
                </div>
               </td>
        </tr>
        <tr>
            <td colspan="2">
            	<div id="quickadd_pubFeatList">
                        <span class="quickadd_published">
                            <span class="quickadd_key"><label for="published" class="key"><?php echo JText::_( 'MOD_QIP_PUBLISHED' ); ?></label></span>
                            <span class="quickadd_input"><?php echo $lists['state']; ?></span>
                        </span>
                        <span class="quickadd_featured">
                            <span class="quickadd_key"><label for="featured" class="key"><?php echo JText::_( 'MOD_QIP_FEATURED' ); ?></label></span>
                            <span class="quickadd_input"><?php echo $lists['featured']; ?></span>
                        </span>
                </div>
             </td>
        </tr>
        <tr>
            <td colspan="2"><div id="quickadd_textarea"><?php echo $lists['editor']; ?></div></td>
        </tr>
        <tr class="quickadd_buttons">
            <td colspan="2"><input type="button" name="save" id="quickadd_save" value="<?php echo JText::_('MOD_QIP_SAVE'); ?>" class="button" />
<!--                <input type="submit" name="apply" id="quickadd_apply" value="<?php //echo JText::_('MOD_QIP_SAVE_EDIT');?>" class="button" />-->
                <input type="button" name="reset" id="quickadd_reset" value="<?php echo JText::_('MOD_QIP_RESET');?>" class="button" /></td>
        </tr>
    </table>
    <input type="hidden" name="controller" id="quickadd_controller" value="items" />
    <input type="hidden" name="view" id="quickadd_view" value="item" />
    <input type="hidden" name="id" value="" />
    <input type="hidden" name="cid[]" value="" />
    <input type="hidden" name="version" value="" />
    <input type="hidden" name="mask" value="0" />
    <input type="hidden" name="option" id="quickadd_option" value="com_content" />
    <input type="hidden" name="task" id="quickadd_task" value="save" />
    <?php echo JHTML::_( 'form.token' ); ?>
</form>

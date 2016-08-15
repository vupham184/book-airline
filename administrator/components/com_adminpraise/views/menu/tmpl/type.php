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

// Precompute the menu CID

$cid = '&amp;menutype=' . ($this->item->menutype)
		. '&amp;cid[]=' . $this->item->id;
?>
<form action="index.php" method="post" name="adminForm">
	<table class="admintable" width="100%">
		<tr valign="top">
			<td width="60%">
				<!-- Menu Item Type Section -->
				<fieldset>
					<legend><?php echo JText::_( 'COM_ADMINPRAISE_SELECT_MENU_ITEM_TYPE' ); ?></legend>
					<ul id="menu-item" class="jtree">
						<li id="internal-node"><div class="node-open"><span></span><a href="#"><?php echo JText::_('COM_ADMINPRAISE_PREDEFINED_LINKS'); ?></a></div>
							<ul>
								<?php 
								$n = count($this->links);
								$i = 0;
								foreach($this->links as $key => $value) : ?>	
								<li <?php echo ($i == $n-1)? 'class="last"' : '' ?>>
									<div class="node">
										<span></span>
										<a href="index.php?option=com_adminpraise&amp;view=menu&amp;task=edit&amp;type=dynamic&amp;dynamicType=<?php echo strtolower($value['name']); ?><?php echo $cid; ?>">
											<?php echo JText::_($value['name']); ?>
										</a>
									</div>
								</li>
								<?php $i++; endforeach; ?>
							</ul>
						</li>
						<li id="external-node"><div class="base"><span></span><a href="index.php?option=com_adminpraise&amp;view=menu&amp;task=edit&amp;type=url<?php echo $cid; ?>"><?php echo JText::_('COM_ADMINPRAISE_LINK'); ?></a></div></li>
						<li id="separator-node"><div class="base"><span></span><a href="index.php?option=com_adminpraise&amp;view=menu&amp;task=edit&amp;type=separator<?php echo $cid; ?>"><?php echo JText::_('COM_ADMINPRAISE_SEPARATOR'); ?></a></div></li>
					</ul>
				</fieldset>
			</td>
			<td width="40%">
			</td>
		</tr>
	</table>
	<input type="hidden" name="option" value="com_adminpraise" />
	<input type="hidden" name="task" value="" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
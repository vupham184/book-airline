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
defined('_JEXEC') or die('Restricted access'); ?>
<form name="adminForm" class="adminFrom" method="post" action="index.php">
<table class="adminlist">
	<thead>
		<tr>
			<th width="20">
				<?php echo JText::_( 'COM_ADMINPRAISE_NUM' ); ?>
			</th>
			<th width="20">
				<!-- TODO: Check all hardcoded -->
				<input type="checkbox" name="toggle" value="" onclick="checkAll(50);" />
			</th>
			<th width="95%" align="left">
				<?php echo JText::_( 'FILENAME' ); ?>
			</th>
		</tr>
	</thead>
	<tbody>
<?php
	$i = 0;

	// exclude dirs
	$exclude = array();
	$exclude[] = ".";
	$exclude[] = "..";
	$exclude[] = ".svn";
	$exclude[] = "index.html";

	// dirname
	$dir = JPATH_ROOT.DS.'logs';

	// open directory
	if ($handle = opendir($dir)) {

		$filecount = count(glob("$dir/*.php"));
	
		if ($filecount > 0) {

			// list files
			while (false !== ($file = readdir($handle))) {

				if (!in_array($file, $exclude) ) {

					$url = "index.php?option=com_adminpraise&view=logs&layout=detail&file=".$file;
?>
					<tr>
						<td>
							<?php echo $i + 1;?>
						</td>
						<td>
							<input type="checkbox" name="cid[]" value="<?php echo $file; ?>" onclick="isChecked(this.checked);" />
						</td>
						<td>
							&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo $url; ?>"><?php echo $file; ?></a>
						</td>
					</tr>
<?php
					$i++;

				}
			}
		}else{
?>
			<tr>
				<td align="center" colspan="6">
					<b><?php echo JText::_( 'COM_ADMINPRAISE_FILE_NOT_FOUND' ); ?></b>
				</td>
			</tr>
<?php
		}
	}
?>
	</tbody>
	<input type="hidden" name="option" value="com_adminpraise" />
	<input type="hidden" name="controller" value="logs" />
	<input type="hidden" name="view" value="logs" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
</table>

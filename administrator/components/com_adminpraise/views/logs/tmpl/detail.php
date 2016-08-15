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

$f = JRequest::getVar('file');
?>
<table class="adminlist">
	<thead>
		<tr>
			<td>
				<h3><?php echo JText::_( 'FILE' ); ?>: <?php echo $f; ?></h3>
			</td>
		</tr>
	</thead>
</table>
<table class="adminlist">
	<thead>
		<tr>
			<th width="15%">
				<?php echo JText::_( 'COM_ADMINPRAISE_DATE' ); ?>
			</th>
			<th width="15%">
				<?php echo JText::_( 'COM_ADMINPRAISE_TIME' ); ?>
			</th>
			<th width="5%" nowrap="nowrap">
				<?php echo JText::_( 'COM_ADMINPRAISE_LEVEL' ); ?>
			</th>
			<th width="15%" nowrap="nowrap">
				<?php echo JText::_( 'COM_ADMINPRAISE_IP' ); ?>
			</th>
			<th width="15%">
				<?php echo JText::_( 'COM_ADMINPRAISE_STATUS' ); ?>
			</th>
			<th width="30%" align="left">
				<?php echo JText::_( 'COM_ADMINPRAISE_COMMENT' ); ?>
			</th>
		</tr>
	</thead>
	<tbody>
<?php

	$f = JRequest::getVar('file');

	$file = JPATH_ROOT.DS.'logs'.DS.$f;

	if (JFile::exists($file)) {
		$rows = file($file);

		foreach ($rows as $line_num => $line) {

			if ($line[0] != "#") {
				$row = explode("\t", $line);
?>
			<tr class="<?php echo "row$k"; ?>">
				<td align="center">
					<?php echo $line[0]; ?>
				</td>
				<td align="center">
					<?php echo $row[1]; ?>
				</td>
				<td align="center">
					<?php echo $row[2]; ?>
				</td>
				<td align="center">
					<?php echo $row[3]; ?>
				</td>
				<td align="center">
					<?php echo $row[4]; ?>
				</td>
				<td>
					<?php echo $row[5]; ?>
				</td>
			</tr>
<?php
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
?>
	</tbody>
</table>


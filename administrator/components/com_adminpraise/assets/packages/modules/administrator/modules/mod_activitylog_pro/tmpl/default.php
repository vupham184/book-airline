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
$document = JFactory::getDocument();
JHTML::_('behavior.framework');
JHTML::_('script', 'activitylog.js', 'media/mod_activitylog_pro/js/');

$activityLogConf = 'activitylogPro = {baseRoute: "' . JURI::base() . '" }';
$document->addScriptDeclaration($activityLogConf);

?>

<?php if ($params->get( 'show_filter' )) : ?>
	<form action='index.php' method='post' name='ualog_form' id='ualog_form'>
		<select name='ualog_filter_id' id="ualog_filter_id" >
			<option value='0'><?php echo JText::_('MOD_ACTIVITYLOG_FILTER_BY_USER') ?></option>
			<?php foreach ($users AS $f) : ?>
				<option value="<?php echo $f->id ?>">
					<?php echo $f->name; ?> (<?php echo $f->username ?>)
				</option>
			<?php endforeach; ?>
		</select>
		<select name='ualog_filter_option' id="ualog_filter_option" >
			<option value='0'><?php echo JText::_('MOD_ACTIVITYLOG_FILTER_BY_COMPONENT') ?></option>
			<?php foreach ($options AS $f) : ?>
				<option value='<?php echo $f ?>'>
					<?php echo $f ?>
				</option>
			<?php endforeach; ?>
		</select>
		
		<input type="hidden" name="option" value="com_adminpraise" />
		<input type="hidden" name="view" value="activity" />
		<input type="hidden" name="format" value="raw" />
		<input type="hidden" name="conf_name" value="<?php echo $params->get('conf_name'); ?>" />
		<input type="hidden" name="limit" value="<?php echo $params->get('limit'); ?>" />
		<input type="hidden" name="show_date" value="<?php echo $params->get( 'show_date' ); ?>" />
		<input type="hidden" name="date_format" value="<?php echo $params->get( 'dateformat' )?>" />
	</form>
	<hr/>
<?php endif; ?>
	
<div id="activitylog-data">
	<?php if($activities) : ?>
		<ul class="bullet">
			<?php foreach($activities as $key => $value) : ?>
				<li class="
						<?php echo ($value->option) ? $value->option : ''; ?>
						<?php echo ($value->type) ? $value->type : ''; ?>
						">
					<?php echo $value->action_title; ?>
					<?php if($params->get( 'show_date' )) : ?>
						<br/>
						<small class="crdate">
							<?php echo $value->crdate; ?>
						</small>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>
</div>
	

<strong>
	<a id="activitylog-reset" href='javascript:void()'>
		[ <?php echo JText::_("MOD_ACTIVITYLOG_CLEAR_LOG"); ?> ]
	</a>
</strong>
<br />
<br />
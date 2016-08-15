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

// Do not allow direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );


class plgEditorSCE extends JPlugin
{
	
	function plgEditorSCE(& $subject, $config)
	{
		parent::__construct($subject, $config);
	}

	
	function onInit()
	{
		
		$document = &JFactory::getDocument();
		$document->addScript(JURI::root().'plugins/editors/sce/sce/sce.js');
		$document->addStylesheet(JURI::root().'plugins/editors/sce/sce/sce.css');
		return null;
	}

	
	function onGetContent( $editor ) {
		return "document.getElementById('".$editor."').value;";
	}

	
	function onSetContent( $editor, $html ) {
		
	}

	
	function onSave( $editor ) {
	
		$output = "";
		
		return $output;
	}

	
	function onDisplay( $name, $content, $width, $height, $col, $row, $buttons = true)
	{
		$siteURL = JURI::root();
		$document = &JFactory::getDocument();
		$js = "
			window.addEvent('domready', function() {
				edToolbar('".$name."', '".$siteURL."');
				
			});
		";
		$document->addScriptDeclaration($js);
		
		// Only add "px" to width and height if they are not given as a percentage
		if (is_numeric( $width )) {
			$width .= 'px';
		}
		if (is_numeric( $height )) {
			$height .= 'px';
		}

		$editor  = "<div id=\"".$name."_toolbar\"></div><textarea id=\"$name\" name=\"$name\" cols=\"$col\" rows=\"$row\" style=\"width:{$width}; height:{$height};\" class=\"ed\">$content</textarea>\n";

		return $editor;
	}

	function onGetInsertMethod($name)
	{
		
	}

	

	
}

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

defined('_JEXEC') or die;

$lnEnd	= $this->_getLineEnd();
$tab	= $this->_getTab();
$tagEnd	= ' />';
$buffer	= '';

// Generate base tag (need to happen first)
$base = $this->getBase();
if (!empty($base)) {
	$buffer .= $tab.'<base href="'.$this->getBase().'" />'.$lnEnd;
}

// Generate META tags (needs to happen as early as possible in the head)
foreach ($this->_metaTags as $type => $tag)
{
	foreach ($tag as $name => $content)
	{
		if ($type == 'http-equiv') {
			$buffer .= $tab.'<meta http-equiv="'.$name.'" content="'.$content.'"'.$tagEnd.$lnEnd;
		}
		else if ($type == 'standard') {
			$buffer .= $tab.'<meta name="'.$name.'" content="'.$content.'"'.$tagEnd.$lnEnd;
		}
	}
}

$buffer .= $tab.'<meta name="description" content="'.$this->getDescription().'" />'.$lnEnd;
$buffer .= $tab.'<meta name="generator" content="'.$this->getGenerator().'" />'.$lnEnd;
$buffer .= $tab.'<title>'.htmlspecialchars($this->getTitle()).'</title>'.$lnEnd;

// Generate link declarations
foreach ($this->_links as $link) {
	$buffer .= $tab.$link.$tagEnd.$lnEnd;
}

// Generate stylesheet links
foreach ($this->_styleSheets as $strSrc => $strAttr)
{
	$buffer .= $tab . '<link rel="stylesheet" href="'.$strSrc.'" type="'.$strAttr['mime'].'"';
	if (!is_null($strAttr['media'])){
		$buffer .= ' media="'.$strAttr['media'].'" ';
	}
	if ($temp = JArrayHelper::toString($strAttr['attribs'])) {
		$buffer .= ' '.$temp;;
	}
	$buffer .= $tagEnd.$lnEnd;
}

// Generate stylesheet declarations
foreach ($this->_style as $type => $content)
{
	$buffer .= $tab.'<style type="'.$type.'">'.$lnEnd;

	// This is for full XHTML support.
	if ($this->_mime == 'text/html') {
		$buffer .= $tab.$tab.'<!--'.$lnEnd;
	} else {
		$buffer .= $tab.$tab.'<![CDATA['.$lnEnd;
	}

	$buffer .= $content . $lnEnd;

	// See above note
	if ($this->_mime == 'text/html') {
		$buffer .= $tab.$tab.'-->'.$lnEnd;
	} else {
		$buffer .= $tab.$tab.']]>'.$lnEnd;
	}
	$buffer .= $tab.'</style>'.$lnEnd;
}

// Generate script file links
$scriptbuffer = "\n";
$moo = false;

foreach ($this->_scripts as $strSrc => $strType) {
	
	$scriptbuffer .= '	<script type="'.$strType.'" src="'.$strSrc.'"></script>'.$lnEnd;
}

// Generate script declarations
foreach ($this->_script as $type => $content)
{
	$scriptbuffer .= '	<script type="'.$type.'">'.$lnEnd;

	// This is for full XHTML support.
	if ($this->_mime != 'text/html') {
		$scriptbuffer .= '		<![CDATA['.$lnEnd;
	}

	$scriptbuffer .= $content.$lnEnd;

	// See above note
	if ($this->_mime != 'text/html') {
		$scriptbuffer .= '		// ]]>'.$lnEnd;
	}
	$scriptbuffer .= '		</script>'.$lnEnd;
}	

foreach($this->_custom as $custom) {
	$buffer .= $tab.$custom.$lnEnd;
}

//Check if its safe to load scripts at the bottom or not
$safe = ( in_array( JRequest::getCmd('option', 'com_login'), explode( ',', str_replace(' ', '', $this->params->get('safeComponents')) ) )&&$this->params->get('jsAtBottom', 0) ) ? true : false ;
if(!$safe)
{
	$buffer .= $scriptbuffer;
	$scriptbuffer = null;
}
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

// This fix is for Windows/IIS (http://www.joomlapraise.com/forum/index.php?f=56&t=317&rb_v=viewtopic#p1278)
if(!isset($_SERVER['REQUEST_URI']))
{
	if(isset($_SERVER['SCRIPT_NAME']))
		$_SERVER['REQUEST_URI'] = $_SERVER['SCRIPT_NAME'];
	else
		$_SERVER['REQUEST_URI'] = $_SERVER['PHP_SELF'];

	if($_SERVER['QUERY_STRING'])
		$_SERVER['REQUEST_URI'] .=  '?'.$_SERVER['QUERY_STRING'];
}

$pageName = $mainframe->get('JComponentTitle');
if ($pageName == "")
{
	$pageName = JRequest::getVar('option');
}
else if(JRequest::getVar('ap_task') != null)
{
	$pageName = "Components";
}

// Take out any special characters and clean up page name
$pageName = strip_tags($pageName);
$pageName = str_replace("!", "", $pageName);
$pageName = str_replace("|", ":", $pageName);
$pageName = trim($pageName);

// Add span
$pageName = "<span>".$pageName."</span>";

$trailLength = 8;
$staleCrumbList = explode('|', @$_COOKIE['breadcrumbs']);

$newPage = $_SERVER['REQUEST_URI'].'!'.$pageName;

if(count($staleCrumbList) == 0 || $newPage != $staleCrumbList[count($staleCrumbList) - 1])
{
	$crumbList = array();
	for($i = 0; $i < count($staleCrumbList); $i++)
	{
		if($staleCrumbList[$i] && $staleCrumbList[$i] != $newPage)
		{
			$crumbList[count($crumbList)] = $staleCrumbList[$i];
		}
	}

	$crumbList[count($crumbList)] = $newPage;

	// Knock them off the front if we're too long
	while(count($crumbList) > $trailLength)
	{
		array_shift($crumbList);
	}

	setcookie('breadcrumbs', join('|', $crumbList), time()+60*60*24*1,'/');
	$_COOKIE['breadcrumbs'] = join('|', $crumbList);
}

function breadcrumbs()
{
	$crumbList = explode('|', @$_COOKIE['breadcrumbs']);
	$returnString = '';
	print "<ul class=\"breadcrumbs\">";
	print "<li>" . JText::_( 'HISTORY' ) . "</li>";
	for($i = 0; $i < count($crumbList) - 1; $i++)
	{
		$crumb=explode('!', $crumbList[$i]);
//		$returnString .= "<span id='bc$i' class='crumb'><a href='$crumb[0]'>$crumb[1]</a></span> /";
		$returnString .= "<li><a href='$crumb[0]'>$crumb[1]</a></li>";
	}
	$crumb = explode('!', $crumbList[count($crumbList) - 1]);
	echo $returnString.$crumb[1];
	print "</ul>";
}

?>

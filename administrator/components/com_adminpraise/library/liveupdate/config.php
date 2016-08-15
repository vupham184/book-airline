<?php
/**
 * @package LiveUpdate
 * @copyright Copyright ©2011 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license GNU LGPLv3 or later <http://www.gnu.org/copyleft/lesser.html>
 */

defined('_JEXEC') or die();

/**
 * Configuration class for your extension's updates. Override to your liking.
 */
class LiveUpdateConfig extends LiveUpdateAbstractConfig
{
	var $_extensionName			= 'com_adminpraise';
	var $_extensionTitle		= 'AdminPraise';
	var $_xmlFilename = 'manifest.xml';
	var $_updateURL				= 'http://live.adminpraise.com/com_adminpraise-j17.ini';
	var $_requiresAuthorization	= false;
	var $_versionStrategy		= 'vcompare';
}

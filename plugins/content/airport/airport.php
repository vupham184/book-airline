<?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

class plgContentAirport extends JPlugin
{
	
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
	}
	
	public function onContentPrepare($context, &$article, &$params, $limitstart)
	{
		
		$sfsParams = JComponentHelper::getParams('com_sfs');
		
		$airport = $sfsParams->get('sfs_system_airport');
		
		if( ! $airport ) return;
		
		// Don't run this plugin when the content is being indexed
		if ($context == 'com_finder.indexer') {
			return true;
		}

		// simple performance check to determine whether bot should process further
		if (strpos($article->text, 'loadairport') === false ) {
			return true;
		}
		
		$regex		= '/{loadairport}/i';
		
		preg_match_all($regex, $article->text, $matches, PREG_SET_ORDER);
		
		if ($matches) {
			foreach ($matches as $match) {
				$article->text = preg_replace("|$match[0]|", addcslashes($airport, '\\$'), $article->text, 1);
			}
		}
		
	}
	
}

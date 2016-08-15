<?php

defined('_JEXEC') or die;
$id  = JRequest::get('GET');		

$db   = JFactory::getDbo();

$query = "SELECT * FROM #__sfs_iatacodes WHERE id = '".$id['id']."'";
$db->setQuery($query);
$row= $db->loadObject();
echo $row->airport_name. 'Heheheh';

?>
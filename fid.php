<?php
/*
echo $_SERVER['DOCUMENT_ROOT'];

//$dir = '../../public_html_11-12-2015-071233/';
$dir = '../';
//$dir = '/home/sfs/domains/sfs-web.org/public_html/joomla3/public_html';
//$dir2 = '/home/sfs/domains/sfs-web.org/public_html/';
$dirx = scandir($dir);
//rename('../../public_html_11-12-2015-071233/','../../');
echo '<pre>';
print_r($dirx);

die;
*/
//rmdir('public_html');die;
$dir = '/home/sfs/domains/sfs-web.org/public_html_12-12-2015-071208';
$dir2 = '/home/sfs/domains/sfs-web.org/public_html/';

function recurse_copy($src,$dst) { 
	$dir = opendir($src); 
	@mkdir($dst); 
	while(false !== ( $file = readdir($dir)) ) { 
		if (( $file != '.' ) && ( $file != '..' )) { 
			if ( is_dir($src . '/' . $file) ) { 
				recurse_copy($src . '/' . $file,$dst . '/' . $file); 
			} 
			else { 
				copy($src . '/' . $file,$dst . '/' . $file); 
			} 
		} 
	} 
	closedir($dir); 
} 

recurse_copy( $dir, $dir2);

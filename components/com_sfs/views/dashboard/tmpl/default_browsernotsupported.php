<?php
/**
 * @package		Joomla.Site
 * @subpackage	com_content
 * @copyright	Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

?>
<style>
.text-center{
	text-align:center;
}
.sfs-main-wrapper{
	border:6px solid #99ccff;
	border-radius:4px;
}
.browser-not-supported h2{
	font-size:24px;	
}
.col-md-4{
	width:25.66666666666%;
	float:left;
}
.row{
	margin:auto;
	width:60%;
	overflow:hidden;
}
table tr td{
	padding:5px 15px;
}
</style>
<div class="heading-block descript clearfix">
	<div class="heading-block-wrap">
		<h3><?php echo $bus->name?>: Dashboard</h3>
	</div>
</div>
<div id="browsernotsupported" class="main sfs-main-wrapper " style="padding: 15px 20px; margin-top: 20px">
    <div class="sfs-orange-wrapper">
        <div class="sfs-white-wrapper">
            <div class="browser-not-supported">
            	<h2 class="text-center">Browser not supported</h2>
                <p class="text-center">
                You're using a web browser we don't support <br />
                Try one of these options to make sure all <br/> 
                functions will work like intended on SFS
                </p>
                <div class="row">
                	<table align="center">
                    	<tr>
                        	<td align="center">
                            	<img src="media/media/images/Chrome_logo.png" width="68" alt="Google Chrome" />
                                <br>
                                <strong>Google Chrome</strong>
                            </td>
                            <td align="center">
                            	<img src="media/media/images/FF_logo.png" width="68" alt="Mozilla Firefox" />
                                <br>
                                <strong>Mozilla Firefox</strong>
                            </td>
                            <td align="center">
                            	<img src="media/media/images/Safari_logo.png" width="68" alt="Safari" />
                                <br>
                                <strong>Safari</strong>
                            </td>
                        </tr>
                        <tr>
                        	<td colspan="3" align="center">
                            	<button class="btn small-button" onclick="window.parent.SqueezeBox.close();" style="cursor:pointer;">OK</button>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

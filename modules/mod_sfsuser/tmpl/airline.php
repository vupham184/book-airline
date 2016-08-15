<?php
defined('_JEXEC') or die;
$session = JFactory::getSession();
$airline_current = SAirline::getInstance()->getCurrentAirport();
$date = SfsHelperDate::getDate('now', "d F Y", $airline_current->time_zone);
$time = SfsHelperDate::time('now', $airline_current->time_zone);
?>
<div class="wellcomepart" style="float:right;overflow:hidden;">
<div class="namecus">Welcome back:<span class="infoname"> <?php echo $user->name;?></span></div>
<div class="namecountry"><?php echo $airline->name;?> <?php echo $airline->country_name ? '('.$airline->country_name.')':'';?></div>
<div class="datetime"><span class="date">Date: <?php echo $date;?></span> <span class="time">Time: <?php echo $time;?></span></div>
</div>
<?php
defined('_JEXEC') or die;
$distance = JRequest::getInt('distance');

$airline = SFactory::getAirline();
$airlineName = '';
if($airline->grouptype == 3) {
    $selectedAirline = $airline->getSelectedAirline();
    $airlineName = 	$selectedAirline->name;
}

$airline_current = SAirline::getInstance()->getCurrentAirport($type);
$airport_current_id = $airline_current->id;
$airport_current_code = $airline_current->code;
$totalAllHotels = 0;
foreach ($this->hotels AS $key => $hotel)
{
    $totalAllHotels += count($hotel);
}

?>

<div class="heading-block descript clearfix">
    <div class="heading-block-wrap">
        <h3><?php if($airlineName) echo $airlineName.': ';?><?php echo JText::_('COM_SFS_MARKET_OVERVIEW');?></h3>
        <p class="descript-txt"><?php echo JText::_('COM_SFS_MARKET_OVERVIEW_DESC');?></p>
    </div>
</div>
<style>
    .rooms .infocol {
        background-color: #FFFFFF;
        height: auto;
        width: 100%;
        padding: 20px 0 20px 0;
        text-align: center;
        margin-bottom: 0;
    }
    .rooms .starrate {
        margin: 0 20px 0 0;
    }
    .rooms .infocol_row {
        margin-left: 250px;
        padding: 10px 0 10px 0;
    }
</style>
<div id="sfs-wrapper" class="main fs-14">

    <div class="market">

        <div class="sfs-orange-wrapper">
        <div class="sfs-white-wrapper" style="padding-bottom:0; padding-right:0">
        
            <form action="<?php echo JRoute::_('index.php?option=com_sfs&view=report&layout=market') ;?>" method="post">
                <div class="register-field-short clear floatbox">
                    <label style="width:140px;">Date:</label>
                    <?php SfsHelperField::getCalendar('market_calendar',JRequest::getVar('market_calendar'),'calendar required');?>
                </div>
                <div class="register-field-short clear floatbox">
                    <label style="width:140px;">Distance to airport:</label>
                    <select name="distance" style="width:150px;">
                        <option value="0">All</option>
                        <option value="1"<?php echo $distance==1 ? ' selected="selected"':'';?>>0-5km</option>
                        <option value="2"<?php echo $distance==2 ? ' selected="selected"':'';?>>0-10km</option>
                        <option value="3"<?php echo $distance==3 ? ' selected="selected"':'';?>>0-20km</option>
                        <option value="4"<?php echo $distance==4 ? ' selected="selected"':'';?>>0-40km</option>
                        <option value="5"<?php echo $distance==5 ? ' selected="selected"':'';?>>&gt;40km</option>
                    </select>
                </div>
                
                <input type="submit" id="btngenerate" value="Generate" class="small-button float-right" style="margin-bottom:10px; margin-right:20px;" />
                
                <input type="hidden" name="option" value="com_sfs" />
                <input type="hidden" name="view" value="report" />
                <input type="hidden" name="layout" value="market" />
                <?php echo JHtml::_('form.token'); ?>
                                       
            </form>
        </div>
        </div>

        <div class="sfs-wrapper rooms<?php echo $this->pageclass_sfx?>">
            <div class="sfs-main-wrapper floatbox" style="margin-top:20px; padding-right:0">
                <div class="infocol">
                    <h4 style="margin-bottom: 25px">Total number of available hotels for <?php echo $airport_current_code?>: <?php echo $totalAllHotels?> Hotels </h4>
                <?php

                foreach ($this->hotels AS $key => $hotel) :
                $totalHotels = 0;
                $totalAverageRate = 0;
                if(count($hotel)) {
                	foreach ($hotel as $value) {
                        $totalHotels ++;
                        $num = 0;
                        $totalRate = 0;
                        if($value->s_room_total != 0)
                        {
                            $num ++;
                            $totalRate += $value->s_room_rate;
                        }
                        if($value->sd_room_total != 0)
                        {
                            $num ++;
                            $totalRate += $value->sd_room_rate;
                        }
                        if($value->t_room_total != 0)
                        {
                            $num ++;
                            $totalRate += $value->t_room_rate;
                        }
                        if($value->q_room_total != 0)
                        {
                            $num ++;
                            $totalRate += $value->q_room_rate;
                        }
                        $averageRate = $totalRate/$num;
                        $totalAverageRate += $averageRate;
                	}

                }
                //var_dump($totalAverageRate);exit;
                if(count($hotel) == 0)
                {
                    $totalAverageRate = 0;
                }
                else
                {
                    $totalAverageRate = $totalAverageRate/count($hotel);
                }

                ?>
                <div class="infocol_row">
                    <div class="starrate userRate<?php echo $key;?>" style="float: left"></div>
                    <div class="inforoom">

                        <div class="infonumber">
                            <div class="title">Number of hotels</div>
                            <div class="number">
                                <?php
                                    echo $totalHotels;
                                ?>
                            </div>
                        </div>
                        <div class="average">
                            <div class="title">Average rate</div>
                            <div class="number">
                                <?php
                                    echo number_format($totalAverageRate,2);
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                </div>
                <?php endforeach;?>
                </div>

                <div class="clear"></div>
            </div>
        
        </div>
	</div>
</div>

<?php
defined('_JEXEC') or die;

JHTML::_('behavior.tooltip');

$airline = SFactory::getAirline();
$airlineName = '';
if($airline->grouptype == 3) {
	$selectedAirline = $airline->getSelectedAirline();
	$airlineName = 	$selectedAirline->name;
}

?>
<div class="heading-block descript clearfix">
    <div class="heading-block-wrap">
        <h3><?php if($airlineName) echo $airlineName.': ';?><?php echo JText::_('COM_SFS_AIRLINE_MY_OVERVIEW');?></h3>
        <div class="descript-txt">
            <?php if($this->prevNight):?>
                <a href="<?php echo $this->prevNightUrl?>" class="match-prev-night" data-step="1" data-intro="<?php echo SfsHelper::getTooltipTextEsc('overview_prev_night', 'Previous night', 'airline');?>">
                    &lt;&lt; <?php echo  JText::_('Previous night');?>
                </a>
            <?php else:?>
                <span class="match-prev-night">&lt;&lt; <?php echo JText::_('Previous night', 'airline');?></span>
            <?php endif;?>
            <a href="<?php echo $this->nextNightUrl?>" class="match-next-night" data-step="2" data-intro="<?php echo SfsHelper::getTooltipTextEsc('overview_next_night', 'Next night &gt;&gt;', 'airline');?>"><?php echo JText::_('Next night &gt;&gt;', 'airline');?></a>
            <div class="sfs-match-title-desc" data-step="3" data-intro="<?php echo SfsHelper::getTooltipTextEsc('overview_night_range', $text, 'airline');?>">
                <?php
                $text = 'For the night starting: '.JFactory::getDate($this->night)->format('d M Y').' ending: '.JFactory::getDate($this->nextNight)->format('d M Y');
                echo JText::_($text, 'airline');
                ?>
            </div>
        </div>
    </div>
</div>

<div id="sfs-wrapper" class="main">
    <div class="box-style yellow radius">
        <div class="airline-overview airline-overview4 clearfix">
            <form action="<?php echo JRoute::_('index.php?option=com_sfs&view=handler');?>" name="deleteSeats" method="post">

                <div class="sfs-main-wrapper" style="padding:1px;">
                    <div class="sfs-orange-wrapper">

                        <div class="floatbox sfs-white-wrapper">

                            <div class="contrast-block-wrapper" style="width:200px; float:left" data-step="4" data-intro="<?php echo SfsHelper::getTooltipTextEsc('select_passengers_overview', $text, 'airline');?>">
                                <h4><?php echo JText::_('Passengers');?></h4>
                                <div class="contrast-block">

                                    <div class="contrast-body">
                                        <div class="overview-seats-block">
                                            <div class="pd">
                                                <?php
                                                $seat_count = 0;
                                                if( count($this->flights_seats) ) :
                                                    ?>
                                                    <?php
                                                    foreach ( $this->flights_seats as $item ) :
                                                        $item->seats = (int) $item->seats - (int)$item->seats_issued ;
                                                        $seat_count += $item->seats;
                                                        for( $i = 0; $i < $item->seats ; $i++  ) :
                                                            ?>
                                                            <span><input class="check-seats" type="checkbox" name="flightids[]" value="<?php echo $item->id;?>" /> <?php echo $item->flight_code.' '.$item->flight_class;?></span>
                                                        <?php endfor;?>
                                                    <?php endforeach; ?>
                                                <?php endif;?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="contrast-foot">
                                        <?php echo $seat_count ?> <?php echo JString::strtolower(JText::_('COM_SFS_PASSENGERS'));?>
                                    </div>

                                    <input type="hidden" name="option" value="com_sfs" />
                                    <input type="hidden" name="task" value="handler.deleteSeats" />
                                    <input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid');?>" />
                                    <?php echo JHtml::_('form.token'); ?>

                                </div>
                            </div>

                            <div class="contrast-block-wrapper" style="width:690px; float:right" data-step="5" data-intro="<?php echo SfsHelper::getTooltipTextEsc('hotel_overview', $text, 'airline');?>">
                                <h4><?php echo JText::_('COM_SFS_AIRLINE_HOTELS');?><?php echo JText::_($text, 'airline'); ?></h4>                                
                                <div class="contrast-block table" >                                    
                                    <table>
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>S</th>
                                                <th>S/D</th>
                                                <th>T</th>
                                                <th>Q</th>                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $sd_room_total = 0;
                                                $t_room_total = 0;
                                                $s_room_total = 0;
                                                $q_room_total = 0;

                                                if( count($this->hotels) ) :
                                            ?>
                                                
                                                <?php foreach ( $this->hotels as $item ) :
                                                    $sd_room_total += ($item->sd_room - $item->sd_room_issued);
                                                    $t_room_total  += ($item->t_room - $item->t_room_issued);
                                                    $s_room_total += ($item->s_room - $item->s_room_issued);
                                                    $q_room_total  += ($item->q_room - $item->q_room_issued);
                                                ?>
                                                <tr>                                                                                                        
                                                    <td><span class="hasTip" title="Date <?php echo $item->room_date;?>"><?php echo $item->name;?></span></td>
                                                    <td><?php echo $item->s_room - $item->s_room_issued ;?></td>
                                                    <td><?php echo $item->sd_room - $item->sd_room_issued ;?></td>
                                                    <td><?php echo $item->t_room - $item->t_room_issued ;?></td>
                                                    <td><?php echo $item->q_room - $item->q_room_issued ;?></td>                                                            
                                                </tr>
                                                <?php endforeach; ?>                                                
                                            <?php endif;?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td></td>
                                                <td><?php echo $s_room_total.' S';?></td>
                                                <td><?php echo $sd_room_total.' S/D';?></td>
                                                <td><?php echo $t_room_total.' T';?></td>
                                                <td><?php echo $q_room_total.' Q';?></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                        

                                </div>
                            </div>
                            <div class="clear"></div>
                            <button type="submit" class="has-indent btn orange sm" data-step="6" data-intro="<?php echo SfsHelper::getTooltipTextEsc('overview_delete_flights', $text, 'airline');?>"><?php echo JText::_('COM_SFS_DELETE_PASSENGERS_FLIGHTS') ?></button>                                
                        </div>

                    </div>
                </div>



            </form>
        </div>

    </div>

    <div class="main-bottom-block">
        <div class="pull-left">
            <a href="<?php echo JRoute::_('index.php?option=com_sfs&view=dashboard&Itemid='.JRequest::getInt('Itemid'))?>" class="btn orange sm">Back</a>
        </div>

        <div class="pull-right">            
            <?php if( SFSAccess::check($this->user, 'a.admin') ) : ?>
                <a href="<?php echo JRoute::_('index.php?option=com_sfs&view=handler&layout=search&Itemid='.JRequest::getInt('Itemid'));?>" class="btn sm orange" data-step="7" data-intro="<?php echo SfsHelper::getTooltipTextEsc('overview_add_hotels', JText::_('COM_SFS_AIRLINE_ADD_HOTELS'), 'airline');?>">
                    <?php echo JText::_('COM_SFS_AIRLINE_ADD_HOTELS') ?>
                </a>
            <?php endif;?>
                <a href="<?php echo JRoute::_('index.php?option=com_sfs&view=handler&layout=flightform&Itemid='.JRequest::getInt('Itemid'))?>" class="btn orange sm" data-step="8" data-intro="<?php echo SfsHelper::getTooltipTextEsc('button_add_passenger', $text, 'airline');?>">
                    <?php echo JText::_('COM_SFS_AIRLINE_ADD_PASSENGERS_FLIGHTS');?>
                </a>            
        </div>
                
    </div>
</div>
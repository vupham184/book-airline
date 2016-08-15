<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
$session= JFactory::getSession();
?>
<p class="note">
<span class="icon"></span>Thank you for viewing this the report page. Im still working for this page, it's not fully done yet at this time. Please see it at other time. Thank you !!!
</p>
    
    <div class="heading-block descript clearfix">
        <div class="heading-block-wrap">
            <h3>Make your report</h3>
            <div class="descript-txt"><?php echo JText::_('COM_SFS_ROOMLOADING_SHORT_DESC')?></div>
        </div>
    </div>
    
    <div class="main">
        <form method="post" action="index.php?option=com_sfs&view=report">            
            
            <h4>Make your selection</h4>
            <p class="selectime"><span class="titletime">From</span> <?php $this->listMonths() ; $this->listYears() ?></p>
            <div class="clear"></div>
            <p class="selectime"><span class="titletime">Until</span> <?php $this->listMonths() ; $this->listYears() ?></p>
            <div class="clear"></div>
            <input type="submit" value="Generate" class="button" />
            
            
            <div class="clear"></div>
            <h3 style="margin-top: 45px!important;">Your report</h3>
            <div class="sfs-blue-wrapper">
            	<div class="group grouporo">
                    <div class="oro item">
                        <img src="components/com_sfs/views/report/tmpl/numberofroomnight.php?show=<?php echo $session->get('show') ?>&y=<?php echo $session->get('yRoomnights') ?>&x=<?php echo $session->get('xRoomnights') ?>" />
                        <p class="caption">Top hotels</p>
                        <div class="inforeport">
                            <table border="1">
                                <tr class="maintitle">
                                    <td>Month</td>
                                    <td>Roomnights</td>
                                </tr>
                                <tr>
                                    <td>JAN</td>
                                    <td class="t-align">10</td>
                                </tr>
                                <tr>
                                    <td>FEB</td>
                                    <td class="t-align">20</td>
                                </tr>
                                <tr class="even">
                                    <td>MAR</td>
                                    <td>30</td>
                                </tr>
                                <tr>
                                    <td>APR</td>
                                    <td>10</td>
                                </tr>
                                <tr class="even">
                                    <td>MAY</td>
                                    <td>40</td>
                                </tr>
                                <tr class="even">
                                    <td>JUN</td>
                                    <td>25</td>
                                </tr>
                            </table>
                         </div>
                         <input type="button" value="Export" class="button"/>
                    </div>
                    <div class="oro item">
                        <img src="components/com_sfs/views/report/tmpl/roomprice.php?show=<?php echo $session->get('show') ?>&y=<?php echo $session->get('yRoomprice') ?>&x=<?php echo $session->get('xRoomprice') ?>"  />
                        <p class="caption">Top hotels</p>
                        <div class="inforeport">
                            <table border="1">
                                <tr class="maintitle">
                                    <td>Month</td>
                                    <td>Roomnights</td>
                                </tr>
                                <tr>
                                    <td>JAN</td>
                                    <td class="t-align">762 EURO</td>
                                </tr>
                                <tr>
                                    <td>FEB</td>
                                    <td class="t-align">334 EURO</td>
                                </tr>
                                <tr class="even">
                                    <td>MAR</td>
                                    <td>982 EURO</td>
                                </tr>
                                <tr>
                                    <td>APR</td>
                                    <td>678 EURO</td>
                                </tr>
                                <tr class="even">
                                    <td>MAY</td>
                                    <td>782 EURO</td>
                                </tr>
                                <tr class="even">
                                    <td>JUN</td>
                                    <td>345 EURO</td>
                                </tr>
                            </table>
                        </div>
                        <input type="button" value="Export"  class="button" />
                    </div>
                    <div class="oro item">
                        <img src="components/com_sfs/views/report/tmpl/totalroom.php?show=<?php echo $session->get('show') ?>&y=<?php echo $session->get('yTotal') ?>&x=<?php echo $session->get('xTotal') ?>"  />
                        <p class="caption">Top hotels</p>
                        <div class="inforeport">
                            <table border="1">
                                <tr class="maintitle">
                                    <td>Month</td>
                                    <td>Net Revenue</td>
                                </tr>
                                <tr>
                                    <td>JAN</td>
                                    <td class="t-align">300 EURO</td>
                                </tr>
                                <tr>
                                    <td>FEB</td>
                                    <td class="t-align">200 EURO</td>
                                </tr>
                                <tr class="even">
                                    <td>MAR</td>
                                    <td>350 EURO</td>
                                </tr>
                                <tr>
                                    <td>APR</td>
                                    <td>100 EURO</td>
                                </tr>
                                <tr class="even">
                                    <td>MAY</td>
                                    <td>500 EURO</td>
                                </tr>
                                <tr class="even">
                                    <td>JUN</td>
                                    <td>600 EURO</td>
                                </tr>
                            </table>
                         </div>
                        <input type="button" value="Export" class="button" />
                    </div>
                </div>
                <div class="group groupcircle">
                    <div class="circle item">
                        <img src="components/com_sfs/views/report/tmpl/IATAcode.php?n=<?php echo $session->get('nIATAcode') ?>&p=<?php echo $session->get('pIATAcode')  ?>" />
                        <p class="caption">Top IATA codes</p>
                        <div class="inforeport">
                            <table border="1">
                                <tr class="maintitle">
                                    <td>Reason</td>
                                    <td>Code</td>
                                </tr>
                                <tr>
                                    <td>Technical</td>
                                    <td>12</td>
                                </tr>
                                <tr class="even">
                                    <td>Weather</td>
                                    <td>5</td>
                                </tr>
                                <tr>
                                    <td>Commercial</td>
                                    <td>44</td>
                                </tr>
                                <tr class="even">
                                    <td>Striker</td>
                                    <td>24</td>
                                </tr>
                            </table>
                        </div>
                        <input type="button" value="Export" class="button" />
                    </div>
                    <div class="circle item">
                        <img src="components/com_sfs/views/report/tmpl/marketpickup.php?n=<?php echo $session->get('nMarket') ?>&p=<?php echo $session->get('pMarket')  ?>" />
                        <p class="caption">Market pick up</p>
                        <div class="inforeport">
                            <table border="1">
                                <tr class="maintitle">
                                    <td>Pick up</td>
                                    <td>66 %</td>
                                </tr>
                            </table>
                         </div>
                         <div class="sometext">Some text will goes here</div>
                         <input type="button" value="Export" class="button" />
                    </div>
                    <div class="circle item">
                        <img src="components/com_sfs/views/report/tmpl/transpotation.php?n=<?php echo $session->get('nTrans') ?>&p=<?php echo $session->get('pTrans')  ?> "/>
                        <p class="caption">Transtation details</p>
                        <div class="inforeport">
                            <table border="1" style="border:solid 1px">
                                <tr class="maintitle">
                                  <td>Included</td>
                                  <td>66%</td>
                                </tr>
                                <tr class="maintitle">
                                   <td>Exclude</td>
                                   <td>34%</td>
                                 </tr>
                              </table>
                         </div>
                        <div class="sometext">Some text will goes here</div>
                        <input type="button" value="Export" class="button" />
                    </div>
                    <div class="circle item lastitem">
                        <img src="components/com_sfs/views/report/tmpl/blockpickup.php?n=<?php echo $session->get('nBlock') ?>&p=<?php echo $session->get('pBlock')  ?>" />
                        <p class="caption">Inital blocked pick up</p>
                        <div class="inforeport">
                            <table border="1">
                                <tr class="maintitle">
                                    <td>Picked up</td>
                                    <td>97%</td>
                                </tr>
                            </table>
                         </div>
                        <div class="sometext">Some text will goes here</div>
                        <input type="button" value="Export" class="button" />
                    </div>
            	</div>
            <input type="hidden" name="task" value="report.generate" />
        </form>
    </div>

</div>
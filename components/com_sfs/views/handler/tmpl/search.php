<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');

$startDateList = SfsHelperDate::getSearchDate('start', 'class="inputbox fs-13" style="width:155px;"');
$endDateList = SfsHelperDate::getSearchDate('end', 'class="inputbox fs-13" style="width:155px;"');

$airline = SFactory::getAirline();

$airlineName = '';
if ($airline->grouptype == 3) {
    $selectedAirline = $airline->getSelectedAirline();
    $airlineName = $selectedAirline->name;
}

?>
<style>
    .sfs-form.form-vertical .form-group label {
        width: 30px;
        font-weight: bold;
    }

    div.room-choice label {
        font-weight: normal !important;
    }

    div.room-choice div.field-room label {
        display: block !important;
        margin: 0;
        width: 100% !important;
        clear: both;
        text-align: right;
        float: none !important;
        padding-right: 10px;
        margin-right: 0 !important;
    }

    div.room-choice div.field-room span {
        display: inline-block;
        float: none;
    }

    div.room-choice div.field-room{
        display: inline-block;
        float: left;
        margin-right: 10px;
    }

    input {
        width: 40px;;
    }

    ul#rooms_tree {
        overflow: hidden;
        margin-left: 100px;
        padding-top: 0;
    }

    ul#rooms_tree li {
        display: block;
        position: relative;
        margin-left: 0 !important;
        padding-top: 20px;
    }

    ul#rooms_tree li:first-child {
        margin-top: 0;
        padding-top: 0;
    }

    ul#rooms_tree li:before {
        content: "";
        position: absolute;
        width: 1px;
        height: 100%;
        background: #E7E7E7;
        top: -21px;
    }

    ul#rooms_tree li:after {
        content: ".";
        display: block;
        height: 0;
        overflow: hidden;
        clear: both;
    }

    ul#rooms_tree li:first-child span.room {
        margin-top: 40px;
    }

    ul#rooms_tree li span.room {
        float: left;
        display: inline-block;
        margin-top: 10px;
        margin-right: 20px;
        position: relative;
        padding-left: 110px;
    }

    ul#rooms_tree li span.room:after {
        content: "";
        position: absolute;
        left: 0;
        bottom: 10px;
        width: 100px;
        height: 1px;
        background: #E7E7E7;
    }

    select.adults {
        width: 100px;
        margin-left: 30px;
    }

    select.children {
        width: 100px;
    }

    hr {
        margin: 20px 15px 10px 100px;
        border: none;
        height: 1px;
        background: #E7E7E7;
    }
</style>
<script type="text/javascript">
    window.addEvent('domready', function () {
        $('enddate').set('text', $('date_end').getSelected().get('text'));
        $('date_start').addEvent('change', function () {
            $('date_start').selectedIndex;
            $('date_end').selectedIndex = $('date_start').selectedIndex;
            $('enddate').set('text', $('date_end').getSelected().get('text'));
        });
    });
    jQuery.noConflict();
    jQuery(function ($) {
        function loadRoomRows(num_room) {
            removeAllRows();
            for(var i=0; i < num_room; i++)
            {
                addNewRow(i);
            }
            $("#hotelSearchForm ul").after("<hr/>");
        }


        function addNewRow(id) {
            var $li = $("<li></li>"),
                $span = $("<span class='room'>Room"+(id +1)+"</span>"),
                $div1 = $("<div class='room-choice'></div>"),
                $div_adults = $("<div class='field-room'></div>"),
                $div_children = $("<div class='field-room'></div>"),
                $select_adults = $("<select class='smaller-size adults'></select>"),
                $select_children = $("<select class='smaller-size children'></select>");
            $li.attr("id", id);

            if(id == 0)
            {
                var $label_adults = $("<label></label>"),
                    $label_children = $("<label></label>");

                $label_adults.attr("for", "room[0][num_adults]").css("padding-right", "30px").text("<?php echo JText::_('COM_SFS_ADULTS'); ?>");
                $div_adults.append($label_adults);

                $label_children.attr("for", "room["+id+"][num_children]").css("padding-right", "30px").text("<?php echo JText::_('COM_SFS_CHILDREN'); ?>");
                $div_children.append($label_children);

            }

            $select_children.append($("<option>"+0+"</option>").attr("value", 0));
            for(var i=1; i<=4; i++)
            {
                if(i == 1)
                {
                    $select_adults.append($("<option></option>").attr("value", i).attr("selected", "selected").text(i));
                }
                else
                {
                    $select_adults.append($("<option></option>").attr("value", i).text(i));
                }
                $select_children.append($("<option>"+i+"</option>").attr("value", i));

            }

            $div_adults.append($select_adults);
            $div1.append($div_adults);

            $div_children.append($select_children);
            $div1.append($div_children);

            $li.append($span);
            $li.append($div1);
            $("#hotelSearchForm ul").append($li);
        }

        function removeAllRows() {
            for(var i=0; i < 3; i++)
            {
                $("#hotelSearchForm ul li#" + i).remove();
                $("#hotelSearchForm ul").next("hr").remove();
            }
        }

        function addInputChild(id, num_child){
            var $li = $("li#"+id);
            $li.find("div.child").remove();
            for(var i=0; i<num_child; i++) {
                var $input = $("<input type='text' value='0'/>"),
                    $span = $("<span style='margin-right: 10px'></span>"),
                    $div = $("<div class='field-room child'></div>");
                if(id == 0)
                {
                    var $label = $("<label></label>");
                    $label.attr("for", "room[0][children_ages]["+i+"]").text("age");
                    $div.append($label);
                }

                $span.text("<?php echo JText::_('COM_SFS_CHILD'); ?> " + (i+1));
                $div.append($span);

                $input.attr("name", "room[" + id + "][children_ages][" + i + "]").addClass("child-age");
                $div.append($input);
                $li.find("div.room-choice").append($div);
            }
        }


        $(document).ready(function () {

            var num_room;

            $("#rooms").on("change",function () {
                num_room = parseInt($(this).val());
                if(!$.isNumeric(num_room) || num_room < 1 )
                {
                    $(this).val(1);
                    num_room = 1;
                }
                if(num_room >99)
                {
                    $(this).val(99);
                    num_room=99;
                }
                if(num_room <=1)
                    loadRoomRows(num_room);
                else
                    removeAllRows();
            });

            $("body").on("change",".children",function() {
                var id = $(this).closest("li").attr("id");
                var num_child = $(this).val();
                addInputChild(id, num_child);
            });
            $("body").on("click","#btnSearch",function() {
                var $form = $("#hotelSearchForm"),
                    check = true;
                if($form.find("input.child-age").length)
                {
                    var $child_ages = $form.find("input.child-age");
                    $child_ages.each(function(){
                        if($(this).val() == 0)
                        {
                            alert("Please input the age for every children!");
                            check = false;
                            return false;
                        }
                    })
                }

                if(check)
                {
                    $form.submit();
                }
                return false;
            })
        });
    })
</script>
<div class="heading-block clearfix">
    <div class="heading-block-wrap">
        <h3><?php if ($airlineName) echo $airlineName . ': '; ?><?php echo JText::_("COM_SFS_HOTEL_SEARCH"); ?></h3>
    </div>
</div>

<div class="main">
<?php
   /* $type = "article";
    $result = SCodeCanyOn::getCommnetCode($type);
    echo $result;*/
    $pass_issue_hotel='';
    if(JRequest::getString('pass_issue_hotel')){
        $pass_issue_hotel='&pass_issue_hotel='.JRequest::getString('pass_issue_hotel').'&room_book='.JRequest::getString('room_book');
    }    
    $pass_detail_hotel='';
    if(JRequest::getString('pass_detail_hotel')){
        $pass_detail_hotel='&pass_detail_hotel='.JRequest::getString('pass_detail_hotel');
    }    
?>

    <form id="hotelSearchForm" action="<?php echo JRoute::_('index.php?option=com_sfs&view=search'.$pass_issue_hotel.$pass_detail_hotel); ?>" method="post"
          class="form-validate sfs-form form-vertical">

        <div class="form-group" data-step="2"
             data-intro="<?php echo SfsHelper::getTooltipTextEsc('start_need_room_field', $text, 'airline'); ?>">
            <label>
                <?php echo JText::_("COM_SFS_ROOMS_NEED"); ?>:
            </label>
            <input type="text" value="1" size="1" name="rooms" id="rooms" class="required smaller-size"
                   style="width:100px"/> <?php echo JText::_('COM_SFS_ROOMS'); ?>
            <ul id="rooms_tree">
                <li id="0">
                    <span class="room"><?php echo JText::_('COM_SFS_ROOM'); ?> 1</span>

                    <div class="room-choice">

                        <!-- Select numbers of adults-->
                        <div class="field-room">
                            <label for="room[0][num_adults]"
                                   style="padding-right: 30px; "><?php echo JText::_('COM_SFS_ADULTS'); ?></label>
                            <select name="room[0][num_adults]" class="smaller-size adults">
                                <?php for ($i = 1; $i <= 4; $i++): ?>
                                    <option value="<?php echo $i ?>" <?php if($i == 1) echo "selected = 'selected'"?>><?php echo $i ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>

                        <div class="field-room">
                            <label for="room[0][num_children]"
                                   style="padding-right: 30px; "><?php echo JText::_('COM_SFS_CHILDREN'); ?></label>
                            <select name="room[0][num_children]" class="smaller-size children">
                                <?php for ($i = 0; $i <= 4; $i++): ?>
                                    <option value="<?php echo $i ?>" <?php if($i == 0) echo "selected = 'selected'"?>><?php echo $i ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>

                    </div>
                </li>
            </ul>
            <hr/>
        </div>


        <div data-step="3"
             data-intro="<?php echo SfsHelper::getTooltipTextEsc('search_start_date', 'help-icon', 'airline', false); ?>">
            <div class="form-group">
                <label><?php echo JText::_("COM_SFS_START"); ?>:</label>
                <?php echo $startDateList; ?>
            </div>

            <div class="form-group block-group">
                <label><?php echo JText::_("COM_SFS_END"); ?>:</label>

                <div style="display:none"><?php echo $endDateList ?></div>
                <div id="enddate" style="display: inline-block"></div>
            </div>
        </div>

        <?php
        if (isset($airline->params['enable_passenger_payment']) && (int)$airline->params['enable_passenger_payment'] == 1) :
            ?>
            <div class="form-group">
                <label class="pull-left">Invoice for:</label>

                <div class="pull-left">
                    <div class="form-group radio">
                        <label>
                            <input type="radio" name="payment_type" value="airline" checked="checked"
                                   id="payment_type_airline" style="padding: 0;margin:0">
                            <?php echo $airline->name?></label>
                    </div>

                    <div class="form-group radio">
                        <label><input type="radio" name="payment_type" id="payment_type_passenger" value="passenger"
                                      style="padding: 0;margin:0">
                            Passenger</label>
                    </div>
                </div>

            </div>
        <?php endif; ?>

        <div class="form-group">
            <a href="#" class="btn orange lg" id="btnSearch"
               data-step="4"
               data-intro="<?php echo SfsHelper::getTooltipTextEsc('button_search', 'help-icon', 'airline', false); ?>">
                <?php echo JText::_('Search'); ?>
            </a>
        </div>


        <input type="hidden" name="task" value="search.search"/>
        <input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid'); ?>"/>
        <?php echo JHtml::_('form.token'); ?>

    </form>
</div>

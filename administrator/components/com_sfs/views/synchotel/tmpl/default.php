<?php
// No direct access.
defined('_JEXEC') or die;

// Load the tooltip behavior.
JHtml::_('behavior.tooltip');
JHtml::_('behavior.modal');
$airports = $this->airports;
?>
<script src="https://code.jquery.com/jquery-1.11.2.min.js" type="text/javascript"></script>
<script>
    function loadHotels() {
        var el = $('#airports').find('tr.not-loaded:eq(0)'),
            i = el.attr('data-index'),
            id = el.attr('data-id'),
            name = el.attr('data-name');

        if (el.length) {
            el.find('.status').text('Loading...').css('color', 'red');
            $.ajax({
                url: '../index.php?option=com_sfs&task=ws.syncHotels' + '&airport_index=' + i,
                success: function () {
                    el.find('.status').text('DONE').css('color', 'green');
                    el.removeClass('not-loaded');
                    loadHotels();
                }
            });
        }
        else
        {
            $('#start').removeAttr('disabled');
            $("#filter-bar .notice").text('Synchronized all hotels completely!').css("color", "green");

        }
    }

    $(function(){
        $('#start').click(function(){
            var $button = $(this);
            $button.attr('disabled', 'disabled');
            $('#airports tr').addClass("not-loaded");
            $('#airports tr .status').text("");
            $("#filter-bar .notice").text('Please wait, downloading "Properties.txt" file from FTP...').show();
            $.ajax({
                url:'../index.php?option=com_sfs&task=ws.updateFileViaFTP',
                success: function() {
                    $("#filter-bar .notice").text('Please wait, synchronizing all hotels!').css("color", "blue");
                    loadHotels();
                },
                error:function(){
                    alert("ERROR!");
                }
            });
        });
    });
</script>
<fieldset id="filter-bar">
    <div class="sfs-cpanel-left float-left">
        <button id="start" type="button" >Start</button>
        <span class="notice" style="color:blue; display: none;"></span>
    </div>
    <div class="sfs-cpanel-right">
        <span style="margin-left: 10%"><?php echo "wget " . JURI::root()."index.php?option=com_sfs&task=ws.syncAllHotels"?></span>
    </div>
</fieldset>
<div>
    <table class="adminlist">
        <thead>
        <tr>
            <th width="40%">Airport</th>
            <th width="10%">Status</th>
            <th width="50%">Command</th>
        </tr>
        </thead>
        <tbody id="airports">
        <?php $i = -1;?>
        <?php foreach($airports as $air) : ?>
        <?php $i++?>
        <tr class="not-loaded" data-index="<?php echo $i?>" data-id="<?php echo $air->code?>" data-name="<?php echo $air->name?>">
            <td class="name"><?php echo $air->name?></td>
            <td class="status"></td>
            <td class="command"><?php echo "wget " . JURI::root()."index.php?option=com_sfs&task=ws.syncHotels&airport_index=" . $i?></td>
        </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div>
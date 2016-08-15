<?php
defined('_JEXEC') or die;
$airline = SFactory::getAirline();
///$n = (int)$this->voucher->seats;
$n = (int)$this->voucher_groups->seats;
$room_id = 0;
?>
<script type="text/javascript">
    <!--
    window.addEvent('domready', function() {

        var insertNamesForm = document.id('insertNamesForm'),
            formResult = document.id('insertNamesFormAjaxResponse');

        new Form.Request(insertNamesForm, formResult, {
            requestOptions: {
                'useSpinner': false
            },
            resetForm:false,
            onSend: function(){
                $('vgSpinner').addClass('ajax-Spinner');
            },
            onComplete: function(responseText, responseXML){
                $('vgSpinner').removeClass('ajax-Spinner');
                $('sfs-insertnames-form').setStyle('display','none');
            }
        });
        $('closeNamesForm').addEvent('click', function(e){
            e.stop();
            $('sfs-insertnames-form').setStyle('display','none');
        });

    });
    -->
</script>
<div id="sfs-insertnames-form" class="sfs-main-wrapper" style="display:none;">

    <form action="<?php echo JRoute::_('index.php')?>" method="post" name="insertNamesForm" id="insertNamesForm">

        <div class="sfs-white-wrapper floatbox sfs-insertnames-form-fields">
        	
            <div <?php if($n>3) echo 'style="overflow:auto;height:435px;"'?>>
            	<?php $i_sroom = 0; $i_sdroom = 0; $i_troom = 0; $i_qroom = 0; 
				foreach ( $this->voucher as $k => $v) :  ?>
                <table class="vgroup-name">
                    <tr>
                        <td></td><td>First name</td><td>Last name</td><td></td>
                    </tr>
                    <?php
                    if( (int)$v->sroom > 0):						
                        for($i=0;$i<(int)$v->sroom;$i++):
                            ?>
                            <tr valign="middle" class="separator">
                                <td>
                                    <input type="hidden" name="passengers[<?php echo $room_id;?>][room_type]" value="1" />
                                    Single room <?php echo $i_sroom+1;?>
                                </td>
                                <td>
                                    <input type="text" name="passengers[<?php echo $room_id;?>][0][first_name]" value="" style="width:110px" />
                                </td>
                                <td>
                                    <input type="text" name="passengers[<?php echo $room_id;?>][0][last_name]" value=""  style="width:150px" />
                                </td>
                                <td style="padding-left: 50px;">
                                    Phone number &nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="passengers[<?php echo $room_id;?>][phone_number]" value=""  style="width:150px" />
                                </td>

                            </tr>
                            <?php
                            $room_id = $room_id + 1;
                        endfor;
						$i_sroom++;
                    endif;
                    ?>

                    <?php
                    if( (int)$v->sdroom > 0):
                        for($i=0;$i<(int)$v->sdroom;$i++):
                            ?>
                            <tr valign="middle" class="separator">
                                <td rowspan="2">
                                    <input type="hidden" name="passengers[<?php echo $room_id;?>][room_type]" value="2" />
                                    Double room <?php echo $i_sdroom+1;?>
                                </td>
                                <td>
                                    <input type="text" name="passengers[<?php echo $room_id;?>][0][first_name]" value="" style="width:110px" />
                                </td>
                                <td>
                                    <input type="text" name="passengers[<?php echo $room_id;?>][0][last_name]" value=""  style="width:150px" />
                                </td>
                                <td style="padding-left: 50px;" rowspan="2">
                                    Phone number &nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="passengers[<?php echo $room_id;?>][phone_number]" value=""  style="width:150px" />
                                </td>
                            </tr>
                            <tr valign="middle">
                                <td>
                                    <input type="text" name="passengers[<?php echo $room_id;?>][1][first_name]" value="" style="width:110px" />
                                </td>
                                <td>
                                    <input type="text" name="passengers[<?php echo $room_id;?>][1][last_name]" value=""  style="width:150px" />
                                </td>

                            </tr>
                            <?php
                            $room_id = $room_id + 1;
                        endfor;
						$i_sdroom++;
                    endif;
                    ?>

                    <?php
                    if( (int)$v->troom > 0):
                        for($i=0;$i<(int)$v->troom;$i++):
                            ?>
                            <tr valign="middle" class="separator">
                                <td rowspan="3">
                                    <input type="hidden" name="passengers[<?php echo $room_id;?>][room_type]" value="3" />
                                    Triple room <?php echo $i_troom+1;?>
                                </td>
                                <td>
                                    <input type="text" name="passengers[<?php echo $room_id;?>][0][first_name]" value="" style="width:110px" />
                                </td>
                                <td>
                                    <input type="text" name="passengers[<?php echo $room_id;?>][0][last_name]" value=""  style="width:150px" />
                                </td>
                                <td style="padding-left: 50px;" rowspan="3">
                                    Phone number &nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="passengers[<?php echo $room_id;?>][phone_number]" value=""  style="width:150px" />
                                </td>
                            </tr>
                            <tr valign="middle">
                                <td>
                                    <input type="text" name="passengers[<?php echo $room_id;?>][1][first_name]" value="" style="width:110px" />
                                </td>
                                <td>
                                    <input type="text" name="passengers[<?php echo $room_id;?>][1][last_name]" value=""  style="width:150px" />
                                </td>

                            </tr>
                            <tr valign="middle">
                                <td>
                                    <input type="text" name="passengers[<?php echo $room_id;?>][2][first_name]" value="" style="width:110px" />
                                </td>
                                <td>
                                    <input type="text" name="passengers[<?php echo $room_id;?>][2][last_name]" value=""  style="width:150px" />
                                </td>

                            </tr>
                            <?php
                            $room_id = $room_id + 1;
                        endfor;
						$i_troom++;
                    endif;
                    ?>

                    <?php
                    if( (int)$v->qroom > 0):
                        for($i=0;$i<(int)$v->qroom;$i++):
                            ?>
                            <tr valign="middle" class="separator">
                                <td rowspan="4">
                                    <input type="hidden" name="passengers[<?php echo $room_id;?>][room_type]" value="4" />
                                    Quad room <?php echo $i_qroom+1;?>
                                </td>
                                <td>
                                    <input type="text" name="passengers[<?php echo $room_id;?>][0][first_name]" value="" style="width:110px" />
                                </td>
                                <td>
                                    <input type="text" name="passengers[<?php echo $room_id;?>][0][last_name]" value=""  style="width:150px" />
                                </td>
                                <td style="padding-left: 50px;" rowspan="4">
                                    Phone number &nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="passengers[<?php echo $room_id;?>][phone_number]" value=""  style="width:150px" />
                                </td>
                            </tr>
                            <tr valign="middle">
                                <td>
                                    <input type="text" name="passengers[<?php echo $room_id;?>][1][first_name]" value="" style="width:110px" />
                                </td>
                                <td>
                                    <input type="text" name="passengers[<?php echo $room_id;?>][1][last_name]" value=""  style="width:150px" />
                                </td>

                            </tr>
                            <tr valign="middle">
                                <td>
                                    <input type="text" name="passengers[<?php echo $room_id;?>][2][first_name]" value="" style="width:110px" />
                                </td>
                                <td>
                                    <input type="text" name="passengers[<?php echo $room_id;?>][2][last_name]" value=""  style="width:150px" />
                                </td>

                            </tr>
                            <tr valign="middle">
                                <td>
                                    <input type="text" name="passengers[<?php echo $room_id;?>][3][first_name]" value="" style="width:110px" />
                                </td>
                                <td>
                                    <input type="text" name="passengers[<?php echo $room_id;?>][3][last_name]" value=""  style="width:150px" />
                                </td>

                            </tr>
                            <?php
                            $room_id = $room_id + 1;
                        endfor;
						$i_qroom++;
                    endif;
                    ?>


                </table>
                
                <?php 
				$voucher_code[] = $v->code;
				$voucher_id[] = $v->id;
				endforeach;?>
            </div>

            <div id="insertNamesFormAjaxResponse"></div>

            <div style="overflow: hidden; margin: 10px 10px 10px 10px;">
                <div class="mid-button float-left" >
                    <button type="button" style="text-indent:22px;width:100px" id="closeNamesForm">
                        Close
                    </button>
                </div>
                <div class="mid-button float-right" >
                    <button type="submit" style="text-indent:22px;width:100px">
                        Save
                    </button>
                </div>
                <div id="vgSpinner" class="float-right"></div>
            </div>

        </div>

        <input type="hidden" name="vouchercode" value="<?php echo implode(",", $voucher_code);?>" />
        <input type="hidden" name="voucher_id" value="<?php echo implode(",", $voucher_id);?>" />
        <input type="hidden" name="option" value="com_sfs" />
        <input type="hidden" name="format" value="raw" />
        <input type="hidden" name="task" value="match.savePassengerNames" />
        <?php echo JHtml::_('form.token'); ?>

    </form>

</div>




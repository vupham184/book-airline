<?php
//global $passenger;
$passenger = $this->item;
?><style>
.canx-confirmation-letter{
}
.confirmation-letter{
}
.top-title{
	overflow:hidden;
}
.top-title-left{
	float:left;
}
.top-title-right{
	float:right;
}
.top-title-right{
	text-align:right;
}
.confirmation-canx-close{
	padding:3px 7px;
	border-radius:10px 20px 10px 20px;
}
.top-title-right{
	float:right !important;
}
</style>
<script>
function printDivHtml(id) {

	//Get the HTML of div
	divID = "sfs-print-wrapper" +id;
	var divElements = document.getElementById(divID).innerHTML;
	//Get the HTML of whole page
	var oldPage = document.body.innerHTML;
	//Reset the page's HTML with div's HTML only
	document.body.innerHTML =
		"<html><head><title></title></head><body>" +
		divElements + "</body>";
	//Print Page
	window.print();
	//Restore orignal HTML
	document.body.innerHTML = oldPage;
}

function closePo(){
   jQuery('.canx-confirmation-letter').css({
		'display':'none'
	});
}
</script>
<div style="position:absolute; right:10px; top:10px;">
	<a onclick="printDivHtml('Html');self.close()" class="sfs-button">Print</a>
	<a class="btn orange confirmation-canx-close" onclick="closePo();" href="javascript:void(0);">Close</a>
</div>
<div id="sfs-print-wrapperHtml" class="fs-14" style="width:100%;">
    <div class="content-confirmation-letter" style="width:100%; margin:auto;">
        <div class="top-title">
            <div class="top-title-left one" style="float:left;">
                <p>
                    Bestätigung der Ankunftszeit /
                </p>
                <p>
                    Confirmation of Arrival Time /
                </p>
                <p>
                    Confirmación de la hora de llegada
                </p>
            </div>
            <div class="top-title-right" style="float:left; ">
                <img width="200px;" src="<?php echo JURI::base();?>/media/system/images/confirmation_canx-logo.png" alt="" />
                    
            </div>
        </div><!--End top-title 1--->
        <br style="clear:both" />
        <div class="top-title tow">
            <p>
                Sehr geehrte Fluggäste, 
            </p>
            <p>
                <i>Dear passengers, </i>
            </p>
            <p>
                <i>Estimados pasajeros,</i>
            </p>
        </div><!--top-title tow-->
        
        <div class="top-title three">
            <p>
                Wir bedauern sehr, dass Ihr Flug <?php echo ( $passenger->carrier != '' ) ? $passenger->carrier :"N/A";?><?php echo $passenger->flight_no;?> von einer Verspätung betroffen ist.
            </p>
            <p>
                We deeply regret the irregular operation of your airberlin service <?php echo ( $passenger->carrier != '' ) ? $passenger->carrier :"N/A";?><?php echo $passenger->flight_no;?>.
            </p>
            <p>
                Lamentamos la irregularidad de su vuelo <?php echo ( $passenger->carrier != '' ) ? $passenger->carrier :"N/A";?><?php echo $passenger->flight_no;?>. 
            </p>
        </div><!--top-title three-->
        
        <div class="top-title four">
            <p>
                Für die Ihnen entstandenen Unannehmlichkeiten bitten wir Sie in aller Form um Entschuldigung.<br /> <i>We wish to express our most sincere apologies for any resulting inconvenience. <br />Lamentamos los inconvenientes ocasionados y le pedimos nuestras más sinceras disculpas por ello. </i>
            </p>
        </div><!--top-title four-->
        <div class="top-title five">
            
            <p>
                Geplante Ankunftszeit / Scheduled Time of Arrival / Hora prevista de llegada: <?php echo ( $passenger->sta != '' ) ? $passenger->sta :"N/A";?> Tatsächliche Ankunftszeit <br />/ Actual Time of Arrival / Hora real de llegada: <?php echo ( $passenger->ata != '' ) ? $passenger->ata :"N/A";?> 
            </p>
        </div><!--top-title five-->
        
        <div class="top-title six">
            
            <p>
                <span style="text-decoration:underline;">Anmerkung / Note: </span>
                <br />
                Bitte gestatten Sie uns den freundlichen Hinweis, dass dieser Bericht keine Anerkennung der <br />Haftung der Fluggesellschaft beinhaltet. <br />/ Please allow us a short comment: This report does not involve any acknowledgement of liability of the airline. <br />/ Por favor tenga en cuenta que este informe no implica <br />ningún reconocimiento jurídico sobre la responsabilidad de la compañía aérea. 
            </p>
        </div><!--top-title six-->
        
    </div>
</div>
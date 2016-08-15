<?php
defined('_JEXEC') or die;

if( !$this->taxiVoucher ) {
	JFactory::getApplication()->close();
}

$document = JFactory::getDocument();
$document->addStylesheet( JURI::base().'components/com_sfs/assets/css/print.css', 'text/css' , 'print' );

$is_return 	= JRequest::getInt('is_return');

$this->airline->airport_name = $this->airline->getAirportName();
?>

<div class="heading-buttons">
	<?php
	$rePrint = JRequest::getInt('reprint',0); 
	if( $rePrint == 0 ) :
	?>			
	<a onclick="window.parent.SqueezeBox.close();" class="sfs-button float-right">Close</a>			
	<a onclick="window.print();window.parent.SqueezeBox.close();" class="sfs-button float-right">Print</a>
	<?php else :?>
	<a onclick="self.close()" class="sfs-button float-right">Close</a>
	<a onclick="window.print();self.close();" class="sfs-button float-right">Print</a>
	<?php endif;?>
</div>

<div id="sfs-print-wrapper" class="taxi-voucher-wrapper clear">
	
    <table cellpadding="0" cellspacing="0" border="0" width="100%">
    	<tr valign="middle">
        	<td align="left" style="text-align:left;" width="36%">
        		<?php if($this->airline->logo) : ?>
            		<img src="<?php echo $this->airline->logo;?>" />
            	<?php endif?>
            </td>
            <td align="center" style="text-align:center" width="28%">
            	<span class="taxi-voucher-title">TAXI VOUCHER</span>
            </td>
            <td align="right" style="text-align:right; vertical-align:middle" width="36%">
            	voucher number: <span class="taxi-voucher-number"><?php echo $this->taxiVoucher->code;?></span>
            </td>
        </tr>
    </table>
    
    <div style="padding-top:10px">
    
    <table cellpadding="0" cellspacing="0" border="0" width="100%" class="taxi-voucher-table">
    	<tr valign="top">
        	<td style="height:60px;" width="25%">
            	<div class="taxi-voucher-col-title">
                	Date:
                </div>
                <div style="padding-left:40px;">
                	<?php                 	
                	echo SfsHelperDate::getDate($this->taxiVoucher->block_date,'d M Y');
                	?>
                </div>
            </td>
            
            <td width="40%">
            	<div class="taxi-voucher-col-title">
                	Flightnumber:
                </div>
            	<div style="padding-left:120px;">
                	<?php echo $this->taxiVoucher->flight_number;?>
                </div>
            </td>
            
            <td class="last" width="35%">
            	<div class="taxi-voucher-col-title">
                	Transportation:
                </div>
                <div>
                </div>
            </td>
        </tr>
        
        <tr valign="top" class="last">
        	<td style="height:50px;">
            	<div class="taxi-voucher-col-title">
                	Place of departure:
                </div>
                <div style="padding-left:20px;padding-top:10px;">
                	<?php if( (int)$is_return == 0  ) : ?>
                		<?php 
                		if($this->taxiVoucher->terminal_name) {
                			echo $this->taxiVoucher->terminal_name;
                		} else {
                			echo $this->airline->airport_name;	
                		}
                		
                		?> 
                	<?php else : ?>
                		<?php if($this->hotel):?>
		                	<?php echo $this->hotel->name?><br />
		                	<?php echo $this->hotel->address?><br />
		                	<?php echo $this->hotel->zipcode; ?>, <?php echo $this->hotel->city; ?>, <?php echo !empty($this->hotel->state_name) ? $this->hotel->state_name.',':''; ?> <?php echo $this->hotel->country_name; ?><br/>
		                	Tel. <?php echo $this->hotel->telephone ; ?>
	                	<?php endif;?>
                	<?php endif;?>
                </div>
            </td>
            
            <td>
            	<div class="taxi-voucher-col-title">
                	Reason: <?php echo !empty($this->taxiVoucher->reason)?trim($this->taxiVoucher->reason):'';?>
                </div>                
            </td>
            
            <td class="last">
            	<div class="taxi-voucher-col-title">
                	only valid on:
                </div>
                <div style="padding-left:100px;">
                	<?php echo $this->taxiVoucher->taxi_name;?>
                </div>
            </td>
        </tr>
        
    </table>
    
    </div>
    
    
    <div style="padding-top:10px">
    
    <table cellpadding="0" cellspacing="0" border="0" width="100%" class="taxi-voucher-table">    	
        <tr valign="top" class="last">
        	<td style="height:50px;" width="60%">
            	               
				<?php if( (int)$is_return == 0  ) : ?>
                    <?php if($this->hotel):?>
                    <div class="taxi-voucher-col-title">Destination</div>
                    <div style="padding-left:20px;">
                    <?php echo $this->hotel->name?><br />
                    <?php echo $this->hotel->address?><br />
                    <?php echo $this->hotel->zipcode; ?>, <?php echo $this->hotel->city; ?>, <?php echo !empty($this->hotel->state_name) ? $this->hotel->state_name.',':''; ?> <?php echo $this->hotel->country_name; ?><br/>
                    Tel. <?php echo $this->hotel->telephone ; ?>
                    </div>
                    <?php endif;?>
                <?php else : ?>
                    <div class="taxi-voucher-col-title">Destination</div> 
                    <div style="padding-left:20px;">
                    <?php echo $this->airline->airport_name?>
                    </div>
                <?php endif;?>               
                
                <?php 
                if( (int) $is_return == 0 && $this->taxiVoucher->comment ) : ?>
	                <div class="taxi-voucher-col-title" style="padding-top: 10px;">
	                	Comment
	                </div>
	                <div style="padding-left:20px;">                	                	
	                 	<?php echo $this->taxiVoucher->comment;?>                	
	                </div>
                <?php endif;?>
                
                <?php                                 	                 
	            if( (int)$is_return == 1 && $this->taxiVoucher->return_comment ):         
                ?>
	                <div class="taxi-voucher-col-title" style="padding-top: 10px;">
	                	Comment
	                </div>
	                <div style="padding-left:20px;">                 	           
	                 	<?php
	                 		echo $this->taxiVoucher->return_comment;
	                 	?>                	
	                </div>	                
                <?php endif;?>
               
            </td>
            <td class="last" width="40%">
            	<div class="taxi-voucher-col-title">
                	Passenger Names
                </div>
                <div style="padding-left:130px; padding-top:5px;">                	                 		                	
                	<?php
                	if( count($this->passengers) ):
	                	foreach ($this->passengers as $passenger):
	                		echo '<div>'.$passenger->first_name.' '.$passenger->last_name.'</div>';
	                	endforeach;
                	endif; 
                	?>	                	
                </div>
            </td>
        </tr>
        
    </table>
    
    </div>
    
    
    <div style="padding-top:10px">
    <table cellpadding="0" cellspacing="0" border="0" width="100%" class="taxi-voucher-table">
    	
        
        <tr valign="top">
        	<td style="height:50px;" rowspan="3" class="taxi-voucher-border-bottom" width="30%">
            	<div class="taxi-voucher-col-title">
                	Only valid with <?php echo $this->airline->getAirlineName();?> stamp
                </div>               
            </td>
            
            <td width="30%">
            	<div class="taxi-voucher-col-title">
                	Name <?php echo $this->airline->getAirlineName();?> agent
                </div>
                <div style="padding-top:10px; padding-left: 20px;">
                	<?php                	
                	if($this->taxiVoucher->created_by){
                		$user = JUser::getInstance($this->taxiVoucher->created_by);	
                	} else {
                		$user = JUser::getInstance($this->hotelVoucher->created_by);
                	}                	 
                	echo $user->name;
                	?>
                </div>
            </td>
            
            <td class="last" width="40%">
            	<div class="taxi-voucher-col-title">
                	Invoicing to:
                </div>
                <div style="padding-left:30px">  
                	<?php if(empty($this->item)) : ?>
                		<?php echo $this->taxiCompany->billing_registed_name?><br />
	                    <?php echo $this->taxiCompany->billing_address?><br />
	                    <?php echo $this->taxiCompany->billing_zipcode; ?>, <?php echo $this->taxiCompany->billing_city; ?>, <?php echo !empty($this->taxiCompany->billing_state) ? $this->taxiCompany->billing_state.',':''; ?> <?php echo $this->taxiCompany->billing_country; ?><br/>
	                    T.V.A. <?php echo $this->taxiCompany->billing_vat_number ; ?>
                	<?php else : ?>              	
	                	<?php echo $this->item->billing_registed_name?><br />
	                    <?php echo $this->item->billing_address?><br />
	                    <?php echo $this->item->billing_zipcode; ?>, <?php echo $this->item->billing_city; ?>, <?php echo !empty($this->item->billing_state) ? $this->item->billing_state.',':''; ?> <?php echo $this->item->billing_country; ?><br/>
	                    T.V.A. <?php echo $this->item->billing_vat_number ; ?>
                    <?php endif;?>
                </div>
            </td>
        </tr>
        
        <tr class="last" valign="top">
        	<td style="padding-bottom: 35px;">
           		<div class="taxi-voucher-col-title">
					Authorisation (Airline + name rep.)<br />
                    (if no rep. plse attach req.)
                </div>  
            </td>
        	<td class="taxi-voucher-border-right">
            	<div class="taxi-voucher-col-title">
                	For services and inquiries: 
                </div>
                <div style="padding-left:30px">
                	<?php if(empty($this->item)) : ?>
	                    <?php echo $this->taxiCompany->address?><br />
	                    <?php echo $this->taxiCompany->zipcode; ?>, <?php echo $this->taxiCompany->city; ?>, <?php echo !empty($this->taxiCompany->state) ? $this->taxiCompany->state.',':''; ?> <?php echo $this->taxiCompany->country; ?><br/>
	                    Tel. <?php echo $this->taxiCompany->telephone ; ?>
                    <?php else : ?> 
                   	    <?php echo $this->item->address?><br />
	                    <?php echo $this->item->zipcode; ?>, <?php echo $this->item->city; ?>, <?php echo !empty($this->item->state) ? $this->item->state.',':''; ?> <?php echo $this->item->country; ?><br/>
	                    Tel. <?php echo $this->item->telephone ; ?>
                    <?php endif;?>
                </div>
            </td>            
        </tr>
        
    </table>
    
    </div>
    
    
    
    
</div>

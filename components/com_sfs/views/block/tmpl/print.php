<?php
defined('_JEXEC') or die;
JHTML::_('behavior.modal');
$document = JFactory::getDocument();
$document->addStylesheet( JURI::base().'components/com_sfs/assets/css/print.css', 'text/css' , 'print' );
?>

<div id="sfs-print-wrapper">

	<div class="heading-buttons">
		<a onclick="window.print();return false;" class="sfs-button float-right">Print</a>
		<a onclick="window.parent.SqueezeBox.close();" class="sfs-button float-right">Close</a>
	</div>

    <div id="print-logo">
    	<img src="<?php echo JURI::base(); ?>components/com_sfs/assets/images/logo.jpg" width="223px" height="86px" />
    </div>
    
    <div class="clear"></div>
    
    <h1><?php echo $this->hotel->name?>: Details name loading</h1>
    
    <table width="100%" cellpadding="0" cellspacing="0" border="0">
    	<tr valign="top">
        	<td width="50%">
            	<?php echo $this->loadTemplate('hotel');?>            	      
            </td>
            <td width="50%">
            	<?php echo $this->loadTemplate('estimate');?>            	            	
            </td>
        </tr>
    </table>
    
    <?php echo $this->loadTemplate('rooms');?>
    
    
    <?php if(count($this->passengers) || !empty($this->guaranteeVoucher)):?>
    <div class="hugepaddingtop">
		<table cellpadding="0" cellspacing="0" width="100%" >
			<tr>
				<td>#</td>
		        <td class="smallpaddingbottom"><strong>First Name</strong></td>
		        <td class="smallpaddingbottom"><strong>First Name</strong></td>
		        <td class="smallpaddingbottom"><strong>Voucher Number</strong></td>
		    </tr>
	        <?php 
	            $i = 0;
	            foreach ( $this->passengers as $item ) : ?>	
	            <tr>
	                <td class="smallpaddingbottom">
	                	<?php echo ++$i;?>
	                </td>
	                <td class="smallpaddingbottom"> 
	               	 <?php echo $item->first_name;?> 
	                </td>	
	                <td class="smallpaddingbottom">
	                	<?php echo $item->last_name ;?>
	                </td>
	                <td class="smallpaddingbottom">
	                	<?php echo $item->code ;?>
	                </td>
	            </tr>            			
	        <?php endforeach ; ?>	
	        
	        <?php
		    if( !empty($this->guaranteeVoucher) && (int)$this->guaranteeVoucher->issued > 0 )
		    {
		    	$j=0;	
		    	while ($j < $this->guaranteeVoucher->issued)
		    	{
		    		?>
			    	<tr>
		                <td class="smallpaddingbottom">
		                	<?php echo ++$i;?>
		                </td>
		                <td class="smallpaddingbottom"> 
		               		No show
		                </td>	
		                <td class="smallpaddingbottom">
		                	No show
		                </td>
		                <td class="smallpaddingbottom">
		                	<?php echo $this->guaranteeVoucher->code ;?>
		                </td>
		            </tr>     		    		
		    		<?php 
		    		$j++;
		    	}
		    }
		    ?>
	         
		</table> 
    </div>
    <?php endif;?>  
  

</div>
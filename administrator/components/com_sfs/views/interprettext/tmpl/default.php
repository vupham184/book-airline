<?php
defined('_JEXEC') or die();
$post = JRequest::get('post');
$text = null;
?>
<?php if($post):
$text = JRequest::getString('interprettext');
$text = trim($text);
$array = explode(" ", $text);
?>
	<div class="width-100">
	
	<fieldset>
		<h3>Result of "<?php echo $text?>"</h3>
		<br />
		<?php		
		for( $i = 0 ; $i < count($array) ; $i++ )
		{
			$array[$i] = trim($array[$i]);
		}
		
		$i = 0;
		$bruIndex = 0;
		$airports = '';
		$flight = '';
		$pnr = null;
		
		for( $i = 1 ; $i < count($array) ; $i++ )
		{
			$tmp = trim($array[$i]);
			$bruPos = JString::strpos($tmp, 'BRU');
			if( is_int($bruPos) )
			{
				if( strlen($tmp) == 8 )
				{
					if( is_numeric($array[$i+1]) ){
						$bruIndex = $i;
						$airports = $array[$i];
						$flight = $array[$i+1];	

						if( $array[$i-1] != 'MR' && $array[$i-1] != 'MRS' && ($i-1) > 0 )
						{
							$pnr = $array[$i-1];
						}
						
						break;	
					}
				}				
			}
		}
		//get passenger name 
		$name = $array[0];
		$name = explode('/', $name);
		$surname = JString::str_ireplace('M1', '', $name[0]);
		$firstname =  $name[1];				
		?>
		
		<div>
		<?php
		if($airports)
		{
			$from = JString::substr($airports, 0,3);
			$to = JString::substr($airports, 3,3);
			$flightCode = JString::substr($airports, 6,2);
			echo 'From: '.$from.'<br />';
			echo 'To: '.$to.'<br />';
			echo 'Flight Number: '.$flightCode.$flight.'<br />';
			
			$j = $bruIndex - 1;	
			
			if($pnr) 
			{
				echo 'PNR: '.$pnr.'<br />';
				$j = $j - 1;
			}
			
			echo '<br />';
			
			$name = $array[0];
			$name = explode('/', $name);
			$surname = JString::str_ireplace('M1', '', $name[0]);
			$firstname = $name[1];	

			if( $array[$j] == 'MR' || $array[$j] == 'MRS'  )
			{
				echo 'passenger sexe: '. $array[$j].'<br />';
				$j = $j - 1 ;
			}
			
			for( $i = 0 ; $i <= $j ; $i++ )
			{
				if($i>0)
				{
					$firstname .= ' '.$array[$i];
				}
			}
			?>
			<div>Surname: <?php echo $surname?></div>
			<div>First name: <?php echo $firstname?></div>
			<?php 
			
		} 
		?>
		</div>

		
		
	</fieldset>
	</div>
<?php endif?>
<form action="<?php echo JRoute::_('index.php?option=com_sfs&view=interprettext'); ?>" method="post" name="adminForm">
	<div class="width-100">
	
	<fieldset>
		
		<textarea name="interprettext" style="width: 98%;height:120px"><?php if($text)echo $text;?></textarea>
			
		<div>
			<button type="submit" class="button" style="margin-top:5px;background:green;color:white;padding:5px 20px;border:none;">Submit</button>			
			<input type="hidden" name="option" value="com_sfs" />	        		
			<?php echo JHtml::_('form.token'); ?>
		</div>
		
	</fieldset>
	</div>
</form>

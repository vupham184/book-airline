<?php
defined('_JEXEC') or die; 
?>
<script type="text/javascript">
<!--
var rankingAjaxUri = '<?php echo JURI::root().'index.php?option=com_sfs&format=raw&id='.$this->hotel->id;?>'; 

window.addEvent('domready', function(){

	var qrequests = new Request.Queue();  
	
	function calculateRanking() 
	{		
		var ajaxElements = $$('div.ajaxCheck');				
		var qArray = new Array();	
		var ii = 0;

		elementLength = ajaxElements.length;		
		
		ajaxElements.each( function(ajaxElement) {	
					
			elementRule = ajaxElement.getProperty('rel').toString();
			
			if(elementRule)
			{				
				//0: Ranking type, 1: Room Type(s,sd,t,q), 2: Rate, 3: Room Number, 4: date, 5: TRansport, 6: update element				
				elementRuleArray = elementRule.split(',');

				rankingTask = elementRuleArray[0];
				
				var requestURL = rankingAjaxUri+'&task=roomloading.'+rankingTask;

				if(rankingTask == 'rank'){
					requestURL = requestURL + '&rate='+elementRuleArray[2];
				} else {
					requestURL = requestURL + '&rate='+elementRuleArray[3];
				}				
				
				requestURL = requestURL + '&rtype='+elementRuleArray[1];
				requestURL = requestURL + '&date='+elementRuleArray[4];
				requestURL = requestURL + '&transport='+elementRuleArray[5];

				var updateElement = $(elementRuleArray[6]);

				if( ii == (elementLength - 1) ) {
					qArray[ii] = new Request({
				    	url: requestURL,
				    	method: 'get',				    	
				        onRequest: function(){		
				        	updateElement.set('text',''); 				        
				        	updateElement.addClass('ajax-loading');
				        },
				        onSuccess: function(txt){
				        	updateElement.removeClass('ajax-loading');
				        	updateElement.set('text',txt);
				        	$('check_ranking').removeProperty('disabled');			        			 			        					    	  
				        }			        
				    });	
				} else {
					qArray[ii] = new Request({
				    	url: requestURL,
				    	method: 'get',				    	
				        onRequest: function(){		
				        	updateElement.set('text',''); 				        
				        	updateElement.addClass('ajax-loading');
				        },
				        onSuccess: function(txt){
				        	updateElement.removeClass('ajax-loading');
				        	updateElement.set('text',txt);			        			 			        					    	   
				        }			        
				    });		
				}					    			     			
			    qrequests.addRequest( 'req'+ii, qArray[ii] );
			    ii = ii + 1 ;				
			}													
		});		

		Array.each( qArray, function(req, index){						
			req.send();					
		}); 		
	}	
	
	$('check_ranking').addEvent('click',function(event){	
		event.stop();	 
		runRanking();					
	});		

	function runRanking()
	{
		$('check_ranking').setProperty('disabled','disabled');	
		var rcheck = new Request({
	    	url: '<?php echo JURI::root();?>index.php?option=com_sfs&format=raw&task=roomloading.rcheck&id=<?php echo $this->hotel->id;?>',
	    	method: 'get',
	    	onRequest: function(){	
				    
			},	    					    		        
	        onSuccess: function(txt){				
				if(txt==1) {
					calculateRanking();
				} else {								
					SqueezeBox.open($('check-ranking-msg'), {handler: 'clone',size: {x: 250, y: 90}});
				}	
									        		    	   
	        }			        
	    }).send();			
	}
	<?php if( $this->allowRunRanking == true ) : ?>
		runRanking();
	<?php endif;?>
				
});

//-->
</script>

<table cellpadding="0" cellspacing="0" border="0" class="roomloading ==">
	<thead>
		<tr>
			<?php 
				$i = 0; 
				foreach ( $this->rooms_prices as $date => $inventory ) :
			?>		
			<th>				
				<div class="date">
					<?php echo date('d-',strtotime($date)).substr(date( 'F' , strtotime($date) ),0,3).'-'.date( JText::_('y') , strtotime($date) );?><span>Rank</span>
				</div>
			</th>
			<?php 
				$i++; 
				endforeach
			?>
		</tr>
	</thead>
	<tbody>
		<tr>
			<?php 
				$i = 0; 
				foreach ( $this->rooms_prices as $date => $inventory ) :
			?>		
			<td>
				<?php
				$this->index	  = $i;	
				$this->inventory  = $inventory;
				echo $this->loadTemplate('inventory');
				?>
				<input type="hidden" id="rdate<?php echo $i;?>" name="rooms[<?php echo $i;?>][rdate]" value="<?php echo $date; ?>"/>
			</td>
			<?php 
				$i++; 
				endforeach;
			?>
		</tr>
	<!--</tbody>
</table>-->


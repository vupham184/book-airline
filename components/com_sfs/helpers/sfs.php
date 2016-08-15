<?php
defined('_JEXEC') or die();

abstract class SfsHelper {

	public static $systemCurrency = null; 
	
    public static function getArticle($id=0, $onlyText=FALSE, $cleanText=FALSE, $limiChar=0) {
    	
    	return null;
    	
        if ((int) $id <= 0) {
            return false;
        }

        jimport('joomla.application.component.model');
        JModel::addIncludePath(JPATH_SITE . '/components/com_content/models', 'ContentModel');
        $model = JModel::getInstance('Article', 'ContentModel', array('ignore_request' => true));

        #Set application parameters in model
        $app = JFactory::getApplication();
        $appParams = $app->getParams();
        $model->setState('params', $appParams);

        #Set article ID
        $model->setState('article.id', $id);

        #Get article
        $item = $model->getItem();
        $item->text = $item->introtext . $item->fulltext;

        #Clean text
        if ($cleanText) {
            $item->text = JFilterOutput::cleanText($item->text);

            if ((int) $limiChar > 0) {
                $item->text = JHTML::_('string.truncate', $item->text, $limiChar);
            }
        }

        if ($onlyText) {
            return $item->text;
        }

        return $item;
    }
    
    public static function getVoucher( $code )
    {
    	$db = JFactory::getDbo();
    	$query = $db->getQuery(true);
    	
		$query->select('a.*,b.comment AS flight_comment');
		$query->from('#__sfs_voucher_codes AS a');
		$query->innerJoin('#__sfs_flights_seats AS b ON b.id=a.flight_id');
		
		$query->select('c.taxi_voucher_id,d.taxi_id,d.is_return,e.name AS taxi_name');
		$query->leftJoin('#__sfs_airline_taxi_voucher_map AS c ON c.voucher_id=a.id');
		$query->leftJoin('#__sfs_taxi_vouchers AS d ON d.id=c.taxi_voucher_id');
		$query->leftJoin('#__sfs_taxi_companies AS e ON e.id=d.taxi_id');
		
		$query->where('a.code='.$db->quote($code));	
		
		$db->setQuery($query);
		
		$voucher = $db->loadObject();
		
		if( $db->getErrorNum() ) {
			return null;
		}
		
		return $voucher;	    	
    }
    
    public static function getIntroTextOfArticle($id) 
    {		
		$db = JFactory::getDbo();
		$query = 'SELECT introtext FROM #__content WHERE id='.(int)$id;
		$db->setQuery($query);
		$result = $db->loadResult();
		
		if( !empty($result) ) {
			return $result;
		} 

		return null;
    }

	public static function getHotelDetail($id)
	{
		if(!empty($id))
		{
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('*');

			$query->from('#__sfs_hotel');
			$query->where('id='. $id );

			$db->setQuery($query);

			$hotel = $db->loadObject();

			if ($error = $db->getErrorMsg()) {
				throw new Exception($error);
			}

			if (empty($hotel)) {
				return null;
			}
		}
		return $hotel;
	}

    public static function createRandomPassword( $length = 0, $excludeCharaters = array("o","0") ) 
    {
        $chars = "abcdefghijkmnopqrstuvwxyz0123456789";
        srand((double) microtime() * 1000000);
        $i = 0;
        $pass = '';

        $n = 7;        
        if( $length > 0  ) $n = $length;
        
        while ($i <= $n) {
            $num = rand() % 33;
            
            $tmp = substr($chars, $num, 1);
            
            if( in_array($tmp, $excludeCharaters) ) continue;
            
            $pass = $pass . $tmp;
            $i++;
        }
        return $pass;
    }
    
    public static function createRandomString( $length ) {
    	$chars = "abcdefghijkmnpqrstuvwxyz";
    	$i = 1;
        $return = '';
        
        while ($i <= $length) {
        	srand((double) microtime() * 1000000);
            $num = rand(0,23);            
            $tmp = substr($chars, $num, 1);                        
            $return .= $tmp;
            $i++;
        }
        return $return;
    }
    
	public static function createRandomChars( $length = 0 ) 
    {
        $chars = "abcdefghijkmnpqrstuvwxyz";        
        $i = 0;
        $return = '';
        
		$n = (int)$length > 0 ? $length : 2 ;
        
        while ($i <= $n) {
        	srand((double) microtime() * 1000000);
            $num = rand(0,23);            
            $tmp = substr($chars, $num, 1);                        
            $return = $return . $tmp;
            $i++;
        }
        return $return;
    }
    
	public static function createRandomNumber( $length = 0 ) 
    {   
    	$return = '';             
    	        
        $n = (int)$length > 0 ? $length : 2 ;
        
        $i = 0;
        while ($i <= $n) {        
        	srand((double) microtime() * 1000000);         
            $tmp = rand(1, 9);                        
            $return = $return . $tmp;
            $i++;
        }
        return $return;
    }

    public static function addOrdinalNumberSuffix($num) {
        if (!in_array(($num % 100), array(11, 12, 13))) {
            switch ($num % 10) {
                // Handle 1st, 2nd, 3rd
                case 1: return $num . 'st';
                case 2: return $num . 'nd';
                case 3: return $num . 'rd';
            }
        }
        return $num . 'th';
    }

    public static function formatPhone($number, $return = 1) {
    	
    	if(empty($number)) return '';
    	
        if ($return == 2) {
            return trim(JString::substr($number, strpos($number, ')') + 1));
        } else {
            $offset = strpos($number, '+') + 1;
            $length = strpos($number, ')') - $offset;
            return trim(JString::substr($number, $offset, $length));
        }        
    }

    public static function getPhoneString($code, $number) {
    	if( $code && $number ) {
    		return '(+' . $code . ') ' . $number;	
    	} else {
    		return '';	
    	}    	
    }

    public static function escape($text) {
    	return htmlspecialchars($text, ENT_COMPAT, 'UTF-8');
    }
    
    public static function getFullnameFrom($firstname,$lastname) {
	    if( $firstname && $lastname ) {
			return $firstname.' '.$lastname;
		} else if($firstname) {
			return $firstname;
		} else if($lastname){
			return $lastname;
		} else {
			return null;
		}
    }
    
    public static function getCurrency()
    {
    	if( self::$systemCurrency == null ) {
    		$params   = JComponentHelper::getParams('com_sfs');
    		self::$systemCurrency = $params->get('sfs_system_currency','EUR');
    	}
    	return self::$systemCurrency;
    }
    
	public static function getTooltipText($name, $tooltip)
    {
    	if( is_array($tooltip) && isset($tooltip[$name]) )
    	{
    		if( isset($tooltip[$name]['enable']) && (int)$tooltip[$name]['enable'] == 1 )
    		{
    			return $tooltip[$name]['text'];
    		}
    	}
    	return null;
    }
    
	public static function getTooltip($name, $html, $tooltip)
    {
    	if( is_array($tooltip) && isset($tooltip[$name]) )
    	{
    		if( isset($tooltip[$name]['enable']) && (int)$tooltip[$name]['enable'] == 1 )
    		{    			    			
    			$text = (string)$tooltip[$name]['text'];    	
    			if(strlen($text))
    			{
    				$position = 2;
	    			if( isset($tooltip[$name]['position'])  ){
						$position = (int) $tooltip[$name]['position'];
					}
    				$text = '{tip text="'.$text.'" position="'.$position.'"}'.$html.'{/tip}';    				    	    				
    				$text = JHtml::_('content.prepare', $text);
    				return $text;
    			}		    		
    		}
    	}
    	return null;
    }
    
	public static function getTooltipTextEsc($name, $html, $tooltipType)
    {
		if($tooltipType===null)
    	{
    		return htmlspecialchars($html);
    	}
		$tooltip = SFactory::getTooltips($tooltipType);
    	if( is_array($tooltip) && isset($tooltip[$name]) )
    	{
    		if( isset($tooltip[$name]['enable']) && (int)$tooltip[$name]['enable'] == 1 )
    		{
    			return htmlspecialchars($tooltip[$name]['text']);
    		}
    	}
    	return null;
    }
	
	public static function htmlTooltip($name, $html, $tooltipType = null, $showHtml=true)
    {
    	if($tooltipType===null)
    	{
    		return $html;
    	}
    	    	
    	$tooltip = SFactory::getTooltips($tooltipType);
    	
    	if($html=='help-icon')
    	{
    		$html = self::getIcon($html);
    	}
    	
    	if( is_array($tooltip) && isset($tooltip[$name]) )
    	{
    		if( isset($tooltip[$name]['enable']) && (int)$tooltip[$name]['enable'] == 1 )
    		{    			    			
    			$text = (string)$tooltip[$name]['text'];    	
    			if(strlen($text))
    			{
    				$position = 2;
    				$sticky = 0;
	    			if( isset($tooltip[$name]['position'])  ){
						$position = (int) $tooltip[$name]['position'];
					}
    				if( isset($tooltip[$name]['sticky'])  ){
						$sticky = 1;
					}
					ob_start();
					?>					
					<span id="tooltip_<?php echo $name?>" class="jmootipper" rel="{'position':<?php echo $position?>,'content':'tooltip_content<?php echo $name?>','sticky':<?php echo $sticky?>}">
						<?php echo $html;?>
					</span>
					<div id="tooltip_container_<?php echo $name?>" style="display: none;">
						<div id="tooltip_content<?php echo $name?>">
							<?php echo $text;?>
						</div>
					</div>					
    				<?php 
    				return ob_get_clean();
    			}		    		
    		}
    	}
    	if($showHtml===false)
    	{
    		return '';
    	}
    	
    	return $html;
    }
    
    public static function getIcon($type='help')
    {
    	return '<img src="components/com_sfs/assets/images/'.$type.'.png" alt="'.$type.'" />';
    }
    
	//lchung
	public static function createRandomStringUniqueID( $length ) {
    	$chars = "ABCDEFGHIJKMNPQRSTUVWXYZ0987654321";
    	$i = 1;
        $return = '';
        
        while ($i <= $length) {
        	srand((double) microtime() * 1000000);
            $num = rand(0,23);            
            $tmp = substr($chars, $num, 1);                        
            $return .= $tmp;
            $i++;
        }
        return $return;
    }
	
	public function generateVoucherUniqueID( $voucher_id = 0 ) 
	{
		$strUnique = '';
		if ( $voucher_id > 0 ) {
			
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select("a.blockcode");
			$query->from("#__sfs_reservations as a");
			$query->innerJoin('#__sfs_voucher_codes AS b ON b.booking_id=a.id');
			$query->where("b.id=".(int)$voucher_id);
			$db->setQuery($query);
			$reservation = $db->loadObject();
			if ($error = $db->getErrorMsg()) {
				throw new Exception($error);
			}

			if (empty($reservation)) {
				return null;
			}
			$strUnique = substr( $voucher_id . '-' . $reservation->blockcode, 0, 17);
		}
		else{
			$strUnique = self::createRandomStringUniqueID( 17 );
		}
		return $strUnique;
	}
	//End lchung
    
	public static function getCardNumber($cardEncoded) {
		if(is_numeric($cardEncoded)) {
			return $cardEncoded;
		}
		$cardNo = @base64_decode($cardEncoded);
		if($cardNo) {
			return $cardNo;
		}
		return $cardEncoded;
	}

    public static function getDistanceHotelAirport($hotel_id, $airport_id) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select("distance, distance_unit");
        $query->from("#__sfs_hotel_airports");
        $query->where("hotel_id=".(int)$hotel_id);
        $query->where("airport_id=".(int)$airport_id);
        $db->setQuery($query);
        return $db->loadObject();
    }

    public static function calculateTaxiValue( $hotel_id  = 0 )
    {
        if( $hotel_id > 0  ) {
            $airline = SFactory::getAirline();
            $airplusparams= $airline->airplusparams;
            return SfsWs::getHotelDistance($hotel_id, $airplusparams['taxi_fee']);
        }

        return 0;
    }

    public static function calculateMealplanValue($type)
    {
        $airline = SFactory::getAirline();
        $airplusparams= $airline->airplusparams;
        if($type == 1){
            $result = $airplusparams['meal_first_limit'];
        }elseif($type == 2){
            $result = $airplusparams['meal_second_limit'];
        }else{
            $result = 0;
        }
        return $result;
    }

    public static function isHotelCreatedByAirline($hotel_id)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select("*");
        $query->from("#__sfs_airline_user_map AS m");
        $query->innerJoin("#__sfs_hotel AS h ON h.created_by = m.user_id");
        $query->where("h.id=".(int)$hotel_id);
        $db->setQuery($query);
        $db->execute();
        return $db->getNumRows();
    }

    public static function getCurrencyMulti($hotel_id)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select("id, name, code");
        $query->from("#__sfs_currency");
        $query->order("name", "DESC");
        $db->setQuery($query);
        $db->execute();
        return $db->loadObjectList();
    }

    public static function getListAirport()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select("id, name, code");
        $query->from("#__sfs_iatacodes");
        $query->where("type = 2");
        $query->order("name", "DESC");
        $db->setQuery($query);
        $db->execute();
        return $db->loadObjectList();
    }
}



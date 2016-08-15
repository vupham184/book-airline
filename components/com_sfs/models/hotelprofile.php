<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.model');

class SfsModelHotelProfile extends JModel
{   
        
    protected function populateState()
    {
        // Get the application object.
        $app    = JFactory::getApplication();        
        $params    = $app->getParams('com_sfs');
        
        // Load the parameters.
        $this->setState('params', $params);
    }   

    public function getHotel()
    {
        $hotel = SFactory::getHotel();
        return $hotel;
    }
    
    public function getHotelContacts()
    {
        $hotel = SFactory::getHotel();
        $contacts = $hotel->getContacts();          
        return $contacts;
    }       
    
    public function getServicingAirports($selectQuery = null)
    {
        $hotel = $this->getHotel();
        
        if( isset($hotel) && $hotel->id > 0 ){
            $db = $this->getDbo();
            
            $query = $db->getQuery(true);
            if( ! $selectQuery ){
                $query->select('*');
            }else{
                $query->select($selectQuery);
            }
            $query->from('#__sfs_hotel_airports');
            $query->where('hotel_id='.$hotel->id);
            $query->order('distance ASC');
            
            $db->setQuery($query);
            
            if ( ! $selectQuery  ){
                $result = $db->loadObjectList();    
            }else {
                $result = $db->loadResultArray();
            }
            
                                         
            if(count($result))
                return $result;
            else 
                return null;    
        }
        return null;
    }   
    
    public function getTaxes()
    {
        $hotel = $this->getHotel();
        
        $db = $this->getDbo();
        $query = 'SELECT * FROM #__sfs_hotel_taxes WHERE hotel_id='.$hotel->id;
        $db->setQuery($query);
        $result = $db->loadObject();
                    
        return $result;
    }  

    public function getMerchantFee()
    {
        $hotel = $this->getHotel();
        
        $db = $this->getDbo();
        $query = 'SELECT * FROM #__sfs_hotel_merchant_fee WHERE hotel_id='.$hotel->id;
        $db->setQuery($query);
        $result = $db->loadObject();
                    
        return $result;
    } 
    
    public function getMealplan() 
    {
        $hotel = $this->getHotel();
        
        $db = $this->getDbo();
        $query = 'SELECT * FROM #__sfs_hotel_mealplans WHERE hotel_id='.$hotel->id;
        $db->setQuery($query);
        $result = $db->loadObject();
        
        return $result;
    }
    
    public function getTransport() 
    {
        $hotel = $this->getHotel();
        
        $db = $this->getDbo();
        $query = 'SELECT * FROM #__sfs_hotel_transports WHERE hotel_id='.$hotel->id;
        $db->setQuery($query);
        $result = $db->loadObject();
        
        return $result;
    }
    
    public function getHotelAdmin() 
    {
        $db = $this->getDbo();
        $hotel = $this->getHotel();
        
        $query = 'SELECT a.*,u.name FROM #__sfs_contacts AS a LEFT JOIN #__users AS u ON u.id=a.user_id WHERE a.grouptype=1 AND a.is_admin=1 AND a.group_id='.$hotel->id;
        $db->setQuery($query);
        $result = $db->loadObject();
        
        return $result;
    }


    public function getHotelLocations(){
        $db   = $this->getDbo();
        $query = 'SELECT id AS value, name AS text ' .
                ' FROM #__sfs_hotel_locations` ' .
                ' ORDER BY ordering';
        $db ->setQuery($query);
        $rows = $db->loadObjectList();        
        return $rows;
    }   
  
    public function saveAirports()
    {   
        $db = $this->getDbo();
                    
        $hotel_location = JRequest::getInt('hotel_location','0');
        
        $airports = JRequest::getVar('airport', array(), 'post', 'array');
                                            
        $hotel = $this->getHotel();
        
        //update hotel location
        if( $hotel_location != (int) $hotel->location_id  ) 
        {
            $db->setQuery('UPDATE #__sfs_hotel SET location_id='.$hotel_location.' WHERE id='.$hotel->id);
            if( ! $db->query()  )
            {
                $this->setError('Hotel ID '.$hotel->id.' Update location failed');  
            }
        }
        
        if( count($airports) )
        {
                        
            foreach ($airports as $key => $airport){                
                $table = JTable::getInstance('HotelAirport', 'JTable');
                
                $row = new stdClass();
                $row->hotel_id = $hotel->id; 
                $row->airport_id = (int)$airport['code'];
                $row->distance = $airport['distance'];
                $row->distance_unit = $airport['distance_unit'];
                if($row->airport_id) {
                    if( (int)$airport['id'] ) {
                        
                        $db->setQuery('SELECT id FROM #__sfs_hotel_airports WHERE hotel_id='.$hotel->id.' AND airport_id='.$row->airport_id);
                        $result = (int)$db->loadResult();
                        //echo $result.'x';die;
                        
                        //exist then continue
                        if( $result !=0 && $result != (int)$airport['id'] ) continue;
                        
                        $table->load( (int)$airport['id'] );
                        
                        if( ! $table->bind($row) ) {
                            $this->setError('Bind failed: update airport id '.$row->airport_id);    
                        }
                        
                        if( ! $table->store() ) {
                            $this->setError('Store failed: update airport id '.$row->airport_id);
                        }                   
                                                                
                    }else{
                        $db->setQuery('SELECT count(*) FROM #__sfs_hotel_airports WHERE hotel_id='.$hotel->id.' AND airport_id='.$row->airport_id);
                        $result = (int)$db->loadResult();                   
                        //exist then continue
                        if( ! $result  ) {                  
                            if( ! $table->save($row) ) {
                                $this->setError('Hotel airport insert failed');                     
                                //return;
                            }                               
                        }                                               
                    }
                }
                
            }
            $db->setQuery('UPDATE #__sfs_hotel_airports SET main=0 WHERE hotel_id='.$hotel->id);
            $db->query();
            
            $db->setQuery('SELECT MIN(distance) FROM #__sfs_hotel_airports WHERE hotel_id='.$hotel->id);
            $distance=$db->loadResult();
            
            if($distance){
                $db->setQuery('UPDATE #__sfs_hotel_airports SET main=1 WHERE hotel_id='.$hotel->id.' AND distance='.$distance);
                $db->query();               
            }
                                    
            $this->updateCompleteStep(3) ;          
        }
                
                
        return true;
    }
    
    public function saveRoomDetail()
    {       
        $hotel = $this->getHotel();
        
        if( $hotel->id ) {          
            $post = JRequest::get('post');          
            $post['hotel_id'] = $hotel->id;
            $post['total'] = $post['room_total'];               
            $table = JTable::getInstance('HotelRoom', 'JTable');
            
            if( ! $table->save($post) ) {
                $this->setError( $table->getError() );
                return false;
            }                                   
            $this->updateCompleteStep(4) ;

            return true;
            
        }
    
        return false;
    }
    

    public function saveTaxes($post)
    {

        $db      = $this->getDbo();
        $hotel   = $this->getHotel();
                
        if( isset($hotel) && $hotel->id ) {     
            
            $post['hotel_id'] = $hotel->id;
            
            $backendParams = $hotel->getBackendSetting();
                            
            $merchantFee = $this->getMerchantFee();  

            $upCurrency = new stdClass();
            $upCurrency->currency_id = $post['currency_val'];
            $upCurrency->id = $hotel->id;                
            $db->updateObject('#__sfs_hotel', $upCurrency, 'id');  
                        
            if( empty($merchantFee) ){
                
                $merchantFeeObject = new stdClass();
                $merchantFeeObject->hotel_id = $hotel->id;              
                if ( empty($post['merchant_fee']) ) 
                {
                    $this->setError(JText::_('COM_SFS_MERCHANT_FEE_ACCEPT'));
                    return false;
                } 
                    
                if ( empty($post['dinner_merchant_fee']) ) 
                {
                    $this->setError(JText::_('COM_SFS_MERCHANT_FEE_ACCEPT'));
                    return false;
                } 
                
                if( isset($backendParams) && (int)$backendParams->merchant_fixed_fee_enable == 1 ) {    
                    if ( empty($post['monthly_fee']) ) 
                    {
                        $this->setError(JText::_('COM_SFS_MERCHANT_FEE_ACCEPT'));
                        return false;
                    }
                    $merchantFeeObject->monthly_fee = floatval($post['monthly_fee']);                   
                }
                
                $merchantFeeObject->merchant_fee = floatval($post['merchant_fee']);
                $merchantFeeObject->merchant_fee_type = intval($post['merchant_fee_type']);
                
                $merchantFeeObject->breakfast_merchant_fee  = floatval($post['dinner_merchant_fee']);
                $merchantFeeObject->lunch_merchant_fee      = floatval($post['dinner_merchant_fee']);
                $merchantFeeObject->dinner_merchant_fee     = floatval($post['dinner_merchant_fee']);
                $merchantFeeObject->comment                 = JRequest::getString('comment');
                
                $db->insertObject('#__sfs_hotel_merchant_fee', $merchantFeeObject);                
            
            } else {                            
                if( (int) $merchantFee->agree == 0 ){ 
                    $merchantFee->comment = JRequest::getString('comment');

                    if ( empty($post['merchant_fee']) ) 
                    {
                        $this->setError(JText::_('COM_SFS_MERCHANT_FEE_ACCEPT'));
                        return false;
                    } 
                        
                    if ( empty($post['dinner_merchant_fee']) ) 
                    {
                        $this->setError(JText::_('COM_SFS_MERCHANT_FEE_ACCEPT'));
                        return false;
                    } 
                    
                    if( isset($backendParams) && (int)$backendParams->merchant_fixed_fee_enable == 1 ) {    
                        if ( empty($post['monthly_fee']) ) 
                        {
                            $this->setError(JText::_('COM_SFS_MERCHANT_FEE_ACCEPT'));
                            return false;
                        }                                           
                    }                   
                    $merchantFee->agree = 1;
                    $db->updateObject('#__sfs_hotel_merchant_fee', $merchantFee, 'hotel_id');
                }
            }
        
            
            $table = JTable::getInstance('HotelTax', 'JTable');
            
            if( ! $table->bind($post) ) {
                $this->setError('Hotel Finance bind failed');
                return false;
            }   
            
            if( ! $table->check($post) ) {
                $this->setError('Hotel Finance check failed');
                return false;
            }   

            if( ! $table->store() ) {
                $this->setError('Hotel Finance store failed');
                return false;
            }
            
            $this->updateCompleteStep(2) ; 
            
            return true;    
            
        }
        return false;
    }    
    

    public function saveMealplan($post)
    {              
        $hotel = $this->getHotel();
        
        if( $hotel->id ) {    

            $db = $this->getDbo();
            $query = $db->getQuery(true);
            $query->select('id');
            $query->from('#__sfs_hotel_mealplans');
            $query->where('hotel_id='.(int)$hotel->id);
            
            $db->setQuery($query);      
            //lchung
            $p = $this->setCustomCommaDecimal( $post );
            $post = $p;
            //End lchung
            $id = (int) $db->loadResult();
            
            if( $id > 0  ) $post['id'] = $id;
            
            $post['hotel_id'] = $hotel->id;

            $noDinner = (int)$post['no_dinner'] % 2;
            $noLunch = (int)$post['no_lunch'] % 2;
            $noBreakfast = (int)$post['bf_no_breakfast'] % 2;
            
            if($noDinner == 0){
                $post['status_dinner'] = $noDinner;
                if( $post['stop_selling_time'] != '24' ){
                    $post['stop_selling_time'] = $post['stop_selling_time_h'].':'.$post['stop_selling_time_m'];
                }

                $week = JRequest::getVar('week', array(), 'post', 'array');
                if( is_array($week) && count($week) )
                {
                    JArrayHelper::toInteger($week);
                    $post['available_days'] = implode(',', $week);
                } else {
                    $post['available_days'] = '';
                }
            }else{
                $post['status_dinner'] = $noDinner;
            }
 
            if($noLunch == 0){
                $post['status_lunch'] = $noLunch;
                if( (int)$post['lunch_service_hour'] == 2 ) {
                    $post['lunch_opentime'] = $post['lunch_opentime_h'].':'.$post['lunch_opentime_m'];
                    $post['lunch_closetime'] = $post['lunch_closetime_h'].':'.$post['lunch_closetime_m'];   
                }   

                $lunchweek = JRequest::getVar('lunchweek', array(), 'post', 'array');
                if( is_array($lunchweek) && count($lunchweek) )
                {
                    JArrayHelper::toInteger($lunchweek);
                    $post['lunch_available_days'] = implode(',', $lunchweek);
                } else {
                    $post['lunch_available_days'] = '';
                }
            }else{
                $post['status_lunch'] = $noLunch;
            }

            if($noBreakfast == 0){
                $post['status_break'] = $noBreakfast;
                if( (int)$post['bf_service_hour'] == 2 ) {
                    $post['bf_opentime'] = $post['bf_opentime_h'].':'.$post['bf_opentime_m'];
                    $post['bf_closetime'] = $post['bf_closetime_h'].':'.$post['bf_closetime_m'];    
                }
            }else{
                $post['status_break'] = $noBreakfast;
            }
                        
            if( (int) $post['service_hour'] == 2 ) {                
                $post['service_opentime'] = $post['service_opentime_h'].':'.$post['service_opentime_m'];
                $post['service_closetime'] = $post['service_closetime_h'].':'.$post['service_closetime_m'];
            }
                               
            
            $table = JTable::getInstance('HotelMealplan', 'JTable');
            
            if( ! $table->save($post) ) {
                $this->setError('Hotel FB save failed');
                return false;
            }           
            
            $this->updateCompleteStep(5) ;
            
            return true;    
            
        }
        return false;
    }
    
    public function saveTransport($post)
    {       
        $hotel = $this->getHotel();
        
        if( $hotel->id ) {    

            $db = $this->getDbo();
            $query = $db->getQuery(true);
            $query->select('id');
            $query->from('#__sfs_hotel_transports');
            $query->where('hotel_id='.(int)$hotel->id);
            
            $db->setQuery($query);      

            $id = (int) $db->loadResult();
            
            if( $id > 0  ) $post['id'] = $id;
            
            $post['hotel_id'] = $hotel->id;

            if( (int) $post['operating_hour'] == 2 ) {
                $post['operating_opentime'] = $post['service_opentime_h'].':'.$post['service_opentime_m'];
                $post['operating_closetime'] = $post['service_closetime_h'].':'.$post['service_closetime_m'];
            }
                        
            $table = JTable::getInstance('HotelTransport', 'JTable');
            
            if( ! $table->save($post) ) {
                $this->setError('Hotel Transport save failed');
                return false;
            }           
            
            $this->updateCompleteStep(6) ;
                        
            return true;            
        }
        return false;
    }      
    
    public function confirmTerms() 
    {
        $post = JRequest::get('post');
        if( $post['agree'] ) {
            $this->updateCompleteStep(9) ; 
            return true;                
        }
        return false;
    }
    
    public function finishRegister() 
    {
        $post = JRequest::get('post');
        if( $post['hotel_id'] ) {
            $this->updateCompleteStep(9) ;
            return true;                
        }
        return false;
    }
    
    private function updateCompleteStep($step){
        $db = $this->getDbo();
        $hotel = $this->getHotel(); 
        if( $hotel->step_completed < $step ) {
            $db->setQuery('UPDATE #__sfs_hotel SET step_completed='.$step.' WHERE id='.$hotel->id);          
            if( ! $db->query() ) {
                $this->setError('Update completed step to '.$step.' failed');
            }
        }
        return true;
    }
    
    //lchung
    public function setCustomCommaDecimal( $post )
    {
        $course_1 = $post['course_1'];
        $course_2 = $post['course_3'];
        $course_3 = $post['course_2'];
        $tax = $post['tax'];
        
        $lunch_standard_price = $post['lunch_standard_price'];          
        $lunch_tax = $post['lunch_tax'];
        
        $bf_standard_price = $post['bf_standard_price'];
        $bf_layover_price = $post['bf_layover_price'];
        $bf_tax = $post['bf_tax'];
        
        $strA = array();
        $strA["course_1"] = ".";
        $strA["course_2"] = ".";
        $strA["course_3"] = ".";
        $strA["lunch_standard_price"] = ".";
        $strA["lunch_tax"] = ".";
        $strA["bf_standard_price"] = ".";
        $strA["bf_layover_price"] = ".";
        $strA["bf_tax"] = ".";
        
        if ( count( explode( ",", $course_1 ) ) > 1 ) {
            $strA["course_1"] = ",";
            $post['course_1'] = str_replace(",", ".", $course_1);
        }
        if ( count( explode( ",", $course_2 ) ) > 1 ) {
            $strA["course_2"] = ",";
            $post['course_2'] = str_replace(",", ".", $course_2);
        }
        if ( count( explode( ",", $course_3 ) ) > 1 ) {
            $strA["course_3"] = ",";
            $post['course_3'] = str_replace(",", ".", $course_3);
        }
        if ( count( explode( ",", $tax ) ) > 1 ) {
            $strA["tax"] = ",";
            $post['tax'] = str_replace(",", ".", $tax);
        }
        //------------------
        
        if ( count( explode( ",", $lunch_standard_price ) ) > 1 ) {
            $strA["lunch_standard_price"] = ",";
            $post['lunch_standard_price'] = str_replace(",", ".", $lunch_standard_price);
        }
        if ( count( explode( ",", $lunch_tax ) ) > 1 ) {
            $strA["lunch_tax"] = ",";
            $post['lunch_tax'] = str_replace(",", ".", $lunch_tax);
        }
        //----------------
        
        if ( count( explode( ",", $bf_standard_price ) ) > 1 ) {
            $strA["bf_standard_price"] = ",";
            $post['bf_standard_price'] = str_replace(",", ".", $bf_standard_price);
        }
        if ( count( explode( ",", $bf_layover_price ) ) > 1 ) {
            $strA["bf_layover_price"] = ",";
            $post['bf_layover_price'] = str_replace(",", ".", $bf_layover_price);
        }
        if ( count( explode( ",", $bf_tax ) ) > 1 ) {
            $strA["bf_tax"] = ",";
            $post['bf_tax'] = str_replace(",", ".", $bf_tax);
        }
        $custom_comma_decimal = json_encode( $strA );
        $post['custom_comma_decimal'] = $custom_comma_decimal;
        return $post;
    }
    
    //lchung
    public function InsertGeolocation()
    {   
        $db = $this->getDbo();
        $geo_location_latitude = JRequest::getVar('geo_location_latitude','0');
        $geo_location_longitude = JRequest::getVar('geo_location_longitude','0');
        $hotel_id = JRequest::getInt('hotel_id','0');
        
        //update hotel location
        if( $hotel_id > 0 ) 
        {
            $db->setQuery('UPDATE #__sfs_hotel SET 
            geo_location_latitude="'.$geo_location_latitude.'",
            geo_location_longitude="'.$geo_location_longitude.'" 
            WHERE id='.$hotel_id);
            if( ! $db->query() ) {
                $this->setError('Update completed failed');
            }
        }
        return;
    }
    //End lchung
    
}

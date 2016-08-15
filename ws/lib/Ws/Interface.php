<?php

interface Ws_Interface {
	# static data group
	public function getAirports($limit = 30, $offset = 0, $options = array());
	public function getBookingSources($limit = 30, $offset = 0, $options = array());
	public function getCardTypes($limit = 30, $offset = 0, $options = array());
	public function getCurrencies($limit = 30, $offset = 0, $options = array());
	public function getExtraTypes($limit = 30, $offset = 0, $options = array());
	public function getFacilities($limit = 30, $offset = 0, $options = array());
	public function getProperties($limit = 30, $offset = 0, $options = array());
	public function getPropertiesNearByAirportIATACode($code, $limit = 30, $offset = 0, $options = array());
	public function getPropertyByPreferenceID($propertyReferenceID);
	public function getPropertyDetail($propertyID, $propertyReferenceID);
	public function getLocations($limit = 30, $offset = 0, $options = array());
	public function getMealBasis($limit = 30, $offset = 0, $options = array());
	public function getProductAttributes($limit = 30, $offset = 0, $options = array());
	public function getRoomTypes($limit = 30, $offset = 0, $options = array());
	public function getStarRatings($limit = 30, $offset = 0, $options = array());
	public function getBlackLists($limit = 30, $offset = 0, $options = array());
	# END static data group

    public function getAirportNumberOfPriorities();
    public function getAllAirportLocation();
    public function getCurrencyIdByCurrencyCode($currencyCode);
    public function getRegionCodeByRegionID($regionID);

	# search
	public function searchHotels(Ws_Do_Search_Request $request, $limit = 300, $offset = 0);
	# END search
	
	# book
	/**
	 * 
	 * @param Ws_Do_PreBook_Request $request
	 * @return Ws_Do_PreBook_Response
	 */
	public function preBook(Ws_Do_PreBook_Request $request);
	/**
	 * 
	 * @param Ws_Do_Book_Request $request
	 * @return Ws_Do_Book_Response
	 */
	public function book(Ws_Do_Book_Request $request);
	public function cancel($request);
	# END book
}
<?php

/**
 * The Credits object is an attribute of the <CloudNCo_User> object.
 * 
 * It let's you manage credits available for the user and spend more credits.
 * 
 * 
 * 
 * 
 * Attributes:
 * (start generated)
 * credits - (int) Current number of credits (Mandatory, default value: 0)
 * 
 * last_purchase_date - (timestamp) Timestamp of last time the user purchased credits
 * 
 * last_purchase_credits - (timestamp) Number of credits purchased by user last time
 * 
 * (end)
 */
class CloudNCo_Credits extends CloudNCo_APIRequest {

	function __construct ( $values = array () ) {
		$this->setAttributes(array(
			new CloudNCo_Attribute('credits', CloudNCo_Attribute::INT_ATTR, true, 0),
			new CloudNCo_Attribute('last_purchase_date', CloudNCo_Attribute::TIMESTAMP_ATTR, false),
			new CloudNCo_Attribute('last_purchase_credits', CloudNCo_Attribute::TIMESTAMP_ATTR, false)
		)) ;

		$this->setAll($values);
	}
	
	/**
	 * Check if the user has at least the given number of credits
	 * 
	 * @param int $number Number of credits to check
	 * @return boolean True if user has as many credits as given or more, false otherwise
	 * @throws CloudNCo_Exception Throwed in case the $number parameter is not an int or is lower than 1
	 */
	function hasCredits ( $number ) {
		
		if ( !is_int($number) || $number < 1 ) {
			throw new CloudNCo_Exception ('CloudNCo_Credits::spend: number of credits to send must be typed as int and be greater than 0') ;
		}
		
		return $this->credits >= $number ;
	}
	
	/**
	 * Spend some credits
	 * 
	 * When this function is called and if user has sufficient credits, 
	 * the CloudNCo platform API is automatically called to update the user credits value.
	 * 
	 * @param int $number Number of credits to spend
	 * @return boolean True if user has as many credits as given or more, false otherwise
	 * @throws CloudNCo_Exception Throwed in case the $number parameter is not an int or is lower than 1
	 */
	function spend ( $number ) {

		if ( !is_int($number) || $number < 1 ) {
			throw new CloudNCo_Exception ('CloudNCo_Credits::spend: number of credits to send must be typed as int and be greater than 0') ;
		}

		if ( !$this->hasCredits($number) ) {
			return false ;
		}

		$this->credits -= $number ;
		
		return true ;
	}
	
	/**
	 * Get the current number of credits available for the user
	 * 
	 * @return int Number of credits available
	 */
	function balance () {
		return $this->credits ;
	}
}

?>
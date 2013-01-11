<?php

/**
 * A CloudNCo application.
 *
 * Attributes:
 * (start generated)
 * developement_mode - (boolean) Is application currently in development mode (Mandatory, default value: false)
 * 
 * name - (string) Application name (Mandatory)
 * 
 * code - (string) Application code (Mandatory)
 * 
 * company - (string) Company
 * 
 * ce_application - (string) Customer Engagement tracking application: "apptegic" so far (Mandatory, default value: "apptegic")
 * 
 * ce_account - (string) Customer Engagement tracking application account code
 * 
 * ce_dataset - (string) Customer Engagement tracking application dataset
 * 
 * ca_application - (string) Analytics application: "ga" for Google Analytics so far (Mandatory, default value: "ga")
 * 
 * ca_account - (string) Analytics tracking code
 * 
 * url - (string) Application URL (Mandatory)
 * 
 * help_url - (string) Application help page URL
 * 
 * privacy_url - (string) Application privacy page URL
 * 
 * variables - (object) Application variables (Mandatory, default value: new CloudNCo_Variables()
 * 
 * (end)
 */
class CloudNCo_Application extends CloudNCo_APIRequest {
	
	
	private static $privateKey = null ;
	
	function __construct () {
		
		$this->setAttributes(array(
			new CloudNCo_Attribute('developement_mode', CloudNCo_Attribute::BOOLEAN_ATTR, true, false),
			new CloudNCo_Attribute('name', CloudNCo_Attribute::STRING_ATTR, true),
			new CloudNCo_Attribute('code', CloudNCo_Attribute::STRING_ATTR, true),
			new CloudNCo_Attribute('company', CloudNCo_Attribute::STRING_ATTR, false),
			new CloudNCo_Attribute('ce_application', CloudNCo_Attribute::STRING_ATTR, true, 'apptegic'),
			new CloudNCo_Attribute('ce_account', CloudNCo_Attribute::STRING_ATTR, false),
			new CloudNCo_Attribute('ce_dataset', CloudNCo_Attribute::STRING_ATTR, false),
			new CloudNCo_Attribute('ca_application', CloudNCo_Attribute::STRING_ATTR, true, 'ga'),
			new CloudNCo_Attribute('ca_account', CloudNCo_Attribute::STRING_ATTR, false),
			new CloudNCo_Attribute('url', CloudNCo_Attribute::STRING_ATTR, true),
			new CloudNCo_Attribute('help_url', CloudNCo_Attribute::STRING_ATTR, false),
			new CloudNCo_Attribute('privacy_url', CloudNCo_Attribute::STRING_ATTR, false),
			new CloudNCo_Attribute('variables', CloudNCo_Attribute::OBJECT_ATTR, true, new CloudNCo_Variables())
		));
		
		parent::__construct() ;
		
	}
	
	
	/**
	 * Set the application private key
	 * 
	 * You should not directly use this method as it's called by the <CloudNCo>::init () method
	 * 
	 * 
	 * @param string $privateKey The private key
	 * @throws CloudNCo_Exception Throwed in case the privateKey has been set yet
	 */
	function setPrivateKey ( $privateKey ) {
		if ( is_string($privateKey) && $privateKey != '' ) {
			self::$privateKey = $privateKey ;
		} else {
			throw new CloudNCo_Exception () ;
		}
	}
	
	/**
	 * [INTERNAL] Get the private key
	 * 
	 * @return string The private key
	 */
	function getPrivateKey () {
		return self::$privateKey;
	}

	/**
	 * Check if the private key is set
	 * 
	 * @return boolean True if private key is set, false otherwise
	 */
	function hasPrivateKey () {
		return !is_null( self::$privateKey );
	}
}

?>
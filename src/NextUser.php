<?php

/**
 * NextUser class (PHP API v1.0) is deprecated — you should now use the CloudNCo main class
 * 
 * It's here for retro-compatibility until v1.3 of the CloudNCo PHP API
 * 
 * 
 */
class NextUser {

	static function init () {
		
	}
	
	/**
	 * Get the API config object
	 *
	 * @return CloudNCo_Config Returns the CloudNCo_Config instance used by the CloudNCo API
	 */
	static function config (){
		return CloudNCo::config ();
	}

	/**
	 * Get the API user object
	 *
	 * @return CloudNCo_User Returns the CloudNCo_Config instance used by the CloudNCo API
	 */
	static function user (){
		return CloudNCo::user ();
	}


	/**
	 * Get the API utils object
	 *
	 * @return CloudNCo_Utils Returns the CloudNCo_Utils instance used by the CloudNCo API
	 */
	static function utils () {
		return CloudNCo::utils ();
	}


	/**
	 * Get the API session object
	 *
	 * @return CloudNCo_Session Returns the CloudNCo_Session instance used by the CloudNCo API
	 */
	static function session () {
		return CloudNCo::session ();
	}


	/**
	 * Get the API security object
	 *
	 * @return CloudNCo_Security Returns the CloudNCo_Security instance used by the CloudNCo API
	 */
	static function security () {
		return CloudNCo::security ();
	}


}



?>
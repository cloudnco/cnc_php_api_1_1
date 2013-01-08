<?php

/**
 * Utility class for CloudNCo subscription callback
 * 
 * 
 */
class CloudNCo_Callback extends CloudNCo_Base {
	
	private $data = array () ;
	
	function __construct () {
		
		if ( !empty ( $_POST ) && 
			array_key_exists('public', $_POST) &&
			array_key_exists('hash', $_POST) &&
			array_key_exists('user', $_POST) &&
			$this->isValidKey($_POST['public'], $_POST['hash']) )
		{
			$content = $_POST['user'] ;

			if ( strpos($content, '\\"') !== false )
			{
				$content = str_replace('\\"', '"', $content);
			}

			$this->data = unserialize($content) ;
		}
		
		return false ;
	}
	
	/**
	 * Check if current callback call is actually a callback and is valid
	 * 
	 * @return boolean True if callback is valid
	 */
	function isCallback () {
		return !empty($this->data) ;
	}

	/**
	 * Get the callback data
	 * 
	 * @return array Array of user data
	 */
	public function getData () {
		return $this->data ;
	}
	
	/**
	 * Get the callback type
	 * 
	 * @return string Callback type (may be signup, profile, cancel) or null if callback is not valid
	 */
	public function getType() {
		return !empty($this->data) ? $this->data['callback_action'] : null ;
	}
	
	/**
	 * Check if callback is a new subscription callback
	 * 
	 * @return boolean True if callback is for a new subscription
	 */
	public function isNewSubscription () {
		return !empty($this->data) && $this->data['callback_action'] == 'signup' ;
	}
	
	/**
	 * Check if callback is a user profile change callback
	 * 
	 * @return boolean True if callback is for a a changed profile
	 */
	public function isProfile () {
		return !empty($this->data) && $this->data['callback_action'] == 'profile' ;
	}
	
	/**
	 * Check if callback is a closed subscription callback
	 * 
	 * @return boolean True if callback is for closing a subscription
	 */
	public function isCancelSubscription () {
		return !empty($this->data) && $this->data['callback_action'] == 'cancel' ;
	}
	
	/**
	 * Send a success response for the current callback
	 * 
	 * @return boolean True if callback is for closing a subscription
	 * @throw CloudNCo_Exception Throwed if headers sent
	 */
	public function respondSuccess () {
		if (headers_sent()) {
			throw new CloudNCo_Exception('Can\'t respond to callback: headers yet sent');
		}
		die () ;
	}
	
	/**
	 * Send a success response for the current callback
	 * 
	 * @return boolean True if callback is for closing a subscription
	 * @throw CloudNCo_Exception Throwed if headers sent
	 */
	public function respondError ( $message = null ) {
		if (headers_sent()) {
			throw new CloudNCo_Exception('Can\'t respond to callback: headers yet sent');
		}
		header('HTTP/1.0 500 Internal Server Error') ;
		die ( is_null($message) ? '' : $message ) ;
	}
	
	
	/**
	 * [INTERNAL] Check an API key
	 * 
	 * Requires the security credentials to be set in configuration.
	 * 
	 * @param string $public API Public Key used for transaction
	 * @param string $hashed API Key to be checked
	 * @return True if key is valid, false otherwise
	 */
	final public function isValidKey ( $public, $hashed )
	{
		if ( NextUser::config()->has( 'privateKey' ) == false )
		{
			return false ;
		}
		
		if ( $hashed == sha1($public . CloudNCo::config()->get('privateKey') ))
		{
			return true ;
		}
		
		return false ;
	}
	
}

?>
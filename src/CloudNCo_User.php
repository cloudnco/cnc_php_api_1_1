<?php

/**
 * 
 * Attributes:
 * (start generated)
 * email - (string) Email of the user
 * 
 * name - (string) Name of the user, if available
 * 
 * logged_in - (boolean) Is the user logged in (Mandatory, default value: false)
 * 
 * subusers - (object) SubUsers object (Mandatory)
 * 
 * subscription - (object) Subscription details for the current application (Mandatory)
 * 
 * variables - (object) User variables (Mandatory)
 * 
 * parent - (object) Parent user object if exists
 * 
 * (end)
 */
class CloudNCo_User extends CloudNCo_APIRequest {

	function __construct ( $values = array () ) {
		$this->setAttributes(array(
			new CloudNCo_Attribute('email', CloudNCo_Attribute::STRING_ATTR, false),
			new CloudNCo_Attribute('name', CloudNCo_Attribute::STRING_ATTR, false),
			new CloudNCo_Attribute('logged_in', CloudNCo_Attribute::BOOLEAN_ATTR, true, false),
			new CloudNCo_Attribute('subusers', CloudNCo_Attribute::OBJECT_ATTR, true),
			new CloudNCo_Attribute('subscription', CloudNCo_Attribute::OBJECT_ATTR, true),
			new CloudNCo_Attribute('variables', CloudNCo_Attribute::OBJECT_ATTR, true),
			new CloudNCo_Attribute('parent', CloudNCo_Attribute::OBJECT_ATTR,false)
		)) ;
		$this->subscription = new CloudNCo_Subscription() ;
		$this->subusers = new CloudNCo_SubUsers () ;
		$this->variables = new CloudNCo_Variables () ;
		$this->setAll($values);
	}
	
	/**
	 * Helper function: try to auto login the user
	 * 
	 * This method redirects the user to the CloudNCo account management platform.
	 * 
	 * @param string $redirect Redirect the user to this url once the user is logged in
	 */
	public function autologin ( $redirect = false ) {
		if ( !$this->is_subscriber ) {
			if ( isset($_COOKIE) && array_key_exists('cloudnco_al', $_COOKIE) && !headers_sent() ) {
				header('Location: ' . CloudNCo::utils()->getLoginURL($redirect)) ;
				die () ;
			}
		}
	}
	
	/**
	 * Helper function: check if user is a subscriber
	 *
	 * @return boolean True if user is a subscriber, false otherwise
	 */
	public function isSubscriber ( $autologin = false, $redirect = false )
	{
		if ( $this->subscription->subscriber == false && $autologin === true ) {
			$this->autologin($redirect) ;
		}
		return $this->subscription->subscriber === true ;
	}

	/**
	 * Helper function: check if user is a subscriber AND has subscribed to a free plan
	 *
	 * @return boolean True if user is a subscriber and has subscribed to a free plan, false otherwise
	 */
	public function isFreeSubscriber ( $autologin = false, $redirect = false )
	{
		if ( $this->subscription->subscriber == false && $autologin === true ) {
			$this->autologin($redirect) ;
		}
		return $this->subscription->subscriber && $this->subscription->subscriber->plan_free == true ;
	}

	/**
	 * Helper function: check if user is NOT subscriber
	 *
	 * @return boolean True if user is NOT a subscriber, false otherwise
	 */
	public function isNotSubscriber ( $autologin = false, $redirect = false )
	{
		if ( !$this->subscription->subscriber == false && $autologin === true ) {
			$this->autologin($redirect) ;
		}
		return $this->subscription->subscriber == false ;
	}

	/**
	 * Helper function: checks if current user is a subscriber, and if he subscribed to a specific plan
	 *
	 * @param string $planCode Code of the plan to check
	 * @return boolean True if user is a subscriber and if he subscribed to a plan, false otherwise
	 */
	public function plan ( $planCode )
	{
		return $this->isSubscriber () && $planCode == $this->subscription->plan_code ;
	}
	
}

?>
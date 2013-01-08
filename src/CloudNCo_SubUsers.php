<?php

/**
 * The SubUsers object is an attribute of the <CloudNCo_User> object.
 * 
 * It let's you manage retrieve informations about sub users of the current logged user
 * 
 * Attributes:
 * (start generated)
 * subusers_list - (array) Array of <CloudNCo_User> objects (Mandatory, default value: array()
 * 
 * subusers_count - (int) Total number of sub users for the current user (Mandatory, default value: 0)
 * 
 * (end)
 *
 */
class CloudNCo_SubUsers extends CloudNCo_Object {
	
	function __construct ( $values = array () ) {
		$this->setAttributes(array(
			new CloudNCo_Attribute('subusers_list', CloudNCo_Attribute::ARRAY_ATTR, true, array() ),
			new CloudNCo_Attribute('subusers_count', CloudNCo_Attribute::INT_ATTR, true, 0 )
		)) ;

		$this->setAll($values);
	}
	
	/**
	 * Sends an invitation to an email address in order to create a new sub user
	 * 
	 * @param string $email 
	 * @return boolean 
	 */
	public function invite ( $email ) {
		return false ;
	}
	
	/**
	 * Get subuser details given its email
	 * 
	 * Returns a <CloudNCo_User> object
	 *
	 * @param string $email f the sub user to get detail about
	 * @return CloudNCo_User A user object if sub user found, null otherwise
	 */
	public function get ( $email ) {
		return null ;
	}
	
	/**
	 * Get a list of all the sub users
	 * 
	 * @return array An array of CloudNCo_User objects
	 */
	public function getAll () {
		return array () ;
	}

}

?>
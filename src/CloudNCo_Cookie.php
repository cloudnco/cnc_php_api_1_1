<?php

/**
 * Class: CloudNCo_Cookie
 *
 * Creates and destroy a cookie storing the CloudNCo session id
 */
class CloudNCo_Cookie extends CloudNCo_Object {

	private $status = 0 ;

	private $sess_id = null ;

	/**
	 * Creates a new CloudNCo_Cookie instance
	 *
	 * Try to restore existing cookie, otherwise creates a new one
	 */
	public function __construct() {
		// Try to restore
		if ( !$this->restore() ) {
			// Can't restore: create a new one
			$this->destroy();
		} else {
			// Restored save it
			$this->save() ;
		}
	}

	/**
	 * Returns the status of the cookie:
	 *
	 * 0 - not inited (error occured)
	 * 1 - new
	 * 2 - restored
	 *
	 * @return int Cookie status
	 */
	public function status () {
		return $this->status ;
	}


	/**
	 * Destroys the current cookie and creates a new one
	 *
	 * @return int Cookie status
	 */
	public function destroy () {
		setrawcookie('cnc_sess','');
		$this->sess_id = $this->generateID() ;
		$this->save() ;
		$this->status = 1 ;

		return $this->status ;
	}
	
	/**
	 * Get session ID 
	 */
	public function getID () {
		return $this->sess_id ;
	}
	
	/**
	 * [PRIVATE] Save cookie
	 * 
	 * @throws CloudNCo_Exception
	 */
	private function save () {
		if ( headers_sent() ) {
			throw new CloudNCo_Exception ('CloudNCo_Session::save() should be used prior to any output');
 		}
		setrawcookie('cnc_sess',$this->sess_id,time() + (86400 * 30));
	}
	
	/**
	 * [PRIVATE] Restore cookie
	 * 
	 * @throws CloudNCo_Exception
	 */
	private function restore ( ) {
		if ( isset($_COOKIE) && array_key_exists('cnc_sess', $_COOKIE) ) {
			$this->sess_id = $_COOKIE['cnc_sess'] ;
			$this->status = 2 ;
		}

		return !is_null( $this->sess_id ) ;
	}

	
	/**
	 * [PRIVATE] Generate new unpredictible cookie ID
	 * 
	 * @throws CloudNCo_Exception
	 */
	private function generateID () {
		$len = 40 ;
		$str = '';
		while ( ($len--) > 0 ) {
			$str .= chr(mt_rand(33, 126));
		}
		return sha1(mt_rand(1,100000).sha1(microtime().sha1($str)).mt_rand(1,100000)) ;
	}

}

?>
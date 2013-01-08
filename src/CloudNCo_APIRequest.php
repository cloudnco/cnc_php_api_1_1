<?php

/**
 * Class to create a CloudNCo API request
 * 
 * 
 * Attributes:
 * (start generated)
 * api_url - (string) URL of the API request (Mandatory)
 * 
 * method - (string) Method of the request, may be post and get (Mandatory, default value: "get")
 * 
 * data - (array) Data to be sent in POST mode
 * 
 * format - (string) Format of the response - "json" only for the moment (Mandatory, default value: "json")
 * 
 * private - (boolean) Sign and crypt (Mandatory, default value: false)
 * 
 * api_key - (string) Private API key for private requests (Mandatory)
 * 
 * (end)
 * 
 */
class CloudNCo_APIRequest extends CloudNCo_Object {

	private $headers ;

	private $body ;
	
	private static $binds = array () ;
	
	function __construct ( $values = array () ) {
		$this->setAttributes(array(
			new CloudNCo_Attribute('api_url', CloudNCo_Attribute::STRING_ATTR, true),
			new CloudNCo_Attribute('method', CloudNCo_Attribute::STRING_ATTR, true, 'get'),
			new CloudNCo_Attribute('data', CloudNCo_Attribute::ARRAY_ATTR, false),
			new CloudNCo_Attribute('format', CloudNCo_Attribute::STRING_ATTR, true, 'json'),
			new CloudNCo_Attribute('private', CloudNCo_Attribute::BOOLEAN_ATTR, true, false),
			new CloudNCo_Attribute('api_key', CloudNCo_Attribute::STRING_ATTR, true),
		));
		$this->setAll($values);
	}
	
	
	/**
	 * Bind result of next request to current class instance
	 * 
	 * @throws CloudNCo_Exception Throwed if API call fails
	 */
	function bind ( $key ) {
		self::$binds[$key] = $this ;
	}
	
	
	/**
	 * Execute the request
	 * 
	 * @return mixed Result of request
	 * @throws CloudNCo_Exception Throwed if API call fails
	 */
	function execute () {

		$this->body = '' ;
		$this->headers = '' ;
		
		$ch = curl_init($this->api_url);

		if ( $this->method == 'post' ) {
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $this->data );
		}

		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_HEADERFUNCTION, array($this,'_headers'));
		curl_setopt($ch, CURLOPT_WRITEFUNCTION, array($this,'_body'));

		$result = curl_exec($ch);

		if ( $result === false ) {
			throw new CloudNCo_Exception('API Request failed: ' . $this->api_url ) ;
		}

		$result = false ;
		switch($this->format) {
			case 'json':
			default:
				$result = json_decode($this->body, true) ;
		}
		
		if ( !empty(self::$binds) ) {
			
			if ( !empty($result) ) {
				foreach(self::$binds as $key => $instance ) {
					if ( array_key_exists($key, $result) ) {
						foreach ( $result[$key] as $k => $v ) {
							if ( $instance->getAttribute($k)->getType() == CloudNCo_Attribute::OBJECT_ATTR ) {
								$obj = $instance->$k ;
								if ( !is_null( $obj ) && method_exists($obj, 'setAll') ) {
									$obj->setAll($v) ;
								}
							} else {
								$instance->$k = $v ;
							}
						}
					}
				}
			}
			
			self::$binds = array () ;
		}
		
		return $result ;
	}

	// For cURL headers callback
	private function _headers ( $ch, $headers ) {
		$this->headers .= $headers ;
		return strlen($headers);
	}

	// For cURL body callback
	private function _body ( $ch, $body ) {
		$this->body .= $body ;
		return strlen($body);
	}


}

?>
<?php

// Require all CloudNCo classes

require_once('CloudNCo_Exception.php') ;
require_once('CloudNCo_Base.php') ;
require_once('CloudNCo_Object.php') ;
require_once('CloudNCo_Attribute.php') ;
require_once('CloudNCo_APIRequest.php') ;
require_once('CloudNCo_Callback.php') ;
require_once('CloudNCo_Variables.php') ;
require_once('CloudNCo_Application.php') ;
require_once('CloudNCo_Cookie.php') ;
require_once('CloudNCo_Subscription.php') ;
require_once('CloudNCo_Credits.php') ;
require_once('CloudNCo_SubUsers.php') ;
require_once('CloudNCo_Utils.php') ;
require_once('CloudNCo_Config.php') ;
require_once('CloudNCo_User.php') ;
require_once('CloudNCo_TemplateEngine.php') ;
require_once('CloudNCo_TemplateEnginePlugin.php') ;
require_once('CloudNCo_LinkGATagger.php') ;

/**
 * Base object to integrate CloudNCo API
 * 
 * Attributes:
 * 
 * 
 * (start generated)
 * application - (object) <CloudNCo_Application> object (Mandatory)
 * 
 * config - (object) <CloudNCo_Config> object (Mandatory)
 * 
 * cookie - (object) <CloudNCo_Cookie> object (Mandatory)
 * 
 * user - (object) <CloudNCo_User> object (Mandatory)
 * 
 * utils - (object) <CloudNCo_Utils> object (Mandatory)
 * 
 * callback - (object) <CloudNCo_Callback> object (Mandatory)
 * 
 * (end)
 * 
 */
class CloudNCo extends CloudNCo_Object {
	
	private static $instance = null ;
	
	private static $landingElement ;
		
	static function init ( $account, $privateKey, $debug = false ) {
		
		if (!is_null(self::$instance)) {
			return self::$instance ;
		}
		
		self::$instance = new CloudNCo () ;
		
		self::$instance->setAttributes(array(
			new CloudNCo_Attribute('application', CloudNCo_Attribute::OBJECT_ATTR, true),
			new CloudNCo_Attribute('config', CloudNCo_Attribute::OBJECT_ATTR, true),
			new CloudNCo_Attribute('cookie', CloudNCo_Attribute::OBJECT_ATTR, true),
			new CloudNCo_Attribute('user', CloudNCo_Attribute::OBJECT_ATTR, true),
			new CloudNCo_Attribute('utils', CloudNCo_Attribute::OBJECT_ATTR, true),
			new CloudNCo_Attribute('callback', CloudNCo_Attribute::OBJECT_ATTR, true)
		));
		
		self::$instance->application = new CloudNCo_Application () ;
		
		self::$instance->application->name = $account ;
		self::$instance->application->setPrivateKey($privateKey) ;
		
		self::$instance->cookie = new CloudNCo_Cookie ( $account ) ;
		self::$instance->user = new CloudNCo_User () ;
		self::$instance->utils = new CloudNCo_Utils () ;
		self::$instance->config = new CloudNCo_Config () ;
		self::$instance->callback = new CloudNCo_Callback () ;
		
		self::$instance->config->set('mode', 'production') ;
		self::$instance->config->set('URL', self::$instance->utils->getURL()) ;
		self::$instance->config->set('accountURL', self::$instance->utils->getAccountURL()) ;
		self::$instance->config->set('widgetURL', self::$instance->config->get('accountURL') . 'assets/js/widget.js') ;
		
		
		if ( strpos( self::$instance->config->get('URL') , 'localhost' ) === false ) {
			self::$instance->application->api_url = 'http://account.'.$account.'.com/api/init/'.$account.'.json?id='.self::$instance->cookie->getID() ;
			self::$instance->application->bind ( 'application' ) ;
			self::$instance->user->bind('user') ;
			self::$instance->user->subscription->bind('subscription') ;
			self::$instance->user->subscription->credits->bind('credits') ;
			self::$instance->application->execute () ;
		}
		
		// Temporary : get element
		if ( isset($_GET) && array_key_exists('cnc_el', $_GET) ) {
			$request = new CloudNCo_APIRequest () ;
			$request->api_url = self::$instance->utils->getAccountURL().'api/element/picknews/'.$_GET['cnc_el'].'.json' ;
			$response = $request->execute() ;
			if ( $response !== false && array_key_exists('element', $response) )
			{
				self::$landingElement = $response['element'] ;
			}
		}
		
		return self::$instance ;
	}
		
	
	/**
	 * Get the API application object
	 *
	 * @return CloudNCo_Application Returns the CloudNCo_Config instance used by the CloudNCo API 
	 */
	static function application (){
		return self::$instance->application;
	}
	
		
	
	/**
	 * Get the API config object
	 *
	 * @return CloudNCo_Config Returns the CloudNCo_Config instance used by the CloudNCo API 
	 */
	static function config (){
		return self::$instance->config;
	}
	
	/**
	 * Get the API user object
	 *
	 * @return CloudNCo_User Returns the CloudNCo_Config instance used by the CloudNCo API 
	 */
	static function user (){
		return self::$instance->user;
	}
	
	
	/**
	 * Get the API utils object
	 *
	 * @return CloudNCo_Utils Returns the CloudNCo_Utils instance used by the CloudNCo API 
	 */
	static function utils () {
		return self::$instance->utils;
	}
	
	
	/**
	 * Get the API callback object
	 *
	 * @return CloudNCo_Callback Returns the CloudNCo_Callback instance used by the CloudNCo API 
	 */
	static function callback () {
		return self::$instance->callback;
	}
	
	
	/**
	 * [DEPRECATED] Get the API session object
	 * 
	 * This function is deprecated, you should not use it anymore
	 *
	 * @return CloudNCo_Session Returns the CloudNCo_Session instance used by the CloudNCo API 
	 */
	static function session () {
		return self::$instance->session ;
	}
	
	
	/**
	 * [DEPRECATED] Get the API security object
	 * 
	 * This function is deprecated, you should not use it anymore
	 *
	 * @return NULL Returns NULL
	 */
	static function security () {
		return null ;
	}
	
}

?>
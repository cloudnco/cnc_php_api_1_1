<?php

/**
 * Class: CloudNCo_Config
 *
 * Config is a little utility to manage CloudNCo API configuration.
 *
 * 
 *
 * Access through CloudNCo:
 * (start code)
 *		<?php
 *			CloudNCo::config()
 *		?>
 * (end)
 *
 * Example:
 * (start code)
 *		<?php
 *
 *			// Set a value to config
 *			CloudNCo::config()->set('publicKey', 'my_public_key');
 *
 *			// Get a value
 *			echo CloudNCo::config()->get('publicKey');
 *			// > my_public_key
 *
 *		?>
 * (end)
 *
 * Available config variables by default:
 *
 * The following config values are available for use in your application.
 * (start generated)
 * account_url - (boolean) No description (Mandatory)
 * 
 * widget_url - (boolean) No description (Mandatory)
 * 
 * (end)
 *
 */
class CloudNCo_Config extends CloudNCo_Object {
	
	
	
	public function __construct() {
		$this->setAttributes(array(
			new CloudNCo_Attribute('account_url', CloudNCo_Attribute::BOOLEAN_ATTR, true),
			new CloudNCo_Attribute('widget_url', CloudNCo_Attribute::BOOLEAN_ATTR, true),
		));
	}

	/**
	 * Set a configuration value
	 *
	 * @static
	 * @param string $identifier Identifier ot the new configuration value
	 * @param mixed $value New configuration value
	 */
	function set ( $identifier, $value )
	{
		$this->data[$identifier] = $value ;
	}

	/**
	 * Get a configuration value
	 *
	 * @static
	 * @param string $identifier Identifier ot the configuration value
	 * @return mixed The configuration value if exists, NULL otherwise
	 */
	function get ( $identifier )
	{
		if ( array_key_exists( $identifier, $this->data ) )
		{
			return $this->data[$identifier] ;
		}

		return null ;
	}

	/**
	 * Check if a configuration value is set, given its identifier
	 *
	 * @static
	 * @param string $identifier Identifier ot the configuration value
	 * @return boolean True if the value is set, false otherwise
	 */
	function has ( $identifier )
	{
		return array_key_exists( $identifier, $this->data ) ;
	}

}

?>
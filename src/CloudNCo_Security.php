<?php

/**
 * Class: CloudNCo_Security
 *
 * Offers some methods to securize API transactions.
 *
 * Access:
 * (start code)
 *		<?php
 *			CloudNCo::security()
 *		?>
 * (end)
 */
class CloudNCo_Security extends CloudNCo_Base {


	/**
	 * Check an API key
	 *
	 * Requires the security credentials to be set in configuration.
	 *
	 * @see CloudNCo_Config
	 * @param string $public API Public Key used for transaction
	 * @param string $hashed API Key to be checked
	 * @return True if key is valid, false otherwise
	 */
	public function isValidKey ( $public, $hashed )
	{
		if ( CloudNCo::config()->has( 'privateKey' ) == false )
		{
			return false ;
		}

		if ( $hashed == sha1($public . CloudNCo::config()->get('privateKey') ))
		{
			return true ;
		}

		return false ;
	}

	/**
	 * Check $_POST content for callbacks
	 */

	public function getCallbackUser ()
	{
		if ( !empty ( $_POST ) )
		{
			if ( !array_key_exists('public', $_POST) )
			{
				return false ;
			}

			if ( !array_key_exists('hash', $_POST) )
			{
				return false ;
			}

			if ( !$this->isValidKey($_POST['public'], $_POST['hash']) )
			{
				return false ;
			}

			if ( !array_key_exists('user', $_POST) )
			{
				return false ;
			}

			$content = $_POST['user'] ;

			if ( strpos($content, '\\"') !== false )
			{
				$content = str_replace('\\"', '"', $content);
			}

			return unserialize($content) ;
		}

		return false ;
	}

}

?>

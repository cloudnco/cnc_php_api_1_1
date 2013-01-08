<?php

/**
 * Class: CloudNCo_Utils
 *
 * This class contains some functions used throughout the Next User API.
 *
 * You can create instances of this class or access it through:
 * (start code)
 *		<?php
 *			CloudNCo::utils()
 *		?>
 * (end)
 *
 * Example of use:
 * (start code)
 *		<?php
 *			echo CloudNCo::utils()->getUpgradeLink () ;
 *		?>
 * (end)
 *
 */
class CloudNCo_Utils extends CloudNCo_Object {
	

	/**
	 * Get an upgrade link
	 *
	 * An upgrade link is an HTML link that point to the upgrade process of Next User.
	 * In case the plan currently choosed by the user is the most expensive plan, this method returns an empy string.
	 *
	 * @param string $text Text to show in the link
	 * @param string $class A CSS class to apply on the link
	 * @return string An HTML link or an empty string depending on current plan choosed by the user
	 */
	function getUpgradeLink ( $text = 'Upgrade to pro!' , $class = '' )
	{
		if ( CloudNCo::user()->isFreeSubscriber () || CloudNCo::user()->isNotSubscriber () )
		{
			return '<a href="' . $this->getUpgradeURL() . '" class="' . $class . '">' . $text . '</a>' ;
		}

		return '';
	}

	/**
	 * Returns upgrade account URL
	 *
	 * @return string The URL of upgrade subscription on Next User application
	 */
	function getUpgradeURL ()
	{
		return str_replace('http://', 'https://', CloudNCo::config()->get('accountURL') ) . 'checkout/upgrade' ;
	}

	/**
	 * Returns login account URL
	 *
	 * @return string The URL of upgrade subscription on Next User application
	 */
	function getLoginURL ( $redirect = false )
	{
		return CloudNCo::config()->get('accountURL') . 'subscriber/signin'
			. ($redirect !== false && is_string($redirect) ? '?redirect=' . urlencode($redirect) : '');
	}

	/**
	 * Returns subscribe URL
	 *
	 * @return string The URL of first subscription on Next User application
	 */
	function getSubscribeURL ()
	{
		return str_replace('http://', 'https://', CloudNCo::config()->get('accountURL') )  . 'checkout/index/direct' ;
	}


	/**
	 * Returns a javascript snippet to insert between <head> and </head>, used
	 * to fire events to apptegic
	 *
	 * @return string A JS snippet to insert into the web application
	 */
	function getJSSnippet () {
		return "" ;
	}


	/**
	 * Get the application URL
	 *
	 * @return string
	 */
	function getURL ()
	{

		if ( array_key_exists ( 'HTTPS', $_SERVER ) && strtolower ( $_SERVER['HTTPS'] ) == 'on' )
		{
			$prefix = 'https://' ;
		// CloudFlare support
		} else if ( array_key_exists ( 'HTTP_CF_VISITOR', $_SERVER ) )
		{
			$visitor = json_decode($_SERVER['HTTP_CF_VISITOR']) ;
			$prefix = $visitor->scheme .'://' ;
		} else {
			$prefix = 'http://' ;
		}
		if (array_key_exists('HTTP_HOST', $_SERVER) ) {
			$url = $_SERVER['HTTP_HOST'] . '/' . $_SERVER['SCRIPT_NAME'] ;
		} else {
			$url = @$_SERVER['SCRIPT_NAME'] ;
		}
		$url = preg_split("/\//", $url, -1) ;

		array_pop($url) ;

		$res = array () ;
		foreach ( $url as &$item )
		{
			if ( !empty ( $item ) || $item === 0 || $item == '0' )
			{
				$res[] = $item ;
			}
		}
		$url = implode( '/' , $res) . '/' ;

		return $prefix . $url ;
	}

	/**
	 * Get the account URL
	 *
	 * The account URL is
	 *
	 * @return string
	 */
	function getAccountURL ()
	{
		$url = CloudNCo::config()->get('URL') ;
		return $url . 'account/' ;
	}

	function getTrialURL ()
	{
		return str_replace('http://', 'https://', CloudNCo::config()->get('accountURL') )  . 'checkout/trial';
	}

	/**
	 * Get the Next User authentication widget HTML code
	 *
	 * @return string The HTML code of the Next User authentication widget
	 */
	function getWidgetCode ()
	{
		return '<div id="nextauth-widget" style="float: right; margin-top: 10px;"></div>' .
				'<script type="text/javascript" src="' . CloudNCo::config()->get('widgetURL') .'"></script>' ;
	}

	/**
	 * Get the field name of email field in registration process.
	 *
	 * @return string The field name used for the email field in the registration process
	 */
	function getSubscribeEmailFieldName ()
	{
		return 'usercore/register/email' ;
	}

	/**
	 * Get the field name of password field in registration process.
	 *
	 * @return string The field name used for the password field in the registration process
	 */
	function getSubscribePasswordFieldName ()
	{
		return 'usercore/register/password' ;
	}
}

?>
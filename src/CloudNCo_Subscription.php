<?php

/**
 * User subscription to the application
 *
 * (start generated)
 * subscriber - (boolean) Does the user have a free or paying subscription  to the application (Mandatory, default value: false)
 * 
 * plan_code - (string) Current plan code, "none" when no subscription (Mandatory, default value: "none")
 * 
 * plan_free - (boolean) Is current plan free (Mandatory, default value: true)
 * 
 * trial_mode - (boolean) Is in trial special mode (Mandatory, default value: false)
 * 
 * had_trial - (boolean) Did user have a trial yet (Mandatory, default value: false)
 * 
 * status - (string) Status of the user: "new", "upgraded", "downgraded", "normal", "anonymous" (Mandatory, default value: "anonymous")
 * 
 * credits - (object) No description (Mandatory, default value: new CloudNCo_Credits()
 * 
 * (end)
 */
class CloudNCo_Subscription extends CloudNCo_Object {

	function __construct ( $values = array () ) {
		$this->setAttributes(array(
			new CloudNCo_Attribute('subscriber', CloudNCo_Attribute::BOOLEAN_ATTR, true, false),
			new CloudNCo_Attribute('plan_code', CloudNCo_Attribute::STRING_ATTR, true, 'none'),
			new CloudNCo_Attribute('plan_free', CloudNCo_Attribute::BOOLEAN_ATTR, true, true),
			new CloudNCo_Attribute('trial_mode', CloudNCo_Attribute::BOOLEAN_ATTR, true, false),
			new CloudNCo_Attribute('had_trial', CloudNCo_Attribute::BOOLEAN_ATTR, true, false),
			new CloudNCo_Attribute('status', CloudNCo_Attribute::STRING_ATTR, true, 'anonymous'),
			new CloudNCo_Attribute('credits', CloudNCo_Attribute::OBJECT_ATTR, true, new CloudNCo_Credits())
		)) ;

		$this->setAll($values);
	}

}

?>
<?php

/**
 * Base attribute class
 */
class CloudNCo_Attribute extends CloudNCo_Base {

	const STRING_ATTR = 'string' ;

	const INT_ATTR = 'int' ;

	const FLOAT_ATTR = 'float' ;

	const BOOLEAN_ATTR = 'boolean' ;

	const TIMESTAMP_ATTR = 'timestamp' ;

	const OBJECT_ATTR = 'object' ;

	const ARRAY_ATTR = 'array' ;


	/**
	 * Test if a value is valid given its type
	 *
	 * @param string $type Type of attribute
	 * @param string $value Value to test
	 * @return boolean True of value is valid, false othewise
	 */
	public static function check ( $type, $value ) {
		switch ($type) {
			case self::STRING_ATTR:
				return is_string($value);
			case self::INT_ATTR:
				return is_int($value);
			case self::FLOAT_ATTR:
				return is_float($value);
			case self::BOOLEAN_ATTR:
				return $value === false || $value === true;
			case self::TIMESTAMP_ATTR:
				return ($timestamp <= PHP_INT_MAX)
				&& ($timestamp >= ~PHP_INT_MAX)
				&& (!strtotime($timestamp));
			case self::OBJECT_ATTR:
				return is_object($value) && is_subclass_of($value, 'CloudNCo_Base');
			case self::ARRAY_ATTR:
				return is_array($value);
		}

		return false;
	}


	protected $name;
	protected $mandatory = false;
	protected $default = '';
	protected $type;



	/**
	 * Creates a new attributes
	 *
	 * @param string $name Name of the attribute
	 * @param string $type Type of the attribute value. May be one of these: string, int, float, boolean, timestamp, object (CloudNCo_Base subclass only)
	 * @param boolean $mandatory Is this attribute mandatory
	 * @param mixed $default Default value of this attribute
	 */
	public function __construct($name, $type = 'string', $mandatory = true, $default = null ) {
		$this->name = $name;
		$this->type = $type;
		$this->mandatory = $mandatory === true ? true : false;
		if ( !is_null($default) && !$this->checkType($default) ) {
			throw new CloudNCo_Exception('Default value ('.$default.') for attribute '. $name . ' is not typed as ' . $type );
		}
		$this->default = $default;
	}

	/**
	 * Check if value is correctly typed
	 *
	 * @param mixed $value Value to test
	 * @return boolean True if type is valid, false otherwise
	 */
	public function checkType($value) {
		return self::check($this->getType(), $value) ;
	}

	/**
	 * Get the name of the attribute
	 *
	 * @return string Name of the attribute
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Is the attribute mandatory
	 *
	 * @return boolean True if attribute is mandatory, false otherwise
	 */
	public function isMandatory() {
		return $this->mandatory;
	}


	/**
	 * Is the attribute mandatory
	 * 
	 * Alias of <CloudNCo_Attribute::isMandatory>
	 *
	 * @return boolean True if attribute is mandatory, false otherwise
	 */
	public function getMandatory() {
		return $this->mandatory;
	}

	/**
	 * Check if attribute has a default value
	 *
	 * @return string Default value of the attribute
	 */
	public function hasDefault() {
		return !is_null( $this->default );
	}

	/**
	 * Get default value of attribute
	 *
	 * @return string Default value of the attribute
	 */
	public function getDefault() {
		return $this->default;
	}

	/**
	 * Get type of the attribute
	 *
	 * @return string Type of attribute
	 */
	public function getType() {
		return $this->type;
	}



}

?>
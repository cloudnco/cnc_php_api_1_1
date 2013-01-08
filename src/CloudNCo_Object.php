<?php

/**
 * Base class for all CloudNCo data objects
 */
class CloudNCo_Object extends CloudNCo_Base {

	private $attributes = array () ;

	private $writable = array () ;

	protected $data = array () ;

	/**
	 * Get attribute description
	 *
	 * @param string $key Name of attribute to get
	 * @return CloudNCo_Attribute The attribute object
	 * @throws CloudNCo_Exception Throwed if attribute hasn't been defined
	 */
	public function getAttribute ( $key ) {
		if (array_key_exists($key, $this->attributes) ) {
			return $this->attributes[$key] ;
		}

		throw new CloudNCo_Exception('Attribute '.$key .' does not exists in '.get_class($this));
	}

	/**
	 * Check if an attribute exists
	 *
	 * @param string $key Name of the attribute to check
	 * @return boolean True if attribute exist, false otherwise
	 */
	public function hasAttribute ( $key ) {
		return array_key_exists($key, $this->attributes) ;
	}

	/**
	 * [Protected] Add an attribute
	 *
	 * @param CloudNCo_Attribute $attribute Attribute to add
	 * @throws CloudNCo_Exception Throwed if given attribute is not a CloudNCo_Attribute instance
	 */
	protected function setAttribute ( $attribute ) {
		if ( is_object($attribute) && get_class($attribute) === 'CloudNCo_Attribute' ) {
			$this->attributes[$attribute->getName()] = $attribute ;
			if ( $attribute->hasDefault() ) {
				$this->data[$attribute->getName()] = $attribute->getDefault() ;
			}
		} else {
			throw new CloudNCo_Exception('Trying to set attribute but given argument is not an attribute ('.get_class($this).')');
		}
	}

	/**
	 * [Protected] Add a bunch of attributes
	 *
	 * @param Array $arr An array of attributes
	 */
	protected function setAttributes ( $arr = array () ) {
		foreach ( $arr as $attribute ) {
			$this->setAttribute($attribute);
		}
	}

	/**
	 *
	 * @param string $key
	 */
	protected function setWritable ( $key ) {
		if ( is_string($key) && $this->hasAttribute($key) ) {
			if ( !in_array($this->writable) ) {
				$this->writable[] = $key ;
			}
		} else {
			throw new CloudNCo_Exception('Trying to set writable an unexisting attributes') ;
		}
	}

	/**
	 * Get an attribute value
	 *
	 * @param string $key Name of attribute
	 * @return Value of attribute if set, null otherwise
	 * @throws CloudNCo_Exception Throwed if attribute does not exist
	 */
	public function __get ( $key ) {
		if ( !array_key_exists($key, $this->attributes) ) {
			throw new CloudNCo_Exception('Attribute '.$key .' does not exists in '.get_class($this));
		}

		if ( !array_key_exists($key, $this->data) ) {
			return null ;
		}

		return $this->data[$key] ;
	}

	/**
	 * Set an attribute value
	 *
	 * @param string $key Name of attribute
	 * @param mixed $value Value to set for the attribute
	 * @throws CloudNCo_Exception Throwed if attribute does not exist
	 */
	public function __set ( $key, $value ) {
		if ( !array_key_exists($key, $this->attributes) ) {
			throw new CloudNCo_Exception('Attribute '.$key .' does not exists in '.get_class($this));
		}
		if ( !is_null($value) && !$this->attributes[$key]->checkType($value) ) {
			if (is_object($value) ) {
				$str_value = get_class($value) ;
			} else if ( is_array($value) ) {
				$str_value = 'Array('.implode(',', $value) .')';
			} else if (is_bool($value) ) {
				$str_value = $value ? 'true' : 'false' ;
			} else {
				$str_value = $value ;
			}
			throw new CloudNCo_Exception('Value "'.$str_value.'" for attribute '.$key .' should be typed as '.$this->attributes[$key]->getType().' ('.get_class($this).')' );
		} else if ( is_null($value) && $this->attributes[$key]->isMandatory() ) {
			throw new CloudNCo_Exception('Value for attribute '.$key .' is null but mandatory ('.get_class($this).')' );
		}
		
		$this->data[$key] = $value ;
	}

	/**
	 * Set a bunch of attributes values
	 *
	 * @param Array $values Array of key/values
	 */
	public function setAll ( $values ) {
		foreach ( $values as $key => $val ) {
			$this->$key = $val ;
		}
	}


	/**
	 * Check if value is set for a given attribute
	 *
	 * @param string $key Name of attribute
	 * @return boolean True if attribute value is set, false otherwise
	 */
	public function has ( $key ) {
		return array_key_exists($key, $this->attributes)
				&& array_key_exists($key, $this->data)
				&& !is_null($this->data[$key]) ;
	}
}


?>
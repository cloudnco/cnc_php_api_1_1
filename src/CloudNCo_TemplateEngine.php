<?php

class CloudNCo_TemplateEngine {

	const ELEMENT_TOKEN = '@';
	const VARIABLE_TOKEN = '=';
	const COMMENT_TOKEN = '#';
	const LIST_TOKEN = '&';
	const MAX_RECURSIVITY = 32 ;

	private $vars = array();
	private $elements = array();
	private $plugins = array () ;
	private $recursion = 0 ;

	function render($tpl) {
		$this->recursion = 0 ;

		$res = $this->_render($tpl);

		if ( !empty($this->plugins) ) {
			foreach ( $this->plugins as $plugin ) {
				$res = $plugin->render($res) ;
			}
		}

		return $res ;
	}

	private function _render($tpl) {

		$this->recursion ++ ;

		if ( $this->recursion > self::MAX_RECURSIVITY ) {
			throw new Exception('BeardTemplateEngine :: Max recursivity value reached') ;
		}

		$len = strlen($tpl);
		$i = 0;
		$result = '';
		$subpart = null;

		for ($i; $i < $len; $i++) {

			$char = substr($tpl, $i, 1);
			$nextChar = substr($tpl, $i + 1, 1);

			if (!is_null($subpart)) {

				if ($char == '%' && $nextChar == '>') {
					$result .= $this->renderSubPart($subpart);
					$subpart = null;
					$i++;
					continue;
				}

				$subpart .= $char;
			} else {

				if ($char == '<' && $nextChar == '%') {
					$subpart = '';
					$i++;
					continue;
				}

				$result .= $char;
			}
		}

		return $result;
	}

	function renderSubPart($subpart) {
		if ($subpart == '') {
			return '';
		}

		$token = substr($subpart, 0, 1);

		if ($token == self::COMMENT_TOKEN) {
			return '' ;
		}

		$id = trim(substr($subpart, 1));
		$default = null;

		if ($token == self::ELEMENT_TOKEN) {
			return $this->getElement($id);
		}

		if ($token == self::VARIABLE_TOKEN) {

			$separator = strpos($id, '|');
			if ($separator !== false) {
				$default = trim(substr($id, $separator + 1));
				$id = trim(substr($id, 0, $separator - 1));
			}

			return $this->getVariable($id, $default);
		}

		return 'Token ' . $token . ' unknown';
	}

	function setVariable($key, $val) {
		$this->vars[$key] = $val;
	}

	function getVariable($key, $default = null) {
		if (array_key_exists($key, $this->vars)) {
			return $this->vars[$key];
		}

		if (!is_null($default)) {
			return $default;
		}

		return 'Variable ' . $key . ' not found, no default given';
	}

	function setElement($key, $val) {
		$this->elements[$key] = $val;
	}

	function getElement($key) {
		if (array_key_exists($key, $this->elements)) {
			return $this->render($this->elements[$key]);
		}

		return 'Element ' . $key . ' not found';
	}

	function addPlugin ( $plugin ) {
		$name = get_class($plugin) ;
		if ( !array_key_exists($name, $this->plugins) ) {
			$this->plugins[$name] = $plugin ;
		}
	}

	function removePlugin ( $plugin ) {
		$name = get_class($plugin) ;
		if (array_key_exists($name, $this->plugins)) {
			unset($this->plugins[$name]) ;
		}
	}

	function removePluginByName ( $pluginClassName ) {
		if (array_key_exists($pluginClassName, $this->plugins)) {
			unset($this->plugins[$pluginClassName]) ;
		}
	}

	function getPlugin ( $pluginClassName ) {
		if (array_key_exists($pluginClassName, $this->plugins)) {
			return $this->plugins[$pluginClassName] ;
		}
	}
}

?>
<?php

class CloudNCo_LinkGATagger extends CloudNCo_TemplateEnginePlugin {

	private $medium ;

	private $source ;

	public function render ( $str ) {
		if  ( is_null($this->medium) || is_null($this->source) || $this->medium == '' || $this->source == '' ) {
			return $str ;
		}

		$patterns = array(
			'/<a([^>]*)href="(?!mailto)([^\\s#"?]*)((#[^"]*)|)"([^>]*)class="([^"]*)cnc_track_([a-z_]*)([^"]*)"([^>]*)>/im',
			'/<a([^>]*)href="(?!mailto)([^\\s#"]*)((#[^"]*)|)"([^>]*)class="([^"]*)cnc_track_([a-z_]*)([^"]*)"([^>]*)>/im' ,
			'/(?!")(http|https):\/\/([a-zA-Z0-9\\.\\-]*?)([^\\s]*)cnc_track_([^\\s&#])([^\\s]*)/im'
		) ;

		$replacements = array(
			'<a$1href="$2?utm_medium='.$this->medium.'&utm_source='.$this->source.'&utm_campain=$7$4"$5class="$6$8"$9>',
			'<a$1href="$2&utm_medium='.$this->medium.'&utm_source='.$this->source.'&utm_campain=$7$4"$5class="$6$8"$9>' ,
			'$1://$2$3utm_medium="+medium+"&utm_source="+source+"&utm_campain=$4$5'
		) ;

		return preg_replace($patterns, $replacements, $str) ;
	}

	public function getMedium() {
		return $this->medium;
	}

	public function setMedium($medium) {
		$this->medium = $medium;
	}

	public function getSource() {
		return $this->source;
	}

	public function setSource($source) {
		$this->source = $source;
	}


}

?>

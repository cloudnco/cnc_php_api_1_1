<?php

class CloudNCo_Exception extends Exception {

	public function __construct($message, $code = 100, $previous = null) {
		parent::__construct($message, $code, $previous);
	}

	public static function handle($e) {

		echo '<pre>';
		echo '[EXCEPTION] ' . $e->getMessage() . "\n\n";
		echo 'Exception location: <strong>' . $e->getFile() . '</strong> / Line: <strong>' . $e->getLine() . "</strong>\n\n";


		$trace = $e->getTrace();

		$c = count($trace);

		echo 'Call stask (' . $c . '):' . "\n";

		$class = null;

		$stack = '';

		$leading = "%01d";

		if ($c > 100) {
			$leading = "%03d";
		} else if ($c > 10) {
			$leading = "%02d";
		}

		$ind = 0;

		foreach ($trace as $call) {
			$c--;


			$str = '[' . sprintf($leading, $c) . ']   ';

			for ($ind2 = 0; $ind2 < $ind; $ind2++) {
				$str .= ' ';
			}

			$str .= '<strong>' . ( array_key_exists('class', $call) ? $call['class'] . ' ' . $call['type'] . ' ' : '' );
			$str .= $call['function'] . '</strong>';
			$str .= array_key_exists('file', $call) ? ' (' . $call['file'] . ':' . $call['line'] . ')' : ' (?)';

			$stack .= $str . "\n";

			$ind++;

			if ($ind == 6)
				$ind = 0;
		}

		echo trim($stack, "\n");

		echo '</pre>';
	}

}

set_exception_handler(array('CloudNCo_Exception', 'handle'));
?>
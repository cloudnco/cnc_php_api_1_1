<?php

$path = dirname(dirname(__FILE__)).'/src/';
$backup = dirname(__FILE__).'/backup/' ;

// All attributes, all objects
$codex = array();
$codexPath = dirname(dirname(dirname(__FILE__))).'/cloudnco-docs/ALL_ATTRIBUTES.md';

header('Content-Type: text/plain') ;
function __log ( $msg ) {
	echo $msg . "\n" ;
	
}

function getExistingAttribute ( $commentLine ) {
	preg_match_all('/\s*\*\s*([a-zA-Z_0-9]{1,})\s-\s\(([a-z]{1,})\)([^\(]*)/is', $commentLine, $res); 
	$attribute = array () ;
	if ( !empty($res[1]) && !empty($res[1][0]) ) {
		$attribute['name'] = $res[1][0] ;
		$attribute['comment'] = trim($res[3][0]) ;
		return $attribute ;
	}
	
	return null ;
}
function getCodeAttribute ( $codeLine ) {
	$codeLine = trim($codeLine);
	preg_match_all('/CloudNCo_Attribute\s*\(([^\)]*)/ims', $codeLine, $res);
	$attribute = array () ;
	if ( !empty($res[1]) && !empty($res[1][0]) ) {
		$attr = explode(',',$res[1][0]);
		$attribute['name'] = trim($attr[0], ' \'\"');
		preg_match_all('/CloudNCo_Attribute::([A-Z]*)_ATTR/ims', $attr[1], $res); 
		$attribute['type'] = strtolower($res[1][0]) ;
		$attribute['mandatory'] = trim($attr[2]) == 'false' ? false : true ;
		$attribute['comment'] = 'No description' ;
		// 3 or 4
		if ( count($attr) == 4) {
			$attribute['default'] = trim($attr[3], ' \'\"'); ;
		} else {
			$attribute['default'] = null ;
		}
		
		
		return $attribute ;
	}
	
	return null ;
}

function writeAttribute ( $attribute ) {
	$res = ' * ' . $attribute['name'] .' - (' . $attribute['type'] . ') '.$attribute['comment'] ;
	
	if ( $attribute['mandatory'] || !is_null($attribute['default']) ) {
		if ( $attribute['mandatory'] && is_null($attribute['default']) ) {
			$res .=' (Mandatory)' ;
		} else 
		if ( !$attribute['mandatory'] && !is_null($attribute['default']) ) {
			$res .=' (Default value: '.$attribute['default'].')' ;
		} else {
			$res .=' (Mandatory, default value: '.($attribute['type'] == 'string' ? '"'.$attribute['default'].'"':$attribute['default']).')';
		}
	}
	
	return $res."\n * \n" ;
}

function writeAttributes ( $attributes ) {
	$res = '' ;
	foreach ( $attributes as $a ) {
		$res.=writeAttribute($a) ;
	}
	return $res ;
}

function writeMDAttribute ( $attribute ) {
	
	$res = "- __" . $attribute['name'] .'__ `' . $attribute['type'] . '`  - '.$attribute['comment'] ;
	
	if ( $attribute['mandatory'] || !is_null($attribute['default']) ) {
		if ( $attribute['mandatory'] && is_null($attribute['default']) ) {
			$res .=' - *Mandatory*' ;
		} else 
		if ( !$attribute['mandatory'] && !is_null($attribute['default']) ) {
			$res .='Default value: '.$attribute['default'].'' ;
		} else {
			$res .=' - *Mandatory*, default value: `'.($attribute['type'] == 'string' ? '"'.$attribute['default'].'"':$attribute['default']).'`';
		}
	}
	
	return $res."\n\n" ;
}

function writeMDAttributes ( $attributes ) {
	$res = '' ;
	foreach ( $attributes as $a ) {
		$res.=writeMDAttribute($a) ;
	}
	return $res ;
}

function process ( $path , $file ) {
	__log('Process '.$file) ;
	
	$content = file_get_contents($path.$file) ;
	
	preg_match('/\(start generated\)(.*)\(end\)/ism', $content, $matches) ;
	
	$existing_attributes = array () ;
	if ( !empty($matches) && !empty($matches[1]) ){
		$existing_comments = $matches[1] ;

		$lines = explode("\n", $matches[1]);
		foreach ( $lines as $line ) {
			$attribute = getExistingAttribute ($line) ;
			if (!is_null($attribute) ) {
				$existing_attributes[$attribute['name']] = $attribute ;
			}
		}
	}

	if ( strpos($content, '->setAttributes') === false ) {
		return ;
	}
	
	preg_match('/\-\>setAttributes[^\n]{2,}\n\s*([^;]*)\s*\)\s*\)\s*;/ism', $content, $matches) ;
	
	$attributes = array () ;
	if ( !empty($matches) && !empty($matches[1]) ){
		$existing_comments = $matches[1] ;

		$lines = explode("\n", $matches[1]);
		foreach ( $lines as $line ) {
			$attribute = getCodeAttribute ($line) ;
			if (!is_null($attribute) ) {
				$attributes[$attribute['name']] = $attribute ;
			}
		}
	} else {
		return ;
	}
	
	
	foreach ( $attributes as $attrName => &$attr ) {
		if (array_key_exists($attrName, $existing_attributes) ) {
			$attr['comment'] = $existing_attributes[$attrName]['comment'];
		}
	}
	
	global $backup , $codex ;
	
	$class = str_replace('.php','',$file);
	
	$codex[$class] = $attributes;
	__log('Processing ' .count($attributes).' attributes on '.$class) ;
	
	// Backup
	
	
	if ( strpos($content, '(start generated)') !== false ) {
		copy($path.$file,$backup.$file.'.bak');
		file_put_contents($path.$file,preg_replace('/\\n*\*\s{0,}\(start generated\)(.*)\(end\)/ism', "* (start generated)\n".writeAttributes($attributes)." * (end)", $content) );
	}
	
}


// Get files
if ($handle = opendir($path)) {
    while (false !== ($entry = readdir($handle))) {
        if ($entry != "." && $entry != ".." && $entry != ".DS_Store") {
            process ($path, $entry ) ;
        }
    }
    closedir($handle);
}


$codexStr = "# API Attributes Codex - v1.1" ;
$codexStr .= "\n\n" ;

foreach($codex as $class=>$attributes) {
	$codexStr .= "## ".str_replace('CloudNCo_', '', $class);
	$codexStr .= "\n\n" ;
	$codexStr .= "Class documentation: [PHP](php-api-v1-1/files/".$class."-php.html)";
	$codexStr .= "\n\n" ;
	$codexStr .= writeMDAttributes($attributes);
	$codexStr .= "\n\n" ;
}
print_r($codexStr);
file_put_contents($codexPath,$codexStr);
?>
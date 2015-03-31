<?php
function name2fn($name) {
	$a = mb_strtolower($name);
	$a = preg_replace(array(
			'{\b(la|de|del|y)\b}u',
			'{\.}u',
			'{^\s+}u',
			'{\s+}u',
			'{á}u',
			'{é}u',
			'{í}u',
			'{ó}u',
			'{ú|ü}u',
			'{ñ}u',
		), array(
			'',
			'',
			'',
			'-',
			'a',
			'e',
			'i',
			'o',
			'u',
			'n',
		), $a );
	return $a;
}

$exceptions = array(
	'guajira'=>'lgu',
	'guainia'=>'gui',
	'guaviare'=>'guv',
	'san-andres-providencia'=>'sap',
	'archipielago-san-andres' => 'sap',
	'valle-cauca'=>'vdc',
	'santafe-bogota' => 'bog',
);
function fn2code($fn) {
	global $exceptions;
	if(isset($exceptions[$fn])) return $exceptions[$fn];
	return substr($fn,0,3);
}

function name2code($name) {
	return fn2code(name2fn($name));
}

function retype(&$a,$i) {
	if(empty($a)) $a = null;
	elseif(preg_match('{^"[^"]*"$}u',$a)) $a = trim($a,'"');
	elseif(is_int($a)) $a = (int)$a;
	elseif(is_numeric($a)) $a = (float)$a;
}

/*function readcsv($fn,$title=true,$skip=0,$id=null) {
	if(is_null($title)) $title=true;
	if(is_null($skip)) $skip=0;
	$t = file_get_contents($fn);
	$lines = explode("\n",$t);
	for($i=0;$i<$skip;$i++)
		array_shift($lines);

	$ans = array();
	if($title) {
		$tline = array_shift($lines);
		$tarray = explode(',',$tline);
		array_walk($tarray,'retype');
		if(is_null($id))
			foreach($lines as $line) {
				$keys = $tarray;
				if(empty($line)) continue;
				$arr = explode(',',$line);
				array_walk($arr,'retype');
				while(count($arr) < count($keys)) {
					$arr[] = null;
				}
				while(count($arr) > count($keys)) {
					$keys[] = count($keys);
				}
				$ans[] = array_combine($keys,$arr);
			}
		else
			foreach($lines as $line) {
				$keys = $tarray;
				if(empty($line)) continue;
				$arr = explode(',',$line);
				array_walk($arr,'retype');
				while(count($arr) < count($keys)) {
					$arr[] = null;
				}
				while(count($arr) > count($keys)) {
					$keys[] = count($keys);
				}
				$ans[$arr[$id]] = array_combine($keys,$arr);
			}
	} else {
		if(is_null($id))
			foreach($lines as $line) {
				if(empty($line)) continue;
				$arr = explode(',',$line);
				array_walk($arr,'retype');
				$ans[] = $arr;
			}
		else
			foreach($lines as $line) {
				if(empty($line)) continue;
				$arr = explode(',',$line);
				array_walk($arr,'retype');
				$ans[$arr[$id]] = $arr;
			}
	}
	return $ans;
}*/

function readcsv($fn,$title=true,$skip=0,$id=null) {
	if(is_null($title)) $title=true;
	if(is_null($skip)) $skip=0;
	$ans = array();
	if (($handle = fopen($fn, "r")) !== FALSE) {
		while (($data = fgetcsv($handle)) !== FALSE) {
			if($skip>0) {
				$skip--;
				continue;
			}
			if($title) {
				$tline = $data;
				$title = false;
				continue;
			}
			if(is_numeric($id)) {
				$key = $data[$id];
			}
			if(isset($tline)) {
				$data = array_combine($tline,$data);
			}
			if(is_null($id))
				$ans[] = $data;
			elseif(is_numeric($id))
				$ans[$key] = $data;
			else
				$ans[$data[$id]] = $data;
		}
		fclose($handle);
	}
	return $ans;
}

$trans = array(
	'intermediario'=>'Intermediario Financiero',
	'municipio'=>'Municipio',
	'linea'=>'Línea',
	'programa'=>'Programa',
	'actividad'=>'Actividad Agro',
	'tipo'=>'Tipo Cartera',
	'productor'=>'Productor',
);
?>

<?php
	if (!isset($class)) { $class = 'WMXI'; }
	require_once(getcwd()."/invoice/$class.php");

	define('WMXI_LOG', 'wmxi.log');

	$wmxi = new $class(getcwd()."/invoice/WMXI.crt", 'UTF-8');

	define('WMID', '915236488902');
	define('PASS', '32233221');
	define('KWMFILE', getcwd()."/invoice/keys/915236488902.kwm");
	if (defined('EKEY') && defined('NKEY')) { $wmkey = array('ekey' => EKEY, 'nkey' => NKEY); }
	elseif (defined('KWMDATA')) { $wmkey = array('pass' => PASS, 'data' => KWMDATA); }
	elseif (defined('KWMFILE')) { $wmkey = array('pass' => PASS, 'file' => KWMFILE); }
	if(isset($wmkey)) { $wmxi->Classic(WMID, $wmkey);}
?>
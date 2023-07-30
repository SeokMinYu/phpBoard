<?php 

function castle_util_htmlencode_lt($msg) {
	while (preg_match("#<#i", $msg))
		$msg = preg_replace("#<#i", "&lt;", $msg);
	return $msg;
}

function castle_util_htmlencode_gt($msg) {
	while (preg_match("#>#i", $msg))
	{
		$msg = preg_replace("#>#i", "&gt;", $msg);
	}
	return $msg;
}

function castle_util_delete_directory_traverse($path) {
	// "/../" -> "/"
	while (preg_match("#/\.\./#i", $path))
		$path = preg_replace("#/\.\./#i", "/", $path);
	
	// "./" -> ""
	while (preg_match("#\./#i", $path))
		$path = preg_replace("#\./#i", "", $path);
	
	// "//" -> "/"
	while (preg_match("#//#i", $path))
		$path = preg_replace("#//#i", "/", $path);
	
	// "\..\" -> "\"
	while (preg_match("/\\\\\.\.\\\\/i", $path))
		$path = preg_replace("/\\\\\.\.\\\\/i", "\\", $path);
	
	// ".\" -> ""
	while (preg_match("/\.\\\\/i", $path))
		$path = preg_replace("/\.\\\\/i", "", $path);
	
	// "\\" -> "\"
	while (preg_match("/\\\\\\\\/", $path))
		$path = preg_replace("/\\\\\\\\/i", "\\", $path);
	
	return $path;
}

function castle_util_remove_crlf($value)
{
	$value = preg_replace("#\r#i", "\\r", $value);
	$value = preg_replace("#\n#i", "\\n", $value);
	return $value;
}
?>
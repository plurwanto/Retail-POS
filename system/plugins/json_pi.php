<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
function jsonEncode($arraydata) {
	require_once("json/json.php");
	$json = new Services_JSON();
	return $json->encode($arraydata);
}
?>
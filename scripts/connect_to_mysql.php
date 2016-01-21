<?php

$dbserver = "localhost";
$dbuser = "pietax10_owner";
$dbpw = "spindell";
$dbdb = "pietax10_pieta";

mysql_connect($dbserver, $dbuser, $dbpw) or die('Could not connect: ' . mysql_error());
mysql_select_db($dbdb) or die ('Could not use pieta : ' . mysql_error());

?>
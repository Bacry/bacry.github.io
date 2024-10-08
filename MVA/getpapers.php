<?php
$ip=$_SERVER['REMOTE_ADDR'];
$paper=$_REQUEST["file"];

header("Content-type: application/pdf");

header("Content-Disposition: attachment; filename=$paper");


readfile("$paper");
exit;
?>
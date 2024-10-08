<?php
$log="ftp/papers/log";
$ip=$_SERVER['REMOTE_ADDR'];
$paper=$_REQUEST["paper"];

header("Content-type: audio/x-mpeg");

header("Content-Disposition: attachment; filename=$paper");

`echo -n "$ip\t$paper\t" >> $log`;
`date +%x%t%T >> $log`;


readfile("ftp/papers/$paper");
exit;
?>
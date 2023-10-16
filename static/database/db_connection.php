<?php

function OpenCon()
{
$dbhost = "crpl.cis.udel.edu";
$dbuser = "oaccvv";
$dbpass = "openaccresults";
$db = "OpenACC";

$conn = new mysqli($dbhost, $dbuser, $dbpass,$db) or die("Connect failed: %s\n". $conn -> error);

return $conn;
}

function CloseCon($conn)
{
$conn -> close();
}

?>
<?php
// this value is checked in order to make sure we have access to this file
define("SkyMakeOnDBConfigConnect", "DBCONFCONNOK");
//begin editing
define("dbHost","localhost");
define("dbName","theskyfallen_skymake-preproduction");
define("dbUser","theskyfallen_pp");
define("dbPassword","Pre2020**");
//stop editing
//Only MySQL is supported
//SkyMake4 uses MySQLi (MySQL Enchanced)
/* Attempt to connect to MySQL database */
$link = mysqli_connect(dbHost, dbUser, dbPassword, dbName);

// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
?>

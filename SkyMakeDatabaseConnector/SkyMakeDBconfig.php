<?php
// this value is checked in order to make sure we have access to this file
define("SkyMakeOnDBConfigConnect", "DBCONFCONNOK");
//begin editing for your own database

// Here is an example configuration for SkyMake Development Container
define("dbHost","dbserver");
define("dbName","skymake_db");
define("dbUser","root");
define("dbPassword","dbpassword");


//stop editing
//Only MySQL is supported
//SkyMake4 uses PHP MySQLi (MySQL Enchanced)
/* Attempt to connect to MySQL database */
$link = mysqli_connect(dbHost, dbUser, dbPassword, dbName);

// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
$linktwo = mysqli_connect(dbHost, dbUser, dbPassword, dbName);

// Check connection
if($linktwo === false){
    die("ERROR: Could not create backup link. " . mysqli_connect_error());
}
?>

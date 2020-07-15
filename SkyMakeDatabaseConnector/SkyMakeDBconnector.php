<?php
die("This file is deprecated. Please check documentation at opensource.theskyfallen.com/skymake/v4/docs");
//this file is deprecated.
include_once "SkyMakeDBconfig.php";
include_once "SkyMakeConfiguration.php";
$connok = false;
global $conn;

if(SkyMakeOnConfigConnect=="CONFCONNOK" and SkyMakeOnDBConfigConnect=="DBCONFCONNOK"){
    $connok = true;
}
else {
    chdir("..");
    if(SkyMakeOnConfigConnect=="CONFCONNOK" and SkyMakeOnDBConfigConnect=="DBCONFCONNOK"){
    $connok = true;
    }
    else{
    die("SKYMAKE-HANDLECONFCONNERROR \n deducated Corporation and Skyfallen \n All Rights Reserved \n Build Info:SkyMakeTWENTY20PreviewEditionBuild-127.0.0.1-HOSTAME:YIGIT'S MACBOOKPRO-SM6-MACHKERNEL-APPLEKERNELDETECTED!-MACOS LOCAL DEVELOPMENT-THROWN WHILE SkyMakeDatabaseConnector for MySQL Enchanced MySQLi WAS HANDLING SkyMake Database Connection-Config Connection Check failed-CHMOD PARENTDIR FAILED");
}}
 if($connok){
     $conn = mysqli_connect(dbHost, dbUser, dbPassword, dbName;
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
 }
function SkyMakeDBExecute($sqlstatement){
         if (mysqli_query($conn, $sqlstatement)) {
             die("OK");
         } else {
             die("Error".mysqli_error($conn));
             return "Error creating database: " . mysqli_error($conn);
         }

         mysqli_close($conn);
}

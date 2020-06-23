<?php
include_once "SkyMakeDBconfig.php";
include_once "SkyMakeConfiguration.php";
$connok = false;
global $conn;
function SkyMakeDBExecute($sqlstatement){
    if ($sqlout = mysqli_query($conn, $sqlstatement)) {
        die("OK");
        return $sqlout;
    } else {
        die(mysqli_error($conn));
        return "Error creating database: " . mysqli_error($conn);
    }

    mysqli_close($conn);
}

if(SkyMakeOnConfigConnect=="CONFCONNOK" and SkyMakeOnDBConfigConnect=="DBCONFCONNOK"){
    $connok = true;
}
else {
    chdir("..");
    if(SkyMakeOnConfigConnect=="CONFCONNOK" and SkyMakeOnDBConfigConnect=="DBCONFCONNOK"){
    $connok = true;
    }IP:
    else{
    die("SKYMAKE-HANDLECONFCONNERROR \n deducated Corporation and Skyfallen \n All Rights Reserved \n Build Info:SkyMakeTWENTY20PreviewEditionBuild-IP:127.0.0.1-THROWN WHILE SkyMakeDatabaseConnector for MySQL Enchanced MySQLi WAS HANDLING SkyMake Database Connection-Config Connection Check failed-CHMOD PARENTDIR FAILED");
}}
 if($connok){
     $conn = mysqli_connect(dbHost, dbUser, dbPassword);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
 }

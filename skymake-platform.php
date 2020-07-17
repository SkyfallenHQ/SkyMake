<?php
$request = $_GET["request"];
$requestsuccess = false;
session_start();
if(!$_SESSION["loggedin"]){
    header("Location: /?act=signin");
}
if($request == "home" or $request == "dash" or $request == "course" or $request == "oes" or $request == "liveclass" or $request == "grades"){
    $requestsuccess = true;
    include "nps/widgets/dash.php";
}
if($requestsuccess == false){
    include "nps/notfound.html";
}
?>
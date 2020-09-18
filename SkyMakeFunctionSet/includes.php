<?php
include_once "Mission-Critical-Functions/SMakeInfo.php";
include_once "Operation-Requirements/MainFunctions.php";
include_once "Mission-Critical-Functions/mcs.php";
include_once "Mission-Critical-Functions/SMC.php";

function crashSkyMake($cdata,$creason){
    MCS::crashLog($cdata,debug_backtrace()["file"]."-".debug_backtrace()["line"],$creason);
    SMC::displayCrash($creason);
    die();
}
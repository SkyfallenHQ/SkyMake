<?php
define("SkyMakeOnConfigConnect", "CONFCONNOK");
//Start editing here!
define("SFLC_HOST","meet.jit.si");
// That's all. Now stop.
include "SkyMakeFunctionSet/includes.php";
include "SkyfallenCodeLib/UpdatesConsoleConnector.php";

define("UPDATES_PROVIDER_URL","https://swupdate.theskyfallen.com");
define("UPDATES_PROVIDER_APP_ID","541a2fb0429491f158f100ce7dcb0b86");
define("UPDATE_SEED","Stable");
if(var_dump(getenv('SKYMAKE_DEVENV')) == "YES"){
   define("DEVENV",true);
} else {
    define("DEVENV",false);
}
include_once "updater.php";
include "thisversion.php";
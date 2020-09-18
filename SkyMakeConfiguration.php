<?php
define("SkyMakeOnConfigConnect", "CONFCONNOK");
//Start editing here!
define("SFLC_HOST","meet.jit.si");
include "SkyMakeFunctionSet/includes.php";
// That's all. Now stop.

/****************************************************************/
//                 Skyfallen Security Check
//    This will check if the software update server is secure
//       as it can be a threat while updating the software
/****************************************************************/
crashSkyMake("TestCrash","ErrorReason");

/* $gcret = MCS::getCertificate();
if($gcret["success"] == true){
    MCS::log("Certificate downloaded successfully.");
}else{

} */
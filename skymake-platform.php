<?php
$request = $_GET["request"];
$requestsuccess = false;
include "SkyMakeFunctionSet/Operation-Requirements/MainFunctions.php";
include "SkyMakeDatabaseConnector/SkyMakeDBconfig.php";
session_start();
if(!$_SESSION["loggedin"]){
    header("Location: /?act=signin");
}
if($request == "home" or $request == "dash" or $request == "course" or $request == "oes" or $request == "liveclass" or $request == "grades"){
    $requestsuccess = true;
    include "nps/widgets/dash.php";
    echo(singlewidget(getassignedlessons($link)[0],getassignedteachers($link)[0],getassignedtimes($link)[0],getassignedtopics($link)[0],getassignedunits($link)[0],getassignedbgurls($link)[0]));
}
if($requestsuccess == false){
    include "nps/notfound.html";
}
?>
<script src="nps/widgets/assets/js/jquery.min.js"></script>
<script src="nps/widgets/assets/bootstrap/js/bootstrap.min.js"></script>
<script src="nps/widgets/assets/js/Animated-Type-Heading.js"></script>
</body>

</html>

<?php
$request = $_GET["request"];
$requestsuccess = false;
include "SkyMakeFunctionSet/Operation-Requirements/MainFunctions.php";
include "SkyMakeDatabaseConnector/SkyMakeDBconfig.php";
include "classes/user.php";
include "SkyMakeDatabaseConnector/db-class.php";
$userclass = new SMUserClass();
session_start();
//$_SESSION["sfl"] = getsetting($link,"link");
if(!$_SESSION["loggedin"]){
    header("Location: /?act=signin");
}
$_SESSION["classid"] = $userclass->getStudentClassID($link,$_SESSION["username"]);
if(substr( $request, 0, 7 ) === "lesson/") {
    $requestsuccess = true;
    include "nps/widgets/dash.php";
    $ce_len = strlen($request);
    $cenroller = substr($request, 7, $ce_len);
    $cenroller = str_replace("/","",$cenroller);
    $lessonname = getassignedlessons($link)[getassignedlessonquery($link, $cenroller)];
    if (!($lessonname == "n")) {
        echo("<div class='text-center'><h1>Lesson Details | " . $lessonname . "</h1></div>");
        $n = getassignedlessonquery($link, $cenroller);
        echo(overview(getassignedlessons($link)[$n], getassignedteachers($link)[$n], getassignedtimes($link)[$n], getassignedtopics($link)[$n], getassignedunits($link)[$n], getassignedbgurls($link)[$n],getassignedids($link)[$n],getlessoncontents($link,$cenroller)));
    }else {
        echo("<div class='text-center'><h1>This lesson does not exist. Please access your course by dashbboard.</h1></div>");
    }
}
if($request == "profile" or $request == "profile/"){
    $requestsuccess = true;
    include "nps/widgets/dash.php";
    echo("<a href='/logout'><h1>Log Out</h1></a>");
}
if($request == "logout" or $request == "logout/"){
    $requestsuccess = true;
    include "nps/widgets/dash.php";
    echo("<h1>Logging you out...</h1>");
    session_destroy();
    header("Location: /");
}
if($request == "home" or $request == "dash" or $request == "course" or $request == "oes" or $request == "liveclass" or $request == "grades" or $request == "home/" or $request == "dash/" or $request == "course/" or $request == "oes/" or $request == "liveclass/" or $request == "grades/"){
    if(!($request == "dash" or $request == "dash/")){
        header("Location: /dash");
        exit;
    }
    $requestsuccess = true;
    include "nps/widgets/dash.php";
    echo("<div class=\"caption v-middle text-center\"><h1 class=\"cd-headline clip\"><span class=\"blc\">Welcome to the new dashboard.</span><br><span class=\"cd-words-wrapper\"><b class=\"is-visible\">Here are your courses.</b><b>Here are your grades.</b><b>Here are your online exams.</b></span></h1> </div>");
    $lessoncount = count(getassignedlessons($link));
    if(getassignedlessons($link)[0]!= "n"){
        if(is_odd($lessoncount)){
            $completed_jobs = array();
            for($n = 0; $n < $lessoncount and $n+1 != $lessoncount; $n = $n+2){
                echo(doublewidget(getassignedlessons($link)[$n], getassignedteachers($link)[$n], getassignedtimes($link)[$n], getassignedtopics($link)[$n], getassignedunits($link)[$n], getassignedbgurls($link)[$n],getassignedids($link)[$n], getassignedlessons($link)[$n + 1], getassignedteachers($link)[$n + 1], getassignedtimes($link)[$n + 1], getassignedtopics($link)[$n + 1], getassignedunits($link)[$n + 1], getassignedbgurls($link)[$n + 1],getassignedids($link)[$n + 1]));
                echo("<br>");
                array_push($completed_jobs, $n, $n + 1);
            }
            $lessoncount = $lessoncount - 1;
            echo(singlewidget(getassignedlessons($link)[$lessoncount],getassignedteachers($link)[$lessoncount],getassignedtimes($link)[$lessoncount],getassignedtopics($link)[$lessoncount],getassignedunits($link)[$lessoncount],getassignedbgurls($link)[$lessoncount],getassignedids($link)[$lessoncount]));
        } else {
            $completed_jobs = array();
            for($n = 0; $n < $lessoncount; $n = $n+2){
                echo(doublewidget(getassignedlessons($link)[$n], getassignedteachers($link)[$n], getassignedtimes($link)[$n], getassignedtopics($link)[$n], getassignedunits($link)[$n], getassignedbgurls($link)[$n],getassignedids($link)[$n],getassignedlessons($link)[$n + 1], getassignedteachers($link)[$n + 1], getassignedtimes($link)[$n + 1], getassignedtopics($link)[$n + 1], getassignedunits($link)[$n + 1], getassignedbgurls($link)[$n + 1],getassignedids($link)[$n + 1]));
                array_push($completed_jobs, $n, $n + 1);
            }
        }
    }else{
        echo("<div class=\"text-center\"><h1>You have no active courses.</h1></div>");
    }}
if($request == "dash/mobile" or $request == "dash/mobile/") {
    $requestsuccess = true;
    include "nps/widgets/dash.php";
    echo("<div class=\"text-center\"><h1>Welcome,".$_SESSION["username"]."</h1></div>");
    $lessoncount = count(getassignedlessons($link));
    if(getassignedlessons($link)[0]!= "n"){
        for($n = 0; $n < $lessoncount; $n = $n+1){
            echo(singlewidget(getassignedlessons($link)[$n], getassignedteachers($link)[$n], getassignedtimes($link)[$n], getassignedtopics($link)[$n], getassignedunits($link)[$n], getassignedbgurls($link)[$n],getassignedids($link)[$n], getassignedlessons($link)[$n + 1], getassignedteachers($link)[$n + 1], getassignedtimes($link)[$n + 1], getassignedtopics($link)[$n + 1], getassignedunits($link)[$n + 1], getassignedbgurls($link)[$n + 1],getassignedids($link)[$n + 1]));
            echo("<br>");
        }
    }else{
        echo("<div class=\"text-center\"><h1>You have no active courses.</h1></div>");
    }
}
if($requestsuccess == false){
    include "nps/notfound.html";
}
?>
<script src="nps/widgets/assets/js/jquery.min.js"></script>
<script src="nps/widgets/assets/bootstrap/js/bootstrap.min.js"></script>
<script src="nps/widgets/assets/js/Animated-Type-Heading.js"></script>
</body>
<footer>
    <div class="footercustom">SkyMake Version 4 - Developed by Skyfallen | This is a beta and must not be used for production. | © 2016-2020 Skyfallen © 2017-2020 SkyMake © 2020 Skyfallen Open Source</div>
</footer>
</html>
<?php
$request = $_GET["request"];
$requestsuccess = false;
include "SkyMakeFunctionSet/Operation-Requirements/MainFunctions.php";
include "SkyMakeDatabaseConnector/SkyMakeDBconfig.php";
session_start();
if(!$_SESSION["loggedin"]){
    header("Location: /?act=signin");
}
if(substr( $request, 0, 7 ) === "lesson/"){
$requestsuccess = true;
include "nps/widgets/dash.php";
echo("<div class='text-center'><h1>Lesson Details</h1></div>");
}
if($request == "home" or $request == "dash" or $request == "course" or $request == "oes" or $request == "liveclass" or $request == "grades" or $request == "home/" or $request == "dash/" or $request == "course/" or $request == "oes/" or $request == "liveclass/" or $request == "grades/"){
    $requestsuccess = true;
    include "nps/widgets/dash.php";
    echo("<div class=\"caption v-middle text-center\"><h1 class=\"cd-headline clip\"><span class=\"blc\">Welcome to the new dashboard.</span><br><span class=\"cd-words-wrapper\"><b class=\"is-visible\">Here are your courses.</b><b>Here are your grades.</b><b>Here are your online exams.</b></span></h1> </div>");
    $lessoncount = count(getassignedlessons($link));
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
}
if($request == "home/mobile" or $request == "home/mobile/") {
    $requestsuccess = true;
    include "nps/widgets/dash.php";
    echo("<div class=\"text-center\"><h1>Welcome,".$_SESSION["username"]."</h1></div>");
    $lessoncount = count(getassignedlessons($link));
        for($n = 0; $n < $lessoncount; $n = $n+1){
            echo(singlewidget(getassignedlessons($link)[$n], getassignedteachers($link)[$n], getassignedtimes($link)[$n], getassignedtopics($link)[$n], getassignedunits($link)[$n], getassignedbgurls($link)[$n],getassignedids($link)[$n], getassignedlessons($link)[$n + 1], getassignedteachers($link)[$n + 1], getassignedtimes($link)[$n + 1], getassignedtopics($link)[$n + 1], getassignedunits($link)[$n + 1], getassignedbgurls($link)[$n + 1],getassignedids($link)[$n + 1]));
            echo("<br>");
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

</html>

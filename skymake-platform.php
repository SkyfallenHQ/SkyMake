<?php
// Initialize the session
session_name('SkyMakeSessionStorage');
session_start();
if(DEVENV) {
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
}
$request = $_GET["request"];
if(substr($request ,0 ,1)=="/"){
 $request = substr($request,1,strlen($request)-1);
}
if (isset($_GET["lang"])) {
    $locale = $_GET["lang"].".UTF-8";
    $_SESSION["locale"] = $locale;
}
else if (isset($_SESSION["locale"])) {
    $locale  = $_SESSION["locale"];
}
else {
    $locale = "en_US";
    $_SESSION["locale"] = $locale;
}

$txtd = "skymake";
textdomain($txtd);
bindtextdomain($txtd,"locale");
bind_textdomain_codeset($txtd,"UTF-8");

putenv("LANG=".$_SESSION["locale"]);
putenv("LANGUAGE=".$_SESSION["locale"]);

$results = setlocale(LC_ALL,$_SESSION["locale"]);

if (isset($_GET["dm"])) {
    $dm = $_GET["dm"];
    $_SESSION["dm"] = $dm;
}
else if (isset($_SESSION["dm"])) {
    $dm  = $_SESSION["dm"];
}
else {
    $dm = "no";
    $_SESSION["dm"] = $dm;
}
include_once "SkyMakeConfiguration.php";
include_once "SkyMakeFunctionSet/Operation-Requirements/MainFunctions.php";
include_once "SkyMakeDatabaseConnector/SkyMakeDBconfig.php";
include_once "classes/user.php";
include_once "SkyMakeDatabaseConnector/db-class.php";
if(!$_SESSION["loggedin"]){
    $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    header("Location: /?act=signin&redirect_to=".urlencode($actual_link));
}
//get user role
$user_role = SMUser::getRole($link,$_SESSION["username"]);
$_SESSION["user_role"] = $user_role;

if($user_role == "unverified" and $request=="logout"){
    include "nps/widgets/dash.php";
    echo("<h1>"._("Logging you out...")."</h1>");
    session_destroy();
    header("Location: /");
    die();
}

if($user_role == "unverified"){
    include "nps/widgets/dash.php";
    include "nps/notapproved.php";
    die();
}
if($user_role == "student") {
    if (substr($request, 0, 7) === "lesson/") {
        $requestsuccess = true;
        include "nps/widgets/dash.php";
        $ce_len = strlen($request);
        $cenroller = substr($request, 7, $ce_len);
        $cenroller = str_replace("/", "", $cenroller);
        $n = getassignedlessonquery($linktwo, $cenroller);
        $lessonname = getassignedlessons($link)[$n];
        if (!($lessonname == "n")) {
            echo("<div class='text-center'><h1>"._("Lesson Details")." | " . $lessonname . "</h1></div>");
            echo(overview(getassignedlessons($link)[$n], getassignedteachers($link)[$n], getassignedtimes($link)[$n], getassignedtopics($link)[$n], getassignedunits($link)[$n], getassignedbgurls($link)[$n], getassignedids($link)[$n], getlessoncontents($link, $cenroller)));
        } else {
            echo("<div class='text-center'><h1>"._("This lesson does not exist. Please access your course by dashbboard.")."</h1></div>");
        }
    }
    if (substr($request, 0, 10) === "liveclass/") {
        $requestsuccess = true;
        include "nps/widgets/dash.php";
        $ce_len = strlen($request);
        $cenroller = substr($request, 10, $ce_len);
        $cenroller = str_replace("/", "", $cenroller);
        $lctoken = getLiveClassToken($link, $cenroller, $_SESSION["classid"]);
        if (isContentValid($link, $cenroller) == true and !($lctoken == false)) {
            echo("<div class='text-center'><h1 style='display: inline;'>"._("Live Class")." | SkyfallenLiveConnect ID:" . $cenroller . "</h1></div>");
            ?>
            <div class="row" style="padding-top: 30px; width: 70%; text-align: left; margin-right: auto; margin-left: auto;">
                <div class="col-sm-6">
                    <div class="card" id="boxonetohide" <?php if($_SESSION["dm"] == "on"){ echo "style='background-color: darkgrey;'"; } ?>>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo(_("Join from your browser")); ?></h5>
                            <p class="card-text"><?php echo(_("You don't need any apps of software downloaded.")); ?></p>
                            <button onclick="loadWebMeeting()" class="btn btn-outline-dark"><?php echo(_("Join from browser")); ?></button>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="card" id="boxtwotohide" <?php if($_SESSION["dm"] == "on"){ echo "style='background-color: darkgrey;'"; } ?>>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo(_("Join with app")); ?></h5>
                            <p class="card-text"><?php echo(_("Join using SkyMake Desktop")); ?></p>
                            <a href="<?php echo 'smdesktop://'.SFLC_HOST.'/'.$lctoken[0]; ?>" class="btn btn-outline-dark"><?php echo(_("Join via app")); ?></a>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            echo("<script src='https://".SFLC_HOST."/external_api.js'></script>
        <script>
        const domain = '".SFLC_HOST."';
        const options = {
            roomName: '" . $lctoken[0] . "',
            width: self.innerWidth,
            height: self.innerHeight,
            parentNode: undefined
        };
        function loadWebMeeting(){
            document.getElementById(\"boxonetohide\").style.display = 'none';
            document.getElementById(\"boxtwotohide\").style.display = 'none';
            document.getElementById(\"mainfooter\").style.display = 'none';
            const api = new JitsiMeetExternalAPI(domain, options);
            api.executeCommands({
            email: '".$_SESSION["username"]."@ne.sm.thesf.me',
            displayName: '".$_SESSION["username"]."'
            });
            
        }
        </script>");
        } else {
            if (!isContentValid($link, $cenroller)) {
                echo("<div class='text-center'><h1>"._("This lesson does not exist. Please access your course by your own dashboard.")."</h1></div>");
            } else {
                echo("<div class='text-center'><h1>"._("This meeting has not started.")."</h1></div>");
            }
        }
    }
    if ($request == "profile" or $request == "profile/") {
        $requestsuccess = true;
        include "nps/widgets/dash.php";
        echo("<a href='/logout'><h1>"._("Log Out")."</h1></a>");
    }
    if ($request == "logout" or $request == "logout/") {
        $requestsuccess = true;
        include "nps/widgets/dash.php";
        echo("<h1>"._("Logging you out...")."</h1>");
        session_destroy();
        header("Location: /");
    }
    if ($request == "home" or $request == "dash" or $request == "course" or $request == "oes" or $request == "liveclass" or $request == "grades" or $request == "home/" or $request == "dash/" or $request == "course/" or $request == "oes/" or $request == "liveclass/" or $request == "grades/") {
        if (!($request == "dash" or $request == "dash/")) {
            header("Location: /dash");
            exit;
        }
        $requestsuccess = true;
        include "nps/widgets/dash.php";
        //echo("<div class=\"caption v-middle text-center\"><h1 class=\"cd-headline clip\"><span class=\"blc\">Welcome to the new dashboard.</span><br><span class=\"cd-words-wrapper\"><b class=\"is-visible\">Here are your courses.</b><b>Here are your grades.</b><b>Here are your online exams.</b></span></h1> </div>");
        $lessoncount = count(getassignedlessons($link));
        if (getassignedlessons($link)[0] != "n") {
            if (is_odd($lessoncount)) {
                $completed_jobs = array();
                for ($n = 0; $n < $lessoncount and $n + 1 != $lessoncount; $n = $n + 2) {
                    echo(doublewidget(getassignedlessons($link)[$n], getassignedteachers($link)[$n], getassignedtimes($link)[$n], getassignedtopics($link)[$n], getassignedunits($link)[$n], getassignedbgurls($link)[$n], getassignedids($link)[$n], getassignedlessons($link)[$n + 1], getassignedteachers($link)[$n + 1], getassignedtimes($link)[$n + 1], getassignedtopics($link)[$n + 1], getassignedunits($link)[$n + 1], getassignedbgurls($link)[$n + 1], getassignedids($link)[$n + 1]));
                    echo("<br>");
                    array_push($completed_jobs, $n, $n + 1);
                }
                $lessoncount = $lessoncount - 1;
                echo(singlewidget(getassignedlessons($link)[$lessoncount], getassignedteachers($link)[$lessoncount], getassignedtimes($link)[$lessoncount], getassignedtopics($link)[$lessoncount], getassignedunits($link)[$lessoncount], getassignedbgurls($link)[$lessoncount], getassignedids($link)[$lessoncount]));
            } else {
                $completed_jobs = array();
                for ($n = 0; $n < $lessoncount; $n = $n + 2) {
                    echo(doublewidget(getassignedlessons($link)[$n], getassignedteachers($link)[$n], getassignedtimes($link)[$n], getassignedtopics($link)[$n], getassignedunits($link)[$n], getassignedbgurls($link)[$n], getassignedids($link)[$n], getassignedlessons($link)[$n + 1], getassignedteachers($link)[$n + 1], getassignedtimes($link)[$n + 1], getassignedtopics($link)[$n + 1], getassignedunits($link)[$n + 1], getassignedbgurls($link)[$n + 1], getassignedids($link)[$n + 1]));
                    array_push($completed_jobs, $n, $n + 1);
                }
            }
        } else {
            echo("<div class=\"text-center\"><h1>"._("You have no active courses.")."</h1></div>");
        }

    }
    if ($request == "dash/mobile" or $request == "dash/mobile/") {
        $requestsuccess = true;
        include "nps/widgets/dash.php";
        echo("<div class=\"text-center\"><h1>Welcome," . $_SESSION["username"] . "</h1></div>");
        $lessoncount = count(getassignedlessons($link));
        if (getassignedlessons($link)[0] != "n") {
            for ($n = 0; $n < $lessoncount; $n = $n + 1) {
                echo(singlewidget(getassignedlessons($link)[$n], getassignedteachers($link)[$n], getassignedtimes($link)[$n], getassignedtopics($link)[$n], getassignedunits($link)[$n], getassignedbgurls($link)[$n], getassignedids($link)[$n], getassignedlessons($link)[$n + 1], getassignedteachers($link)[$n + 1], getassignedtimes($link)[$n + 1], getassignedtopics($link)[$n + 1], getassignedunits($link)[$n + 1], getassignedbgurls($link)[$n + 1], getassignedids($link)[$n + 1]));
                echo("<br>");
            }
        } else {
            echo("<div class=\"text-center\"><h1>"._("You have no active courses.")."</h1></div>");
        }
    }
}
if($user_role == "teacher") {
    $_SESSION["teacheruser"] = $_SESSION["username"];
    if (substr($request, 0, 7) === "lesson/") {
        $requestsuccess = true;
        include "nps/widgets/dash.php";
        $ce_len = strlen($request);
        $cenroller = substr($request, 7, $ce_len);
        $cenroller = str_replace("/", "", $cenroller);
        $n = getassignedlessonqueryteacher($linktwo, $cenroller);
        $lessonname = getassignedlessonsteacher($link)[$n];
        if (!($lessonname == "n")) {
            echo("<div class='text-center'><h1>"._("Course Details")." | " . $lessonname . "</h1></div>");
            echo(overview(getassignedlessonsteacher($link)[$n], "Assigned to me", getassignedtimesteacher($link)[$n], getassignedtopicsteacher($link)[$n], getassignedunitsteacher($link)[$n], getassignedbgurlsteacher($link)[$n], getassignedidsteacher($link)[$n], getlessoncontents($link, $cenroller)));
        } else {
            echo("<div class='text-center'><h1>"._("This lesson does not exist. Please access your course by your own dashboard.")."</h1></div>");
        }
    }
    if (substr($request, 0, 10) === "liveclass/") {
        $requestsuccess = true;
        include "nps/widgets/dash.php";
        $ce_len = strlen($request);
        $cenroller = substr($request, 10, $ce_len);
        $cenroller = str_replace("/", "", $cenroller);
        $lctoken = getLiveClassToken($link, $cenroller, $_SESSION["classid"],false);
        if (isContentValid($link, $cenroller) == true and !($lctoken == false)) {
            echo("<div class='text-center'><h1 style='display: inline;'>"._("Live Class")." | SkyfallenLiveConnect ID:" . $cenroller . "</h1></div>");
            ?>
            <div class="row" style="padding-top: 30px; width: 70%; text-align: left; margin-right: auto; margin-left: auto;">
                <div class="col-sm-6">
                    <div class="card" id="boxonetohide" <?php if($_SESSION["dm"] == "on"){ echo "style='background-color: darkgrey;'"; } ?>>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo(_("Join from your browser")); ?></h5>
                            <p class="card-text"><?php echo(_("You don't need any apps of software downloaded.")); ?></p>
                            <button onclick="loadWebMeeting()" class="btn btn-outline-dark"><?php echo(_("Join from browser")); ?></button>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="card" id="boxtwotohide" <?php if($_SESSION["dm"] == "on"){ echo "style='background-color: darkgrey;'"; } ?>>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo(_("Join with app")); ?></h5>
                            <p class="card-text"><?php echo(_("Join using SkyMake Desktop")); ?></p>
                            <a href="<?php echo 'smdesktop://'.SFLC_HOST.'/'.$lctoken[0]; ?>" class="btn btn-outline-dark"><?php echo(_("Join via app")); ?></a>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            echo("<script src='https://".SFLC_HOST."/external_api.js'></script>
        <script>
        const domain = '".SFLC_HOST."';
        const options = {
            roomName: '" . $lctoken[0] . "',
            width: self.innerWidth,
            height: self.innerHeight,
            parentNode: undefined
        };
        function loadWebMeeting(){
            document.getElementById(\"boxonetohide\").style.display = 'none';
            document.getElementById(\"boxtwotohide\").style.display = 'none';
            document.getElementById(\"mainfooter\").style.display = 'none';
            const api = new JitsiMeetExternalAPI(domain, options);
            api.executeCommands({
            email: '".$_SESSION["username"]."@ne.sm.thesf.me',
            displayName: '".$_SESSION["username"]."'
            });
        }
        </script>");
        } else {
            if (!isContentValid($link, $cenroller)) {
                echo("<div class='text-center'><h1>"._("This lesson does not exist. Please access your course by your own dashboard.")."</h1></div>");
            } else {
                $includedcourses = array();
                $sql = "SELECT * FROM skymake_lessoncontent WHERE `content-id`='".$cenroller."'";
                if($result = mysqli_query($link, $sql)){
                    if(mysqli_num_rows($result) > 0){
                        while($row = mysqli_fetch_array($result)){
                            array_push($includedcourses,$row["lessonid"]);
                        }
                        // Free result set
                        mysqli_free_result($result);
                    } else{
                        die(_("This content was not assigned to any course!"));
                    }
                } else{
                    die("ERROR: Could not able to execute for course  $sql. " . mysqli_error($link));
                }
                // Close connection
                mysqli_close($link);
                sleep(1);
                $includedgroups = array();
                foreach($includedcourses as $oneofcourses) {
                    $sql = "SELECT classid FROM skymake_assignments WHERE lessonid='".$oneofcourses."'";
                    if($result = mysqli_query($linktwo, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            while($row = mysqli_fetch_array($result)){
                                array_push($includedgroups,$row["classid"]);
                            }
                            // Free result set
                            mysqli_free_result($result);
                        } else{
                            die(_("This content was not assigned to any course!"));
                        }
                    } else{
                        die("ERROR: Could not able to execute $sql. " . mysqli_error($linktwo));
                    }
                }
            }
            $exitret = false;
            $newtoken = md5(uniqid(rand(), true));
            foreach ($includedgroups as $oneofgroups){
                $ret = setLiveClassToken($linktwo, $cenroller, $oneofgroups, $newtoken);
                if(!$ret){
                    die(_("An error occured."));
                } if($ret) {
                    $exitret = true;
                }
            }
            if($exitret){
                header("Refresh:0");
            }

        }
    }
    if ($request == "profile" or $request == "profile/") {
        $requestsuccess = true;
        include "nps/widgets/dash.php";
        echo("<a href='/logout'><h1>"._("Log Out")."</h1></a>");
    }
    if ($request == "logout" or $request == "logout/") {
        $requestsuccess = true;
        include "nps/widgets/dash.php";
        echo("<h1>"._("Logging you out...")."</h1>");
        session_destroy();
        header("Location: /");
    }

    if ($request == "home" or $request == "dash" or $request == "course" or $request == "oes" or $request == "liveclass" or $request == "grades" or $request == "home/" or $request == "dash/" or $request == "course/" or $request == "oes/" or $request == "liveclass/" or $request == "grades/") {
        if (!($request == "dash" or $request == "dash/")) {
            header("Location: /dash");
            exit;
        }
        $requestsuccess = true;
        include "nps/widgets/dash.php";
        //echo("<div class=\"caption v-middle text-center\"><h1 class=\"cd-headline clip\"><span class=\"blc\">Welcome to the new dashboard.</span><br><span class=\"cd-words-wrapper\"><b class=\"is-visible\">Here are your courses.</b><b>Here are your grades.</b><b>Here are your online exams.</b></span></h1> </div>");
        $lessoncount = count(getassignedlessonsteacher($link));
        if (getassignedlessonsteacher($link)[0] != "n") {
            if (is_odd($lessoncount)) {
                $completed_jobs = array();
                for ($n = 0; $n < $lessoncount and $n + 1 != $lessoncount; $n = $n + 2) {
                    echo(doublewidget(getassignedlessonsteacher($link)[$n], "Assigned to me", getassignedtimesteacher($link)[$n], getassignedtopicsteacher($link)[$n], getassignedunitsteacher($link)[$n], getassignedbgurlsteacher($link)[$n], getassignedidsteacher($link)[$n], getassignedlessonsteacher($link)[$n + 1], "Assigned to me", getassignedtimesteacher($link)[$n + 1], getassignedtopicsteacher($link)[$n + 1], getassignedunitsteacher($link)[$n + 1], getassignedbgurlsteacher($link)[$n + 1], getassignedidsteacher($link)[$n + 1]));
                    echo("<br>");
                    array_push($completed_jobs, $n, $n + 1);
                }
                $lessoncount = $lessoncount - 1;
                echo(singlewidget(getassignedlessonsteacher($link)[$lessoncount], "Assigned to me", getassignedtimesteacher($link)[$lessoncount], getassignedtopicsteacher($link)[$lessoncount], getassignedunitsteacher($link)[$lessoncount], getassignedbgurlsteacher($link)[$lessoncount], getassignedidsteacher($link)[$lessoncount]));
            } else {
                $completed_jobs = array();
                for ($n = 0; $n < $lessoncount; $n = $n + 2) {
                    echo(doublewidget(getassignedlessonsteacher($link)[$n], "Assigned to me", getassignedtimesteacher($link)[$n], getassignedtopicsteacher($link)[$n], getassignedunitsteacher($link)[$n], getassignedbgurlsteacher($link)[$n], getassignedidsteacher($link)[$n], getassignedlessonsteacher($link)[$n + 1], "Assigned to me", getassignedtimesteacher($link)[$n + 1], getassignedtopicsteacher($link)[$n + 1], getassignedunitsteacher($link)[$n + 1], getassignedbgurlsteacher($link)[$n + 1], getassignedidsteacher($link)[$n + 1]));
                    array_push($completed_jobs, $n, $n + 1);
                }
            }
        } else {
            echo("<div class=\"text-center\"><h1>"._("You are free for now.")."</h1></div>");
        }

    }
}
if($user_role == "admin") {
    if ($request == "logout" or $request == "logout/") {
        $requestsuccess = true;
        include "nps/widgets/dash.php";
        echo("<h1>"._("Logging you out...")."</h1>");
        session_destroy();
        header("Location: /");
    }
   if($request == "upload" or $request == "upload/"){
       $requestsuccess = true;
       include "userupload.php";
   }
   if($request == "home" or $request == "home/"){
       include_once "nps/widgets/dash.php";
       $requestsuccess = true;
       ?>

       <div class="row" style="padding-top: 30px; width: 70%; text-align: left; margin-right: auto; margin-left: auto;">
           <div class="col-sm-6">
               <div class="card" <?php if($_SESSION["dm"] == "on"){ echo "style='background-color: darkgrey;'"; } ?>>
                   <div class="card-body">
                       <h5 class="card-title"><?php echo(_("Users")); ?></h5>
                       <p class="card-text"><?php echo(_("You can change user roles here")); ?></p>
                       <a href="/users" class="btn btn-outline-dark"><?php echo(_("Users")); ?></a>
                   </div>
               </div>
           </div>
           <div class="col-sm-6">
               <div class="card" <?php if($_SESSION["dm"] == "on"){ echo "style='background-color: darkgrey;'"; } ?>>
                   <div class="card-body">
                       <h5 class="card-title"><?php echo(_("Classes")); ?></h5>
                       <p class="card-text"><?php echo(_("You can create,delete or edit groups here")); ?></p>
                       <a href="/groups" class="btn btn-outline-dark"><?php echo(_("Classes")); ?></a>
                   </div>
               </div>
           </div>
       </div>
       <div class="row" style="padding-top: 30px; width: 70%; text-align: left; margin-right: auto; margin-left: auto;">
           <div class="col-sm-6">
               <div class="card" <?php if($_SESSION["dm"] == "on"){ echo "style='background-color: darkgrey;'"; } ?>>
                   <div class="card-body">
                       <h5 class="card-title"><?php echo(_("Results")); ?></h5>
                       <p class="card-text"><?php echo(_("You can view your students' results here")); ?></p>
                       <a href="/results" class="btn btn-outline-dark"><?php echo(_("Results")); ?></a>
                   </div>
               </div>
           </div>
           <div class="col-sm-6">
               <div class="card" <?php if($_SESSION["dm"] == "on"){ echo "style='background-color: darkgrey;'"; } ?>>
                   <div class="card-body">
                       <h5 class="card-title"><?php echo(_("Upload")); ?></h5>
                       <p class="card-text"><?php echo(_("You can upload documents or files here")); ?></p>
                       <a href="/upload" class="btn btn-outline-dark"><?php echo(_("Upload")); ?></a>
                   </div>
               </div>
           </div>
       </div>
       <div class="row" style="padding-top: 30px; width: 70%; text-align: left; margin-right: auto; margin-left: auto;">
           <div class="col-sm-6">
               <div class="card" <?php if($_SESSION["dm"] == "on"){ echo "style='background-color: darkgrey;'"; } ?>>
                   <div class="card-body">
                       <h5 class="card-title"><?php echo(_("Create an Exam")); ?></h5>
                       <p class="card-text"><?php echo(_("You can create,schedule and edit online exams here")); ?></p>
                       <a href="/examcreate" class="btn btn-outline-dark"><?php echo(_("Create an Exam")); ?></a>
                   </div>
               </div>
           </div>
           <div class="col-sm-6">
               <div class="card" <?php if($_SESSION["dm"] == "on"){ echo "style='background-color: darkgrey;'"; } ?>>
                   <div class="card-body">
                       <h5 class="card-title"><?php echo(_("Courses and Lesson Contents")); ?></h5>
                       <p class="card-text"><?php echo(_("You can create or assign courses,live lessons,uploads and online exams here")); ?></p>
                       <a href="/courses" class="btn btn-outline-dark"><?php echo(_("Courses and Lesson Contents")); ?></a>
                   </div>
               </div>
           </div>
       </div>
       <div class="row" style="padding-top: 30px; width: 70%; text-align: left; margin-right: auto; margin-left: auto;">
           <div class="col-sm-6">
               <div class="card" <?php if($_SESSION["dm"] == "on"){ echo "style='background-color: darkgrey;'"; } ?>>
                   <div class="card-body">
                       <h5 class="card-title"><?php echo(_("Get Support")); ?></h5>
                       <p class="card-text"><?php echo(_("Get Support for SkyMake from official Skyfallen Support")); ?></p>
                       <a href="https://help.theskyfallen.com" class="btn btn-outline-dark"><?php echo(_("Get Support")); ?></a>
                   </div>
               </div>
           </div>
           <div class="col-sm-6">
               <div class="card" <?php if($_SESSION["dm"] == "on"){ echo "style='background-color: darkgrey;'"; } ?>>
                   <div class="card-body">
                       <h5 class="card-title"><?php echo(_("About & Licence")); ?></h5>
                       <p class="card-text"><?php echo(_("You can get more info about this SkyMake install.")); ?></p>
                       <a href="/about" class="btn btn-outline-dark"><?php echo(_("About this Install")); ?></a>
                   </div>
               </div>
           </div>
       </div>
           <?php
   }
    if($request == "about" or $request == "about/"){
        $requestsuccess = true;
        include_once "nps/widgets/dash.php";
        $provider_info = \SkyfallenCodeLibrary\UpdatesConsoleConnector::getProviderData(UPDATES_PROVIDER_URL);
        ?>
        <div class="card-group">
            <div class="card" <?php if($_SESSION["dm"] == "on"){ echo "style='background-color: #4c4747;'"; } ?>>
                <img class="card-img-top" src="https://theskyfallen.company/wp-content/uploads/2020/07/IMG_0183.png" alt="SFLogo">
                <div class="card-body">
                    <h1 class="card-title">SkyMake 4 by Skyfallen</h1>
                    <h6 class="card-title">Version 4.3 Aegaeus</h6>
                 <h5><a href="/updates" class="btn btn-danger"><?php echo _("Check for Updates"); ?></a></h5>
                    <h5 class="card-text">&copy; 2016-2020 The Skyfallen Company | &copy; 2018-2020 The SkyMake Project <br>
                        This application is subject to Skyfallen Open Source Licence and Skyfallen Privacy.</h5>
                    <br>
                    <br>
                    <h4><?= _("Updates for this SkyMake Installation are managed externally by") ?> <br> <a href="<?php echo $provider_info["url"] ?>"><?php echo $provider_info["name"]; ?></a></h4>
                    <h5><?php echo $provider_info["location"]; ?> | <?php echo $provider_info["ounit"]; ?></h5>
                    <h5><?php echo $provider_info["info"]; ?> | <?php echo $provider_info["type"]; ?></h5>
                    <h3><?= _("For Support") ?></h3>
                    <h5><?php echo $provider_info["contact"]; ?> | <?php echo $provider_info["contact_email"]; ?>
                        <br> <a href="<?php echo $provider_info["contact_url"]; ?>"><?php echo $provider_info["contact_url"]; ?></a></h5>
                    <h6 class="card-text">October 26, 2020 - Public Distribution Release</h6>
                    <h6 class="card-text"><small><?php echo THIS_VERSION; ?> - This install was registered with <?php echo VERSION_PROVIDER; ?></small></h6>
                </div>
            </div>
            <div class="card" <?php if($_SESSION["dm"] == "on"){ echo "style='background-color: #4c4747;'"; } ?>>
                <img class="card-img-top" src="/SkyMakeVersionAssets/logo/SkyMake4AboutPage.svg" alt="SM4-FOUR">
                <div class="card-body">
                </div>
            </div>
        </div>
        <?php
    }
    if($request == "updates" or $request == "updates/"){
        $requestsuccess = true;
        include_once "nps/widgets/dash.php";
        $provider_info = \SkyfallenCodeLibrary\UpdatesConsoleConnector::getProviderData(UPDATES_PROVIDER_URL);
        $new_vname = \SkyfallenCodeLibrary\UpdatesConsoleConnector::getLatestVersion(UPDATES_PROVIDER_APP_ID,UPDATE_SEED,UPDATES_PROVIDER_URL);
        $new_version_data = \SkyfallenCodeLibrary\UpdatesConsoleConnector::getLatestVersionData(UPDATES_PROVIDER_APP_ID,UPDATE_SEED,UPDATES_PROVIDER_URL);
        ?>
        <div class="card-group">
            <div class="card" <?php if($_SESSION["dm"] == "on"){ echo "style='background-color: #4c4747;'"; } ?>>
                <img class="card-img-top" src="https://theskyfallen.company/wp-content/uploads/2020/07/IMG_0183.png" alt="SFLogo">
                <div class="card-body">
                    <h1 class="card-title">SkyMake 4 by Skyfallen</h1>
                    <h6 class="card-title">Version 4.3 Aegeaeus</h6>
                    <?php
                    if (THIS_VERSION != $new_vname){
                        if(isset($_GET["install"])) {
                            if ($_GET["install"] == "start") {
                                $_SESSION["UPDATE_AUTHORIZED"] = true;
                                header("location: updater.php");
                            }
                        }
                        ?>
                        <h6><?= _("A New Version is available") ?></h6>
                        <h5><?php echo $new_version_data["title"]; ?> (<?php echo $new_version_data["version"]; ?>) </h5>
                        <h5><?= _("Release Date:") ?> <?php echo $new_version_data["releasedate"]; ?></h5>
                        <h6><?= _("Description:") ?> <br><?php echo $new_version_data["description"]; ?></h6>

                        <a href="?install=start" class="btn btn-dark"><?= _("Install Now.") ?></a>
                            <?php


                    }
                    else {
                        echo _("Your installation is up to date.");
                    }
                    ?>
                    <h4><?= _("Updates for this SkyMake Installation are managed externally by") ?> <br> <a href="<?php echo $provider_info["url"] ?>"><?php echo $provider_info["name"]; ?></a></h4>
                    <h6 class="card-text"><small><?php echo THIS_VERSION; ?> - <?php echo _("This install was registered with ").VERSION_PROVIDER; ?></small></h6>
                </div>
            </div>
            <div class="card" <?php if($_SESSION["dm"] == "on"){ echo "style='background-color: #4c4747;'"; } ?>>
                <img class="card-img-top" src="/SkyMakeVersionAssets/logo/SkyMake4AboutPage.svg" alt="SM4-FOUR">
                <div class="card-body">
                </div>
            </div>
        </div>
        <?php
    }
   if($request == "users" or $request == "users/"){
       include_once "nps/widgets/dash.php";
       $requestsuccess = true;
       if(isset($_POST["deluser"])){
           $sql = "DELETE FROM skymake_users WHERE username='".$_POST["username"]."'";
           if($result = mysqli_query($link, $sql)){
               $sql = "DELETE FROM skymake_roles WHERE username='".$_POST["username"]."'";
               if($result = mysqli_query($link, $sql)){
                    echo _("Success!");
               }
               else{
                   echo("ERROR: Could not able to execute on step two: $sql. " . mysqli_error($link));
               }
           }
           else{
               echo("ERROR: Could not able to execute $sql. " . mysqli_error($link));
           }
       }
       if(isset($_POST["setRole"])){
           SMUser::setRole($link,$_POST["username"],$_POST["newRole"]);
       }
       ?>
       <div style="text-align: center; padding-top: 100px; border-bottom-width: thin; border-bottom-color: #4e555b; border-bottom-style: solid;">
        <form method="post" style="width:800px; text-align: center; margin-right:auto; margin-left: auto; padding-bottom:10px;">
                <div class="input-group">
                    <select style="margin-bottom: 30px;" class="custom-select" id="inputGroupSelect04" aria-label="Select User" name="username">
                        <?php
                        $sql = "SELECT * FROM skymake_users";
                        if($result = mysqli_query($link,$sql)){
                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_array($result)) {
                                    echo "<option>".$row["username"]."</option>";
                                }
                            }
                        }else {
                            echo "SQL Error: $sql . ".mysqli_error($link);
                        }
                        ?>
                    </select>
                    <div class="input-group-append">
                        <button style="margin-bottom: 30px;" class="btn btn-outline-secondary" type="submit" name="deluser" ><?= _("Delete User") ?></button>
                    </div>
                </div>
            <div class="input-group">
                <select class="custom-select" id="inputGroupSelect04" name="newRole">
                    <option selected><?= _("Choose a new role to set...") ?></option>
                    <option value="admin"><?= _("Administrator") ?></option>
                    <option value="student"><?= _("Student") ?></option>
                    <option value="teacher"><?= _("Teacher") ?></option>
                </select>
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="submit" name="setRole"><?= _("Set Role") ?></button>
                </div>
            </div>
        </form>
       </div>
<?php
$link2 = $linktwo;
$sql = "SELECT username FROM skymake_users";
if ($res = mysqli_query($link, $sql)) {
        if (mysqli_num_rows($res) > 0) {
            echo '<div style="text-align: center;">';
            echo "<table class='table' style='width:80%; margin-right: auto; margin-left: auto;'>";
            echo "<thead>";
            echo "<tr>";
            echo "<th scope='col'>"._("Username")."</th>";
            echo "<th scope='col'>"._("User Role")."</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            while ($row = mysqli_fetch_array($res)) {
                echo "<tr>";
                echo "<td>" . $row['username'] . "</td>";
                $rolefromdb = "unverified";
                $sql = "SELECT role FROM skymake_roles WHERE username='".$row["username"]."'";
                if($result2 = mysqli_query($link2, $sql)){
                    if(mysqli_num_rows($result2) == 1){
                        while($rowrole = mysqli_fetch_array($result2)){
                            $rolefromdb = $rowrole['role'];
                        }
                    }

                }
                else{
                    die("ERROR: Could not able to execute $sql. " . mysqli_error($link));
                }
                echo "<td>" . $rolefromdb . "</td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table></div>";
        }
    }
   }
if($request == "groups" or $request == "groups/"){
       include_once "nps/widgets/dash.php";
       $requestsuccess = true;
        if(isset($_POST["delGroup"])){
            $sql = "DELETE FROM skymake_classes WHERE classid='".$_POST['groupid']."'";
            if(mysqli_query($link,$sql)){
                $sql = "DELETE FROM skymake_class_assigned WHERE classid='".$_POST['groupid']."'";
                if(mysqli_query($link,$sql)){
                    $sql = "DELETE FROM skymake_assignments WHERE classid='".$_POST['groupid']."'";
                    if(mysqli_query($link,$sql)){
                        echo _("Success!");
                    }
                    else {
                        echo _("ERROR. At step three MySQL encountered an error:").mysqli_error($link);
                    }
                }
                else {
                    echo _("ERROR. At step two MySQL encountered an error:").mysqli_error($link);
                }
            }
            else {
                echo _("ERROR. At step one MySQL encountered an error:").mysqli_error($link);
            }
        }
        if(isset($_POST["addGroup"])){
            $sql = "INSERT INTO skymake_classes(classname) VALUES ('".$_POST["groupname"]."')";
            if(mysqli_query($link,$sql)){
                echo _("Success!");
            }else {
                echo _("MySQL has encountered an error while creating group. ").mysqli_error($link);
            }
        }
       ?>
       <div style="text-align: center; padding-top: 100px; border-bottom-width: thin; border-bottom-color: #4e555b; border-bottom-style: solid;">
        <form method="post" style="width:800px; text-align: center; margin-right:auto; margin-left: auto; padding-bottom:10px;">
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">G@ID</span>
                </div>
                <select class="custom-select" id="inputGroupSelect04" name="groupid">
                <?php
                $sql = "SELECT * FROM skymake_classes";
                if($result = mysqli_query($link,$sql)){
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_array($result)) {
                            echo "<option value='".$row["classid"]."'>".$row["classname"]."</option>";
                        }
                    }
                }else {
                    echo _("SQL Error:").$sql.mysqli_error($link);
                }
                ?>
                </select>
            </div>
            <button type="submit" name="delGroup" class="btn btn-outline-dark" style="margin-top: 20px;"><?= _("Delete Group") ?></button>
            </div>
        </form>
       </div>
    <div style="text-align: center; padding-top: 100px; border-bottom-width: thin; border-bottom-color: #4e555b; border-bottom-style: solid;">
        <form method="post" style="width:800px; text-align: center; margin-right:auto; margin-left: auto; padding-bottom:10px;">
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1"><?= _("Group Name") ?></span>
                </div>
                <input type="text" class="form-control" placeholder="<?= _("Group Name") ?>" name="groupname" aria-label="groupname" aria-describedby="basic-addon1">
            </div>
            <button type="submit" name="addGroup" class="btn btn-outline-dark" style="margin-top: 20px;"><?= _("Create") ?></button>
    </div>
    </form>
    </div>
<?php
$sql = "SELECT * FROM skymake_classes";
if ($res = mysqli_query($link, $sql)) {
        if (mysqli_num_rows($res) > 0) {
            echo '<div style="text-align: center;">';
            echo "<table class='table' style='width:80%; margin-right: auto; margin-left: auto;'>";
            echo "<thead>";
            echo "<tr>";
            echo "<th scope='col'>"._("Group ID")."</th>";
            echo "<th scope='col'>"._("Group Name")."</th>";
            echo "<th scope='col'>"._("Edit Users")."</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            while ($row = mysqli_fetch_array($res)) {
                echo "<tr>";
                echo "<td>" . $row['classid'] . "</td>";
                echo "<td>" . $row['classname'] . "</td>";
                echo "<td><a href='/editgroup/".$row["classid"]."'>"._("Edit")."</a></td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table></div>";
        }
    }
}
if (substr($request, 0, 10) === "editgroup/") {
    $requestsuccess = true;
    include "nps/widgets/dash.php";
    $gid_len = strlen($request);
    $gid = substr($request, 10, $gid_len);
    $gid = str_replace("/", "", $gid);
    $gname = "";
    $sql = "SELECT * FROM skymake_classes WHERE classid='".$gid."'";
    if ($res = mysqli_query($link, $sql)) {
        if (mysqli_num_rows($res) == 1) {
            while($row = mysqli_fetch_array($res)){
                $gname = $row["classname"];
            }
        }else{
            die("There is no such group.");
        }

    }else{
        die("There was an error with MySQL. Error:".mysqli_error($link));
    }
    if(isset($_GET["deluser"])){
        $sql = "DELETE FROM skymake_class_assigned WHERE classid='".$gid."' and username='".$_GET["deluser"]."'";
        if (mysqli_query($link, $sql)) {
            echo _("Success");
        }else{
            echo _("Failed while deleting. MySQL has encountered an error: ").mysqli_error($link);
        }
    }
    if(isset($_POST["addUser"])){
        $sql = "INSERT INTO skymake_class_assigned (classid,username) VALUES ('".$gid."','".$_POST["username"]."')";
        if (mysqli_query($link, $sql)) {
            echo _("Success");
        }else{
            echo _("Failed while deleting. MySQL has encountered an error: ").mysqli_error($link);
        }
    }
    ?>
    <div style="text-align: center; padding-top: 100px; border-bottom-width: thin; border-bottom-color: #4e555b; border-bottom-style: solid;">
        <h2><?= _("Editing Group:") ?> <?php echo $gname; ?></h2>
        <form method="post" style="width:800px; text-align: center; margin-right:auto; margin-left: auto; padding-bottom:10px;">
            <select style="margin-bottom: 30px;" class="custom-select" id="inputGroupSelect04" aria-label="Select User" name="username">
                <?php
                $sql = "SELECT * FROM skymake_users";
                if($result = mysqli_query($link,$sql)){
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_array($result)) {
                            echo "<option>".$row["username"]."</option>";
                        }
                    }
                }else {
                    echo _("SQL Error:").mysqli_error($link);
                }
                ?>
            </select>
            <button type="submit" name="addUser" class="btn btn-outline-dark" style="margin-top: 20px;"><?= _("Add User") ?></button>
    </div>
    </form>
    </div>
<?php
    $sql = "SELECT * FROM skymake_class_assigned WHERE classid='".$gid."'";
    if ($res = mysqli_query($link, $sql)) {
        if (mysqli_num_rows($res) > 0) {
            echo '<div style="text-align: center;">';
            echo "<table class='table' style='width:80%; margin-right: auto; margin-left: auto;'>";
            echo "<thead>";
            echo "<tr>";
            echo "<th scope='col'>"._("Username")."</th>";
            echo "<th scope='col'>"._("Delete Users")."</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            while ($row = mysqli_fetch_array($res)) {
                echo "<tr>";
                echo "<td>" . $row['username'] . "</td>";
                echo "<td><a href='?deluser=".$row["username"]."'>"._("Delete")."</a></td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table></div>";
        }
    }
}
    if($request == "courses" or $request == "courses/"){
        include_once "nps/widgets/dash.php";
        $requestsuccess = true;
        if(isset($_POST["delCourse"])){
            $sql = "DELETE FROM skymake_assignments WHERE lessonid='".$_POST["courseid"]."'";
            if(mysqli_query($link,$sql)){
                echo _("Success!");
            }else {
                echo _("MySQL has encountered an error while creating group. ").mysqli_error($link);
            }
        }
        if(isset($_POST["createCourse"])){
            $sql = "INSERT INTO skymake_assignments(lessonid,lesson,teacher,teacheruser,time,topic,unit,bgurl,classid) VALUES ('".$_POST["courseid"]."','".$_POST["lesson"]."','".$_POST["teacher"]."','".$_POST["teacheruser"]."','".$_POST["date"]." ".$_POST["hour"]."','".$_POST["topic"]."','".$_POST["unit"]."','".$_POST["bgurl"]."','".$_POST["classid"]."')";
            if(mysqli_query($link,$sql)){
                echo _("Success!");
            }else {
                echo "MySQL has encountered an error while creating group. ".mysqli_error($link);
            }
        }
        ?>
        <div style="text-align: center; padding-top: 100px; border-bottom-width: thin; border-bottom-color: #4e555b; border-bottom-style: solid;">
            <form method="post" style="width:800px; text-align: center; margin-right:auto; margin-left: auto; padding-bottom:10px;">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1"><?= _("Course ID") ?></span>
                    </div>
                    <select class="custom-select" id="inputGroupSelect04" name="courseid">
                        <option selected><?= _("Choose Course to Delete") ?></option>
                        <?php
                        $sql = "SELECT * FROM skymake_assignments";
                        if($result = mysqli_query($link,$sql)){
                            if(mysqli_num_rows($result) > 0){
                                while($row = mysqli_fetch_array($result)){
                                    $sql1 = "SELECT * FROM skymake_classes WHERE classid='".$row["classid"]."'";
                                    if($result1 = mysqli_query($linktwo,$sql1)){
                                        if(mysqli_num_rows($result1) == 1){
                                            while($row1 = mysqli_fetch_array($result1)){
                                                echo "<option value='".$row["lessonid"]."'>".$row["lessonid"]." | ".$row["lesson"]." - ".$row["topic"]." | ".$row["teacher"]." - ".$row1["classname"]."</option>";
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        ?>
                    </select>
                </div>
                <button type="submit" name="delCourse" class="btn btn-outline-dark" style="margin-top: 20px;"><?= _("Delete Course") ?></button>
        </div>
        </form>
        </div>
        <div style="text-align: center; padding-top: 100px; border-bottom-width: thin; border-bottom-color: #4e555b; border-bottom-style: solid;">
            <form method="post" style="width:800px; text-align: center; margin-right:auto; margin-left: auto; padding-bottom:10px;">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1"><?= _("Course ID") ?></span>
                    </div>
                    <input type="text" class="form-control" placeholder="<?= _("Course ID") ?>" name="courseid" aria-label="courseid" aria-describedby="basic-addon1" value="<?php echo strtoupper(substr(md5(microtime()),rand(0,26),5)); ?>">
                </div>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1"><?= _("Lesson") ?></span>
                    </div>
                    <input type="text" class="form-control" placeholder="<?= _("Lesson Name") ?>" name="lesson" aria-label="lesson" aria-describedby="basic-addon1">
                </div>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1"><?= _("Teacher") ?></span>
                    </div>
                    <input type="text" class="form-control" placeholder="<?= _("Teacher's Real Name") ?>" name="teacher" aria-label="Teacher's Real Name" aria-describedby="basic-addon1">
                </div>
                <div class="input-group mb-3" style="margin-bottom: 30px;">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><?= _("Teacher's Username") ?></span>
                    </div>
                <select class="custom-select" id="inputGroupSelect04" aria-label="Select User" name=teacheruser>
                    <?php
                    $sql = "SELECT * FROM skymake_users";
                    if($result = mysqli_query($link,$sql)){
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_array($result)) {
                                if(SMUser::getRole($link,$row["username"]) == "teacher") {
                                    echo "<option>" . $row["username"] . "</option>";
                                }
                            }
                        }
                    }else {
                        echo _("SQL Error:") .$sql .mysqli_error($link);
                    }
                    ?>
                </select>
                </div>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1"><?= _("Topic") ?></span>
                    </div>
                    <input type="text" class="form-control" placeholder="<?= _("Topic") ?>" name="topic" aria-label="topic" aria-describedby="basic-addon1">
                </div>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1"><?= _("Unit") ?></span>
                    </div>
                    <input type="text" class="form-control" placeholder="<?= _("Unit") ?>" name="unit" aria-label="unit" aria-describedby="basic-addon1">
                </div>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1"><?= _("Cover Image URL") ?></span>
                    </div>
                    <input type="text" class="form-control" placeholder="<?= _("Cover Image URL") ?>" name="bgurl" aria-label="bgimage" aria-describedby="basic-addon1">
                </div>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1"><?= _("Class ID") ?></span>
                    </div>
                    <select class="custom-select" id="inputGroupSelect04" name="classid">
                        <option selected><?= _("Chose a Class") ?></option>
                        <?php
                        $sql = "SELECT * FROM skymake_classes";
                        if($result = mysqli_query($link,$sql)){
                            if(mysqli_num_rows($result) > 0){
                                while($row = mysqli_fetch_array($result)){
                                    $sql1 = "SELECT * FROM skymake_classes WHERE classid='".$row["classid"]."'";
                                    if($result1 = mysqli_query($linktwo,$sql1)){
                                        if(mysqli_num_rows($result1) == 1){
                                            while($row1 = mysqli_fetch_array($result1)){
                                                echo "<option value='".$row["classid"]."'>".$row1["classname"]."</option>";
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        ?>
                    </select>
                </div>
                <label for="exam-date"><?= _("Course Date:") ?></label>
                <input type="date" id="date" name="date">
                <label for="exam-start"><?= _("Course Time:") ?></label>
                <input type="time" id="hour" name="hour" value="15:16:00"><br>
                <button type="submit" name="createCourse" class="btn btn-outline-dark" style="margin-top: 20px;"><?= _("Create Course") ?></button>
        </div>
        </form>
        </div>
        <?php
        $sql = "SELECT * FROM skymake_assignments";
        if ($res = mysqli_query($link, $sql)) {
            if (mysqli_num_rows($res) > 0) {
                echo '<div style="text-align: center;">';
                echo "<table class='table' style='width:80%; margin-right: auto; margin-left: auto;'>";
                echo "<thead>";
                echo "<tr>";
                echo "<th scope='col'>"._("Course ID")."</th>";
                echo "<th scope='col'>"._("Lesson Name")."</th>";
                echo "<th scope='col'>"._("Teacher")."</th>";
                echo "<th scope='col'>"._("Topic")."</th>";
                echo "<th scope='col'>"._("Assigned Class ID")."</th>";
                echo "<th scope='col'>"._("Edit")."</th>";
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";
                while ($row = mysqli_fetch_array($res)) {
                    echo "<tr>";
                    echo "<td>" . $row['lessonid'] . "</td>";
                    echo "<td>" . $row['lesson'] . "</td>";
                    echo "<td>" . $row['teacher'] . "</td>";
                    echo "<td>" . $row['topic'] . "</td>";
                    echo "<td>" . $row['classid'] . "</td>";
                    echo "<td><a href='/lessoncontent/".$row["lessonid"]."'>"._("Edit")."</a></td>";
                    echo "</tr>";
                }
                echo "</tbody>";
                echo "</table></div>";
            }
        }
    }
    if (substr($request, 0, 14) === "lessoncontent/") {
        $requestsuccess = true;
        include "nps/widgets/dash.php";
        $cid_len = strlen($request);
        $cid = substr($request, 14, $cid_len);
        $cid = str_replace("/", "", $cid);
        if(isset($_GET["delcontent"])){
            $sql = "DELETE FROM skymake_lessoncontent WHERE `content-id`='".$_GET["delcontent"]."'";
            if (mysqli_query($link, $sql)) {
                echo _("Success");
            }else{
                echo _("Failed while deleting. MySQL has encountered an error: ").mysqli_error($link);
            }
        }
            if(isset($_POST["addLC"])){
                $sql = "INSERT INTO skymake_lessoncontent (lessonid,`content-id`,`content-type`,`content-link`) VALUES ('".$cid."','".$_POST["llcid"]."','"."Live Class"."','"."/liveclass/".$_POST["llcid"]."')";
                if(mysqli_query($link,$sql)){
                    echo _("Success!");
                }else{
                    echo _("Error. ").mysqli_error($link);
                }
            }
            if(isset($_POST["addExam"])){
                $sql = "INSERT INTO skymake_lessoncontent (lessonid,`content-id`,`content-type`,`content-link`) VALUES ('".$cid."','".$_POST["examid"]."','"."Online Exam"."','"."/oes/?examid=".$_POST["examid"]."')";
                if(mysqli_query($link,$sql)){
                    echo _("Success!");
                }else{
                    echo _("Error. ").mysqli_error($link);
                }
            }
            if(isset($_POST["addUpload"])){
                $sql = "SELECT uploadlink FROM skymake_useruploads WHERE `upload_id`='".$_POST["uploadid"]."'";
                $c_link = "";
                if ($res = mysqli_query($link, $sql)) {
                    if(mysqli_num_rows($res) == 1){
                        while($row = mysqli_fetch_array($res)){
                            $c_link = $row["uploadlink"];
                        }
                    }else{
                        die(_("No such upload."));
                    }
                }
                $sql = "INSERT INTO skymake_lessoncontent (lessonid,`content-id`,`content-type`,`content-link`) VALUES ('".$cid."','".$_POST["uploadid"]."','"."Document"."','".$c_link."')";
                if(mysqli_query($link,$sql)){
                    echo _("Success!");
                }else{
                    echo _("Error. ").mysqli_error($link);
                }
            }

        ?>
        <div style="text-align: center; padding-top: 100px; border-bottom-width: thin; border-bottom-color: #4e555b; border-bottom-style: solid;">
            <h2><?= _("Editing Course:") ?> <?php echo $cid; ?></h2>
            <form method="post" style="width:800px; text-align: center; margin-right:auto; margin-left: auto; padding-bottom:10px;">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1"><?= _("Live Class") ?></span>
                    </div>
                    <input type="text" class="form-control" placeholder="<?= _("Content ID") ?>" aria-label="Content ID" name="llcid" aria-describedby="basic-addon1">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" name="addLC" type="submit"><?= _("Create Live Class") ?></button>
                    </div>
                </div>
                <div class="input-group">
                    <select class="custom-select" id="inputGroupSelect04" name="examid">
                        <option selected><?= _("Choose an online exam to assign") ?></option>
                        <?php
                        $sql = "SELECT * FROM skymake_examdata";
                        if($result = mysqli_query($link,$sql)){
                            if(mysqli_num_rows($result) > 0){
                                while($row = mysqli_fetch_array($result)){
                                    echo "<option value='".$row["examid"]."'>".$row["examid"]." - ".$row["exam_name"]."</option>";
                                }
                            }
                        }else{
                            echo _("Could not list exams. SQL Error.");
                        }
                        ?>
                    </select>
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="submit" name="addExam"><?= _("Add Exam") ?></button>
                    </div>
                </div>
                <div class="input-group" style="padding-top: 15px;">
                    <select class="custom-select" id="inputGroupSelect04" name="uploadid">
                        <option selected><?= _("Choose an upload to assign") ?></option>
                        <?php
                        $sql = "SELECT * FROM skymake_useruploads";
                        if($result = mysqli_query($link,$sql)){
                            if(mysqli_num_rows($result) > 0){
                                while($row = mysqli_fetch_array($result)){
                                    echo "<option value='".$row["upload_id"]."'>".$row["uploadlink"]." - ".$row["upload_id"]."</option>";
                                }
                            }
                        }else{
                            echo _("Could not list uploads. SQL Error.");
                        }
                        ?>
                    </select>
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="submit" name="addUpload"><?= _("Assign Upload") ?></button>
                    </div>
                </div>
        </div>
        </form>
        </div>
        <?php
        $sql = "SELECT * FROM skymake_lessoncontent WHERE lessonid='".$cid."'";
        if ($res = mysqli_query($link, $sql)) {
            if (mysqli_num_rows($res) > 0) {
                echo '<div style="text-align: center;">';
                echo "<table class='table' style='width:80%; margin-right: auto; margin-left: auto;'>";
                echo "<thead>";
                echo "<tr>";
                echo "<th scope='col'>"._("Content ID")."</th>";
                echo "<th scope='col'>"._("Content Type")."</th>";
                echo "<th scope='col'>"._("Delete")."</th>";
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";
                while ($row = mysqli_fetch_array($res)) {
                    echo "<tr>";
                    echo "<td>" . $row['content-id'] . "</td>";
                    echo "<td>" . $row['content-type'] . "</td>";
                    echo "<td><a href='?delcontent=".$row["content-id"]."'>"._("Delete")."</a></td>";
                    echo "</tr>";
                }
                echo "</tbody>";
                echo "</table></div>";
            }
        }
    }
if($request == "examcreate" or $request == "examcreate/"){
    include "nps/widgets/dash.php";
    $requestsuccess = true;
    if(isset($_POST["createExam"])){
        $sql = "INSERT INTO skymake_examdata (examid,exam_name,exam_start,exam_end,exam_qcount,exam_type,exam_creator) VALUES ('".$_POST["exam-id"]."','".$_POST["exam-name"]."','".$_POST["exam-date"]." ".$_POST["exam-start"]."','".$_POST["exam-date"]." ".$_POST["exam-end"]."','".$_POST["exam-qcount"]."','standard','no-one')";
        if(mysqli_query($link,$sql)){
            echo _("Success!");
        }else {
            echo _("Error").$sql.mysqli_error($link);
        }
    }
    if(isset($_GET["delexam"])){
        $sql = "DELETE FROM skymake_examdata WHERE examid='".$_GET["delexam"]."'";
        if(mysqli_query($link,$sql)){
            echo _("Success!");
        }else {
            echo _("Error ").$sql.mysqli_error($link);
        }
    }
    ?>
    <div style="text-align: center; padding-top: 100px; border-bottom-width: thin; border-bottom-color: #4e555b; border-bottom-style: solid;">
        <form method="post" style="width:800px; text-align: center; margin-right:auto; margin-left: auto; padding-bottom:10px;">
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1"><?= _("Name") ?></span>
                </div>
                <input type="text" class="form-control" placeholder="<?= _("Exam Name") ?>" name="exam-name" aria-label="Exam Name" aria-describedby="basic-addon1">
            </div>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1"><?= _("Exam ID") ?></span>
                </div>
                <input type="text" class="form-control" placeholder="<?= _("Exam ID") ?>" name="exam-id" aria-label="Exam ID" aria-describedby="basic-addon1" value="OES<?php echo  mt_rand(1000,9999); ?>">
            </div>
            <label for="exam-date"><?= _("Exam Date:") ?></label>
            <input type="date" id="exam-date" name="exam-date">
            <label for="exam-start"><?= _("Exam Start:") ?></label>
            <input type="time" id="exam-start" name="exam-start" value="15:16:00">
            <label for="exam-end"><?= _("Exam End:") ?></label>
            <input type="time" id="exam-end" name="exam-end" value="15:16:00">
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1"><?= _("Number of Questions") ?></span>
                </div>
                <input type="text" class="form-control" placeholder="<?= _("Number of Questions") ?>" name="exam-qcount" aria-label="Exam Question Count" aria-describedby="basic-addon1">
            </div>
            <button type="submit" name="createExam" class="btn btn-outline-dark" style="margin-top: 20px;"><?= _("Create Exam") ?></button>
    </div>
    </form>
    </div>
<?php
    $sql = "SELECT * FROM skymake_examdata";
    if ($res = mysqli_query($link, $sql)) {
        if (mysqli_num_rows($res) > 0) {
            echo '<div style="text-align: center;">';
            echo "<table class='table' style='width:80%; margin-right: auto; margin-left: auto;'>";
            echo "<thead>";
            echo "<tr>";
            echo "<th scope='col'>"._("Exam ID")."</th>";
            echo "<th scope='col'>"._("Exam Name")."</th>";
            echo "<th scope='col'>"._("Start")."</th>";
            echo "<th scope='col'>"._("End")."</th>";
            echo "<th scope='col'>"._("Questions")."</th>";
            echo "<th scope='col'>"._("Edit")."</th>";
            echo "<th scope='col'>"._("Delete")."</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            while ($row = mysqli_fetch_array($res)) {
                echo "<tr>";
                echo "<td>" . $row['examid'] . "</td>";
                echo "<td>" . $row['exam_name'] . "</td>";
                echo "<td>" . $row['exam_start'] . "</td>";
                echo "<td>" . $row['exam_end'] . "</td>";
                echo "<td>" . $row['exam_qcount'] . "</td>";
                echo "<td><a href='/examcreator/?examid=" . $row['examid'] . "'>"._("Edit")."</a></td>";
                echo "<td><a href='?delexam=" . $row['examid'] . "'>"._("Delete")."</a></td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table></div>";
        }
    }
}
}
if($requestsuccess == false){
    include "nps/notfound.php";
}
if($requestsuccess){
?>
<div class="footer" id="mainfooter" style="<?php if($dm == "off"){ echo "text-align: center; margin-top: 50px; border: 2px solid lightgray; height: 40px;"; } else { echo "text-align: center; margin-top: 50px; border: 2px solid black; height: 40px; color:white;"; }?>" >
    <p style="margin-top: 6px;">SkyMake 4 by Skyfallen. All Rights Reseved &copy; 2016-2020 The Skyfallen Company.<?php echo _("Build Number:").THIS_VERSION; ?></p>
</div>
<?php } ?>
<script src="nps/widgets/assets/js/jquery.min.js"></script>
<script src="nps/widgets/assets/bootstrap/js/bootstrap.min.js"></script>
<script src="nps/widgets/assets/js/Animated-Type-Heading.js"></script>
</body>
</html>

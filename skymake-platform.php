<?php
$request = $_GET["request"];
$requestsuccess = false;
error_reporting(E_ALL);
ini_set('display_errors', 1);
if(substr($request ,0 ,1)=="/"){
 $request = substr($request,1,strlen($request)-1);
}
include "SkyMakeFunctionSet/Operation-Requirements/MainFunctions.php";
include "SkyMakeDatabaseConnector/SkyMakeDBconfig.php";
include "classes/user.php";
include "SkyMakeDatabaseConnector/db-class.php";
include "SkyMakeConfiguration.php";
session_name('SkyMakeSessionStorage');
session_start();
if(!$_SESSION["loggedin"]){
    header("Location: /?act=signin");
}
//get user role
$_SESSION["user_role"] = $user_role = SMUser::getRole($link,$_SESSION["username"]);

if($user_role == "unverified"){
    include "nps/widgets/dash.php";
    include "nps/errors/notapproved.html";
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
            echo("<div class='text-center'><h1>Lesson Details | " . $lessonname . "</h1></div>");
            echo(overview(getassignedlessons($link)[$n], getassignedteachers($link)[$n], getassignedtimes($link)[$n], getassignedtopics($link)[$n], getassignedunits($link)[$n], getassignedbgurls($link)[$n], getassignedids($link)[$n], getlessoncontents($link, $cenroller)));
        } else {
            echo("<div class='text-center'><h1>This lesson does not exist. Please access your course by dashbboard.</h1></div>");
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
            echo("<div class='text-center'><h1>Live Class | SkyfallenLiveConnect ID:" . $cenroller . "</h1></div>");
            echo("<script src='https://".SFLC_HOST."/external_api.js'></script>
        <script>
        const domain = '".SFLC_HOST."';
        const options = {
            roomName: 'SkyMake4/LiveClasses/" . $cenroller . "/" . $lctoken[0] . "',
            width: self.innerWidth,
            height: self.innerHeight,
            parentNode: undefined
        };
        const api = new JitsiMeetExternalAPI(domain, options);
        </script>");
        } else {
            if (!isContentValid($link, $cenroller)) {
                echo("<div class='text-center'><h1>This lesson does not exist. Please access your course by your own dashboard.</h1></div>");
            } else {
                echo("<div class='text-center'><h1>This meeting has not started.</h1></div>");
            }
        }
    }
    if ($request == "profile" or $request == "profile/") {
        $requestsuccess = true;
        include "nps/widgets/dash.php";
        echo("<a href='/logout'><h1>Log Out</h1></a>");
    }
    if ($request == "logout" or $request == "logout/") {
        $requestsuccess = true;
        include "nps/widgets/dash.php";
        echo("<h1>Logging you out...</h1>");
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
        echo("<div class=\"caption v-middle text-center\"><h1 class=\"cd-headline clip\"><span class=\"blc\">Welcome to the new dashboard.</span><br><span class=\"cd-words-wrapper\"><b class=\"is-visible\">Here are your courses.</b><b>Here are your grades.</b><b>Here are your online exams.</b></span></h1> </div>");
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
            echo("<div class=\"text-center\"><h1>You have no active courses.</h1></div>");
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
            echo("<div class=\"text-center\"><h1>You have no active courses.</h1></div>");
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
            echo("<div class='text-center'><h1>Lesson Details | " . $lessonname . "</h1></div>");
            echo(overview(getassignedlessonsteacher($link)[$n], "Assigned to me", getassignedtimesteacher($link)[$n], getassignedtopicsteacher($link)[$n], getassignedunitsteacher($link)[$n], getassignedbgurlsteacher($link)[$n], getassignedidsteacher($link)[$n], getlessoncontents($link, $cenroller)));
        } else {
            echo("<div class='text-center'><h1>This lesson does not exist. Please access your course by dashbboard.</h1></div>");
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
            echo("<div class='text-center'><h1>Live Class | SkyfallenLiveConnect ID:" . $cenroller . "</h1></div>");
            echo("<script src='https://".SFLC_HOST."/external_api.js'></script>
        <script>
        const domain = '".SFLC_HOST."';
        const options = {
            roomName: 'SkyMake4/LiveClasses/" . $cenroller . "/" . $lctoken[0] . "',
            width: self.innerWidth,
            height: self.innerHeight,
            parentNode: undefined
        };
        const api = new JitsiMeetExternalAPI(domain, options);
        </script>");
        } else {
            if (!isContentValid($link, $cenroller)) {
                echo("<div class='text-center'><h1>This lesson does not exist. Please access your course by your own dashboard.</h1></div>");
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
                        die("This content was not assigned to any course!");
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
                            die("This content was not assigned to any course!");
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
                    die("An error occured.");
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
        echo("<a href='/logout'><h1>Log Out</h1></a>");
    }
    if ($request == "logout" or $request == "logout/") {
        $requestsuccess = true;
        include "nps/widgets/dash.php";
        echo("<h1>Logging you out...</h1>");
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
        echo("<div class=\"caption v-middle text-center\"><h1 class=\"cd-headline clip\"><span class=\"blc\">Welcome to the new dashboard.</span><br><span class=\"cd-words-wrapper\"><b class=\"is-visible\">Here are your courses.</b><b>Here are your grades.</b><b>Here are your online exams.</b></span></h1> </div>");
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
            echo("<div class=\"text-center\"><h1>You are free for now.</h1></div>");
        }

    }
}
if($user_role == "admin") {
   if($request == "upload" or $request == "upload/"){
       $requestsuccess = true;
       include "userupload.php";
   }
   if($request == "home" or $request == "home/"){
       include_once "nps/widgets/dash.php";
       $requestsuccess = true;
   }
   if($request == "users" or $request == "users/"){
       include_once "nps/widgets/dash.php";
       $requestsuccess = true;
       if(isset($_POST["deluser"])){
           $sql = "DELETE FROM skymake_users WHERE username='".$_POST["username"]."'";
           if($result = mysqli_query($link, $sql)){
               $sql = "DELETE FROM skymake_roles WHERE username='".$_POST["username"]."'";
               if($result = mysqli_query($link, $sql)){
                    echo "Success!";
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
                        <button style="margin-bottom: 30px;" class="btn btn-outline-secondary" type="submit" name="deluser" >Delete User</button>
                    </div>
                </div>
            <div class="input-group">
                <select class="custom-select" id="inputGroupSelect04" name="newRole">
                    <option selected>Choose a new role to set...</option>
                    <option value="admin">Administrator</option>
                    <option value="student">Student</option>
                    <option value="teacher">Teacher</option>
                </select>
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="submit" name="setRole">Set Role</button>
                </div>
            </div>
            <button type="submit" name="deluser" class="btn btn-outline-dark" style="margin-top: 20px;">Delete User</button>
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
            echo "<th scope='col'>Username</th>";
            echo "<th scope='col'>User Role</th>";
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
                            mysqli_free_result($result2);
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
                        echo "Success! ".$_POST["groupid"].".";
                    }
                    else {
                        echo "ERROR. At step three MySQL encountered an error:".mysqli_error($link);
                    }
                }
                else {
                    echo "ERROR. At step two MySQL encountered an error:".mysqli_error($link);
                }
            }
            else {
                echo "ERROR. At step one MySQL encountered an error:".mysqli_error($link);
            }
        }
        if(isset($_POST["addGroup"])){
            $sql = "INSERT INTO skymake_classes(classname) VALUES ('".$_POST["groupname"]."')";
            if(mysqli_query($link,$sql)){
                echo "Success!";
            }else {
                echo "MySQL has encountered an error while creating group. ".mysqli_error($link);
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
                    echo "SQL Error: $sql . ".mysqli_error($link);
                }
                ?>
                </select>
            </div>
            <button type="submit" name="delGroup" class="btn btn-outline-dark" style="margin-top: 20px;">Delete Group</button>
            </div>
        </form>
       </div>
    <div style="text-align: center; padding-top: 100px; border-bottom-width: thin; border-bottom-color: #4e555b; border-bottom-style: solid;">
        <form method="post" style="width:800px; text-align: center; margin-right:auto; margin-left: auto; padding-bottom:10px;">
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">G@Name</span>
                </div>
                <input type="text" class="form-control" placeholder="Group Name" name="groupname" aria-label="groupname" aria-describedby="basic-addon1">
            </div>
            <button type="submit" name="addGroup" class="btn btn-outline-dark" style="margin-top: 20px;">Create Group</button>
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
            echo "<th scope='col'>Group ID</th>";
            echo "<th scope='col'>Group Name</th>";
            echo "<th scope='col'>Edit Users</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            while ($row = mysqli_fetch_array($res)) {
                echo "<tr>";
                echo "<td>" . $row['classid'] . "</td>";
                echo "<td>" . $row['classname'] . "</td>";
                echo "<td><a href='/editgroup/".$row["classid"]."'>Edit</a></td>";
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
            echo "Success";
        }else{
            echo "Failed while deleting. MySQL has encountered an error: ".mysqli_error($link);
        }
    }
    if(isset($_POST["addUser"])){
        $sql = "INSERT INTO skymake_class_assigned (classid,username) VALUES ('".$gid."','".$_POST["username"]."')";
        if (mysqli_query($link, $sql)) {
            echo "Success";
        }else{
            echo "Failed while deleting. MySQL has encountered an error: ".mysqli_error($link);
        }
    }
    ?>
    <div style="text-align: center; padding-top: 100px; border-bottom-width: thin; border-bottom-color: #4e555b; border-bottom-style: solid;">
        <h2>Editing Group: <?php echo $gname; ?></h2>
        <form method="post" style="width:800px; text-align: center; margin-right:auto; margin-left: auto; padding-bottom:10px;">
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">@</span>
                </div>
                <input type="text" class="form-control" placeholder="Username" name="username" aria-label="Username" aria-describedby="basic-addon1">
            </div>
            <button type="submit" name="addUser" class="btn btn-outline-dark" style="margin-top: 20px;">Add User</button>
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
            echo "<th scope='col'>Username</th>";
            echo "<th scope='col'>Delete Users</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            while ($row = mysqli_fetch_array($res)) {
                echo "<tr>";
                echo "<td>" . $row['username'] . "</td>";
                echo "<td><a href='?deluser=".$row["username"]."'>Delete</a></td>";
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
                echo "Success!";
            }else {
                echo "MySQL has encountered an error while creating group. ".mysqli_error($link);
            }
        }
        if(isset($_POST["createCourse"])){
            $sql = "INSERT INTO skymake_assignments(lessonid,lesson,teacher,teacheruser,time,topic,unit,bgurl,classid) VALUES ('".$_POST["courseid"]."','".$_POST["lesson"]."','".$_POST["teacher"]."','".$_POST["teacheruser"]."','".$_POST["date"]." ".$_POST["hour"]."','".$_POST["topic"]."','".$_POST["unit"]."','".$_POST["bgurl"]."','".$_POST["classid"]."')";
            if(mysqli_query($link,$sql)){
                echo "Success!";
            }else {
                echo "MySQL has encountered an error while creating group. ".mysqli_error($link);
            }
        }
        ?>
        <div style="text-align: center; padding-top: 100px; border-bottom-width: thin; border-bottom-color: #4e555b; border-bottom-style: solid;">
            <form method="post" style="width:800px; text-align: center; margin-right:auto; margin-left: auto; padding-bottom:10px;">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">C@ID</span>
                    </div>
                    <select class="custom-select" id="inputGroupSelect04" name="courseid">
                        <option selected>Choose a Course to Delete...</option>
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
                <button type="submit" name="delCourse" class="btn btn-outline-dark" style="margin-top: 20px;">Delete Course</button>
        </div>
        </form>
        </div>
        <div style="text-align: center; padding-top: 100px; border-bottom-width: thin; border-bottom-color: #4e555b; border-bottom-style: solid;">
            <form method="post" style="width:800px; text-align: center; margin-right:auto; margin-left: auto; padding-bottom:10px;">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">C@ID</span>
                    </div>
                    <input type="text" class="form-control" placeholder="Course ID" name="courseid" aria-label="courseid" aria-describedby="basic-addon1">
                </div>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">Lesson</span>
                    </div>
                    <input type="text" class="form-control" placeholder="Lesson Name" name="lesson" aria-label="lesson" aria-describedby="basic-addon1">
                </div>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">Teacher</span>
                    </div>
                    <input type="text" class="form-control" placeholder="teacher" name="teacher" aria-label="teacher" aria-describedby="basic-addon1">
                </div>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">Teacher Username</span>
                    </div>
                    <input type="text" class="form-control" placeholder="Teacher Username" name="teacheruser" aria-label="teacheruser" aria-describedby="basic-addon1">
                </div>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">Topic</span>
                    </div>
                    <input type="text" class="form-control" placeholder="Topic" name="topic" aria-label="topic" aria-describedby="basic-addon1">
                </div>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">Unit</span>
                    </div>
                    <input type="text" class="form-control" placeholder="Unit" name="unit" aria-label="unit" aria-describedby="basic-addon1">
                </div>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">BG Image</span>
                    </div>
                    <input type="text" class="form-control" placeholder="Background Image" name="bgurl" aria-label="bgimage" aria-describedby="basic-addon1">
                </div>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">Class ID</span>
                    </div>
                    <select class="custom-select" id="inputGroupSelect04" name="classid">
                        <option selected>Choose a Class</option>
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
                <label for="exam-date">Course Date:</label>
                <input type="date" id="date" name="date">
                <label for="exam-start">Course Time:</label>
                <input type="time" id="hour" name="hour" value="15:16:00"><br>
                <button type="submit" name="createCourse" class="btn btn-outline-dark" style="margin-top: 20px;">Create Course</button>
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
                echo "<th scope='col'>Course ID</th>";
                echo "<th scope='col'>Lesson Name</th>";
                echo "<th scope='col'>Teacher</th>";
                echo "<th scope='col'>Topic</th>";
                echo "<th scope='col'>Assigned Class ID</th>";
                echo "<th scope='col'>Edit</th>";
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
                    echo "<td><a href='/lessoncontent/".$row["lessonid"]."'>Edit</a></td>";
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
                echo "Success";
            }else{
                echo "Failed while deleting. MySQL has encountered an error: ".mysqli_error($link);
            }
        }
            if(isset($_POST["addLC"])){
                $sql = "INSERT INTO skymake_lessoncontent (lessonid,`content-id`,`content-type`,`content-link`) VALUES ('".$cid."','".$_POST["llcid"]."','"."Live Class"."','"."/liveclass/".$_POST["llcid"]."')";
                if(mysqli_query($link,$sql)){
                    echo "Success!";
                }else{
                    echo "Error. ".mysqli_error($link);
                }
            }
            if(isset($_POST["addExam"])){
                $sql = "INSERT INTO skymake_lessoncontent (lessonid,`content-id`,`content-type`,`content-link`) VALUES ('".$cid."','".$_POST["examid"]."','"."Online Exam"."','"."/oes/?examid=".$_POST["examid"]."')";
                if(mysqli_query($link,$sql)){
                    echo "Success!";
                }else{
                    echo "Error. ".mysqli_error($link);
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
                        die("No such upload.");
                    }
                }
                $sql = "INSERT INTO skymake_lessoncontent (lessonid,`content-id`,`content-type`,`content-link`) VALUES ('".$cid."','".$_POST["uploadid"]."','"."Document"."','".$c_link."')";
                if(mysqli_query($link,$sql)){
                    echo "Success!";
                }else{
                    echo "Error. ".mysqli_error($link);
                }
            }

        ?>
        <div style="text-align: center; padding-top: 100px; border-bottom-width: thin; border-bottom-color: #4e555b; border-bottom-style: solid;">
            <h2>Editing Course: <?php echo $cid; ?></h2>
            <form method="post" style="width:800px; text-align: center; margin-right:auto; margin-left: auto; padding-bottom:10px;">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">Live Lesson</span>
                    </div>
                    <input type="text" class="form-control" placeholder="Content ID" aria-label="Content ID" name="llcid" aria-describedby="basic-addon1">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" name="addLC" type="submit">Create Live Lesson</button>
                    </div>
                </div>
                <div class="input-group">
                    <select class="custom-select" id="inputGroupSelect04" name="examid">
                        <option selected>Choose an Online Exam To Add...</option>
                        <?php
                        $sql = "SELECT * FROM skymake_examdata";
                        if($result = mysqli_query($link,$sql)){
                            if(mysqli_num_rows($result) > 0){
                                while($row = mysqli_fetch_array($result)){
                                    echo "<option value='".$row["examid"]."'>".$row["examid"]." - ".$row["exam_name"]."</option>";
                                }
                            }
                        }else{
                            echo "Could not list exams. SQL Error.";
                        }
                        ?>
                    </select>
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="submit" name="addExam">Add Exam</button>
                    </div>
                </div>
                <div class="input-group" style="padding-top: 15px;">
                    <select class="custom-select" id="inputGroupSelect04" name="uploadid">
                        <option selected>Choose an Upload To Add...</option>
                        <?php
                        $sql = "SELECT * FROM skymake_useruploads";
                        if($result = mysqli_query($link,$sql)){
                            if(mysqli_num_rows($result) > 0){
                                while($row = mysqli_fetch_array($result)){
                                    echo "<option value='".$row["upload_id"]."'>".$row["uploadlink"]." - ".$row["upload_id"]."</option>";
                                }
                            }
                        }else{
                            echo "Could not list uploads. SQL Error.";
                        }
                        ?>
                    </select>
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="submit" name="addUpload">Add Upload</button>
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
                echo "<th scope='col'>Content ID</th>";
                echo "<th scope='col'>Content Type</th>";
                echo "<th scope='col'>Delete</th>";
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";
                while ($row = mysqli_fetch_array($res)) {
                    echo "<tr>";
                    echo "<td>" . $row['content-id'] . "</td>";
                    echo "<td>" . $row['content-type'] . "</td>";
                    echo "<td><a href='?delcontent=".$row["content-id"]."'>Delete</a></td>";
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
            echo "Success!";
        }else {
            echo "Error $sql".mysqli_error($link);
        }
    }
    if(isset($_GET["delexam"])){
        $sql = "DELETE FROM skymake_examdata WHERE examid='".$_GET["delexam"]."'";
        if(mysqli_query($link,$sql)){
            echo "Success!";
        }else {
            echo "Error $sql".mysqli_error($link);
        }
    }
    ?>
    <div style="text-align: center; padding-top: 100px; border-bottom-width: thin; border-bottom-color: #4e555b; border-bottom-style: solid;">
        <form method="post" style="width:800px; text-align: center; margin-right:auto; margin-left: auto; padding-bottom:10px;">
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">Name</span>
                </div>
                <input type="text" class="form-control" placeholder="Exam Name" name="exam-name" aria-label="Exam Name" aria-describedby="basic-addon1">
            </div>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">Exam ID</span>
                </div>
                <input type="text" class="form-control" placeholder="Exam ID" name="exam-id" aria-label="Exam ID" aria-describedby="basic-addon1" value="OES<?php echo  mt_rand(1000,9999); ?>">
            </div>
            <label for="exam-date">Exam Date:</label>
            <input type="date" id="exam-date" name="exam-date">
            <label for="exam-start">Exam Start:</label>
            <input type="time" id="exam-start" name="exam-start" value="15:16:00">
            <label for="exam-end">Exam End:</label>
            <input type="time" id="exam-end" name="exam-end" value="15:16:00">
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">Exam Question Count</span>
                </div>
                <input type="text" class="form-control" placeholder="Exam QCount" name="exam-qcount" aria-label="Exam Question Count" aria-describedby="basic-addon1">
            </div>
            <button type="submit" name="createExam" class="btn btn-outline-dark" style="margin-top: 20px;">Create Exam</button>
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
            echo "<th scope='col'>Exam ID</th>";
            echo "<th scope='col'>Exam Name</th>";
            echo "<th scope='col'>Start</th>";
            echo "<th scope='col'>End</th>";
            echo "<th scope='col'>Questions</th>";
            echo "<th scope='col'>Edit</th>";
            echo "<th scope='col'>Delete</th>";
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
                echo "<td><a href='/examcreator/?examid=" . $row['examid'] . "'>Edit</a></td>";
                echo "<td><a href='?delexam=" . $row['examid'] . "'>Delete</a></td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table></div>";
        }
    }
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

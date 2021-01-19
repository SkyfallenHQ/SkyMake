<?php

include_once "../SkyMakeDatabaseConnector/SkyMakeDBconfig.php";
include_once "../classes/user.php";
include_once "../SkyMakeConfiguration.php";
include_once "../SkyMakeFunctionSet/Operation-Requirements/MainFunctions.php";

global $link;

$linktwo = $link;


// Check if username is empty
if (empty(trim($_GET["username"]))) {
    // Add this error under username box.
    die("Forbidden. Missing auth.");
} else {
    $username = trim($_GET["username"]);
}

// Check if password is empty
if (empty(trim($_GET["password"]))) {
    // Add this error under password.
    die("Forbidden. Missing auth.");
} else {
    $password = trim($_GET["password"]);
}

// Validate credentials
if (empty($username_err) && empty($password_err)) {
    // Prepare a select statement
    $sql = "SELECT id, username, password FROM skymake_users WHERE username = ?";

    if ($stmt = mysqli_prepare($link, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "s", $param_username);

        // Set parameters
        $param_username = $username;

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            // Store result
            mysqli_stmt_store_result($stmt);

            // Check if username exists, if yes then verify password
            if (mysqli_stmt_num_rows($stmt) == 1) {
                // Bind result variables
                mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                if (mysqli_stmt_fetch($stmt)) {
                    if (password_verify($password, $hashed_password)) {
                        // Password is correct, so start a new session
                        session_start();
                        $_SESSION["lc-embedded-username"] = $username;
                        $_SESSION["classid"] = SMUser::getStudentClassID($link,$username);
                        $_SESSION["dm"] = "off";

                    } else {
                        // Display an error message if password is not valid
                        die("Forbidden.");
                    }
                }
            } else {
                die("Forbidden.");
            }
        } else {
            die("Forbidden.");
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }
}

$user_role = SMUser::getRole($link,$username);

switch($user_role){
    default:
        die("NoAuth");
        break;

    case "student":
        $cenroller = $_GET["LCID"];
        $lctoken = getLiveClassToken($link, $cenroller, $_SESSION["classid"]);
        if (isContentValid($link, $cenroller) == true and !($lctoken == false)) {
            $result["meetServer"] = "https://".SFLC_HOST;
            $result["meetCode"] = $lctoken[0];
            echo json_encode($result);
        } else {
            if (!isContentValid($link, $cenroller)) {
                echo("<div class='text-center'><h1>This lesson does not exist. Please access your course by your own dashboard.</h1></div>");
            } else {
                $result["error"] = "notstarted";
                echo json_encode($result);
            }
        }
        break;
        /*
    case "teacher":
        $_SESSION["teacheruser"] = $username;
            $cenroller = $_GET["LCID"];
            $lctoken = getLiveClassToken($link, $cenroller, $_SESSION["classid"],false);
            if (isContentValid($link, $cenroller) == true and !($lctoken == false)) {
                $result["meetServer"] = "https://".SFLC_HOST;
                $result["meetCode"] = $lctoken[0];
                echo json_encode($result);
            } else {
                if (!isContentValid($link, $cenroller)) {
                    echo("<div class='text-center'><h1>This lesson does not exist. Please access your course by your own dashboard.</h1></div>");
                } else {
                    $includedcourses = array();
                    $sql = "SELECT * FROM skymake_lessoncontent WHERE `content-id`='" . $cenroller . "'";
                    if ($result = mysqli_query($link, $sql)) {
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_array($result)) {
                                array_push($includedcourses, $row["lessonid"]);
                            }
                            // Free result set
                            mysqli_free_result($result);
                        } else {
                            die("This content was not assigned to any course!");
                        }
                    } else {
                        die("ERROR: Could not able to execute for course  $sql. " . mysqli_error($link));
                    }
                    sleep(1);
                    $includedgroups = array();
                    foreach ($includedcourses as $oneofcourses) {
                        $sql = "SELECT classid FROM skymake_assignments WHERE lessonid='" . $oneofcourses . "'";
                        if ($result = mysqli_query($linktwo, $sql)) {
                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_array($result)) {
                                    array_push($includedgroups, $row["classid"]);
                                }
                                // Free result set
                                mysqli_free_result($result);
                            } else {
                                die("This content was not assigned to any course!");
                            }
                        } else {
                            die("ERROR: Could not able to execute $sql. " . mysqli_error($linktwo));
                        }
                    }
                }
                $exitret = false;
                $newtoken = md5(uniqid(rand(), true));
                foreach ($includedgroups as $oneofgroups) {
                    $ret = setLiveClassToken($linktwo, $cenroller, $oneofgroups, $newtoken);
                    if (!$ret) {
                        die("An error occured.");
                    }
                    if ($ret) {
                        $exitret = true;
                    }
                }
                if ($exitret) {
                    $result["meetServer"] = "https://".SFLC_HOST;
                    $result["meetCode"] = $newtoken;
                    echo json_encode($result);
                }
            }
            break;
        */
}

?>
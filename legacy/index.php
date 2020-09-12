<?php
// (C) 2020 The Skyfallen Productions Company
// SkyMake 2020 Edition Preview 1
// Code By Yigit Kerem Oktay
// June 20 Saturday 2020
// 21.00
// Source Code for SkyMake 2020 Edition Preview 1 : Code Begins Here
// Please Do not edit SkyMake Core Files
// SkyMake Version 4
// Intended to run on Apache 2 with PHP 7 and above.
// No deprecated functions are used so it will probably be compatible with a few next major release of PHP.

//Include config file
include_once "../SkyMakeDatabaseConnector/SkyMakeDBconfig.php";
include_once "../SkyMakeFunctionSet/Operation-Requirements/MainFunctions.php";
include "../classes/user.php";
$l = getsetting($link,"link");
// Check if act is equal to signup
if($_GET["act"] == "signup"){
    //if so set optget to signup
    $optget = "signup";
}else{
  $optget = "signin";
}
// Initialize the session
session_name('SkyMakeSessionStorage');
session_start();
// check if this is sign in
if($optget != "signup") {
// Check if the user is already logged in, if yes then redirect him to user page
    if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
        //this is not yet ready
        header("Location: /home");
    }
    $confirm_password_err = "";
// Define variables and initialize with empty values
    $username = $password = "";
    $username_err = $password_err = "";

// Processing form data when form is submitted
// Any post request will trigger including ones that does not carry our password and username
// Will be changed in future builds
// check if this is sign in
    if ($optget != "signup") {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            // Check if username is empty
            if (empty(trim($_POST["skymake-un"]))) {
                // Add this error under username box.
                $username_err = "Please enter username.";
            } else {
                $username = trim($_POST["skymake-un"]);
            }

            // Check if password is empty
            if (empty(trim($_POST["skymake-pw"]))) {
                // Add this error under password.
                $password_err = "Please enter your password.";
            } else {
                $password = trim($_POST["skymake-pw"]);
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

                                    // Store data in session variables
                                    $_SESSION["loggedin"] = true;
                                    $_SESSION["id"] = $id;
                                    $_SESSION["username"] = $username;
                                    //get assigned class
                                    $_SESSION["classid"] = SMUser::getStudentClassID($link,$_SESSION["username"]);
                                    // Redirect user to welcome page
                                    // Logged in successfully.
                                    header("Location: /home");
                                } else {
                                    // Display an error message if password is not valid
                                    $password_err = "The password you entered was not valid.";
                                }
                            }
                        } else {
                            // Display an error message if username doesn't exist
                            $username_err = "No account found with that username.";
                        }
                    } else {
                        // ANY OTHER ERROR - Will need an update in a future build.
                        die("Oops! Something went wrong. Please try again later.");
                    }

                    // Close statement
                    mysqli_stmt_close($stmt);
                }
            }

            // Close connection
            mysqli_close($link);
        }
    }
}
    if($optget == "signup"){
        // Define variables and initialize with empty values
        $username = $password = $confirm_password = "";
        $username_err = $password_err = $confirm_password_err = "";

// Processing form data when form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            // Validate username
            if (empty(trim($_POST["skymake-un"]))) {
                $username_err = "Please enter a username.";
            } else {
                // Prepare a select statement
                $sql = "SELECT id FROM skymake_users WHERE username = ?";

                if ($stmt = mysqli_prepare($link, $sql)) {
                    // Bind variables to the prepared statement as parameters
                    mysqli_stmt_bind_param($stmt, "s", $param_username);

                    // Set parameters
                    $param_username = trim($_POST["skymake-un"]);

                    // Attempt to execute the prepared statement
                    if (mysqli_stmt_execute($stmt)) {
                        // store result
                        mysqli_stmt_store_result($stmt);

                        if (mysqli_stmt_num_rows($stmt) == 1) {
                            $username_err = "This username is already taken.";
                        } else {
                            $username = trim($_POST["skymake-un"]);
                        }
                    } else {
                        die("Oops! Something went wrong. Please try again later.");
                    }

                    // Close statement
                    mysqli_stmt_close($stmt);
                }
            }

            // Validate password
            if (empty(trim($_POST["skymake-pw"]))) {
                $password_err = "Please enter a password.";
            } elseif (strlen(trim($_POST["skymake-pw"])) < 6) {
                $password_err = "Password must have atleast 6 characters.";
            } else {
                $password = trim($_POST["skymake-pw"]);
            }

            // Validate confirm password
            if (empty(trim($_POST["skymake-pwconfirm"]))) {
                $confirm_password_err = "Please confirm password.";
            } else {
                $confirm_password = trim($_POST["skymake-pwconfirm"]);
                if (empty($password_err) && ($password != $confirm_password)) {
                    $confirm_password_err = "Password did not match.";
                }
            }

            // Check input errors before inserting in database
            if (empty($username_err) && empty($password_err) && empty($confirm_password_err)) {

                // Prepare an insert statement
                $sql = "INSERT INTO skymake_users (username, password) VALUES (?, ?)";

                if ($stmt = mysqli_prepare($link, $sql)) {
                    // Bind variables to the prepared statement as parameters
                    mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);

                    // Set parameters
                    $param_username = $username;
                    $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash

                    // Attempt to execute the prepared statement
                    if (mysqli_stmt_execute($stmt)) {
                        // Redirect to login page
                        header("location: /?act=signin");
                    } else {
                        die("Something went wrong. Please try again later.");
                    }

                    // Close statement
                    mysqli_stmt_close($stmt);
                }
            }

            // Close connection
            mysqli_close($link);
        }
    }

?>
<html>
<head>
    <link type="text/css" rel="stylesheet" href="/SkyMakeVersionAssets/include/login-page.css">
    <title><?php if($optget == "signin"){
        echo "Sign in";
        } else {
        echo "Sign up";
        } ?> - Skyfallen:SkyMake</title>
</head>
<body style="background-image: url('/SkyMakeVersionAssets/include/img/loginbackground.jpg')">
<div class="background-div">
<div class="loginform">
    <form method="post">
        <h3><?php if($optget == "signin"){
                echo "Sign in";
            } else {
                echo "Sign up";
            } ?> to SkyMake</h3>
        <input class="loginform-username" name="skymake-un" placeholder="Username"><br>
        <?php echo $username_err; ?><br>
        <input class="loginform-password" type="password" name="skymake-pw" placeholder="Password"><br>
        <?php echo $password_err; ?><br>
        <?php if($optget == "signup"){
            echo "<input class=\"loginform-password\" type=\"password\" name=\"skymake-pwconfirm\" placeholder=\"Confirm Password\"><br>";
        } echo $confirm_password_err; ?><br>
        <input class="loginform-submit" style="margin-bottom: 20px;" type="submit" value="<?php if($optget == "signin"){
            echo "Sign in";
        } else {
            echo "Sign up";
        } ?>">
        <?php if($optget == "signin"){
            echo "<a href='/?act=signup' class='actswitch'>Don't have an account?</a>";
        } else {
            echo "<a href='/?act=signin' class='actswitch'>Have an account?</a>";
        } ?>
    </form>
</div>
    <div class="footer">
        <div class="footer-logocontainer">
            <img src="/SkyMakeVersionAssets/logo/SkyfallenLogoRB.png" height="30" style="padding-top: 5px; padding-bottom: 5px;">
        </div>
    </div>
</div>
</body>
</html>

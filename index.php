<?php
//(C) 2020 The Skyfallen Productions Company
// SkyMake 2020 Edition Preview 1
// Code By Yigit Kerem Oktay
// June 20 Saturday 2020
// 21.00
// Source Code for SkyMake 2020 Edition Preview 1 : Begin
// Initial Commit.
// June 21 Sunday 2020
// HTML Login Complete
// Php starting to roll out.
if($_POST["skymake-un"] and $_POST["skymake-pw"]){
include_once "SkyMakeDatabaseConnector/SkyMakeDBconnector.php";
    $username = $_POST["skymake-un"];
    $password = $_POST["skymake-pw"];
    $hashedpassword = password_hash($password, PASSWORD_DEFAULT);
    SkyMakeDBExecute("INSERT INTO `SkyMake_noprfx_SysLog` (`LogID`, `LogApplication`, `LogApplicationDescription`, `LogData`) VALUES (NULL, 'SkyMakeLogin', 'SkyMakeLoginPasswordHasher-ServerTime-12.40-22June2020', 'PaswordHashingComplete')");
}
?>
<html>
<head>
    <link type="text/css" rel="stylesheet" href="SkyMakeVersionAssets/include/login-page.css">
    <title>Sign in - Skyfallen:SkyMake</title>
</head>
<body style="background-image: url('/SkyMakeVersionAssets/include/img/loginbackground.jpg')">
<div class="background-div">
<div class="loginform">
    <form method="post">
        <h3>Sign in to SkyMake</h3>
        <input class="loginform-username" name="skymake-un" placeholder="Username"><br>
        <input class="loginform-password" type="password" name="skymake-pw" placeholder="Password"><br>
        <input class="loginform-submit" style="margin-bottom: 20px;" type="submit" value="Sign in">
    </form>
</div>
    <div class="footer">
        <div class="footer-logocontainer">
            <img src="SkyMakeVersionAssets/logo/SkyMakeLogo.png" height="30" style="padding-top: 5px; padding-bottom: 5px;">
        </div>
    </div>
</div>
</body>
</html>

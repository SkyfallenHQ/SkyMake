<?php
/*
************************************************************
*                 The Skyfallen Company, Inc.
*            -- Everyone has stories to tell --
*           -- But sometimes silence is golden --
*                   --November-10-2017--
*                  --SkyMake Version 2--
*  Written with love in php by
*  Yigit Kerem Oktay
*
*
*  Updated : 4th April 2020
*
*
************************************************************
*/
// Initialize the session
session_start();
//initialize bg value and set a random background number
$bg = rand(0,3);
// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: oes.php");
    exit;
}

// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    } else{
        $username = trim($_POST["username"]);
    }

    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }

    // Validate credentials
    if(empty($username_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT id, username, password FROM users WHERE username = ?";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            // Set parameters
            $param_username = $username;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);

                // Check if username exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            // Password is correct, so start a new session
                            session_start();

                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;
                            $_SESSION["qn"]=1;

                            // Redirect user to welcome page
                            header("location: oes.php");
                        } else{
                            // Display an error message if password is not valid
                            $password_err = "The password you entered was not valid.";
                        }
                    }
                } else{
                    // Display an error message if username doesn't exist
                    $username_err = "No account found with that username.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Close connection
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>deducated - Online Examination Service</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
    <?php
     //if random background value is 1
      if($bg == 1){
          echo "body{ font: 14px sans-serif; background :radial-gradient(#000 2px, transparent 3px); background-size : 20px 20px;}       .content {
            background-color:rgba(255,255,255,.99);
            border-radius:.25em;
            box-shadow:0 0 .25em rgba(0,0,0,.25);
            box-sizing:border-box;
            left:50%;
            padding:20px;
            position:fixed;
            text-align:center;
            top:50%;
            transform:translate(-50%, -50%);
            width: 350px;
          }";}
          //if random background value is 0
          elseif($bg == 0) {
      echo "body {
	background: linear-gradient(-45deg, #ee7752, #e73c7e, #23a6d5, #23d5ab);
	background-size: 400% 400%;
	animation: gradient 15s ease infinite;
}

@keyframes gradient {
	0% {
		background-position: 0% 50%;
	}
	50% {
		background-position: 100% 50%;
	}
	100% {
		background-position: 0% 50%;
	}
}
.content{ margin-top:100px;background: White;padding:20px; border:2px solid Black; border-radius:30px 1px 30px 1px; margin-right:auto; margin-left: auto; width: 350px;}";
}
//if random background value is 2
elseif($bg == 2){
echo "body {
    height: 100%;
    /* max-height: 600px; */
    width: 1000px;
    background-color: hsla(200,40%,30%,.4);
    background-image:       
        url('https://78.media.tumblr.com/cae86e76225a25b17332dfc9cf8b1121/tumblr_p7n8kqHMuD1uy4lhuo1_540.png'), 
        url('https://78.media.tumblr.com/66445d34fe560351d474af69ef3f2fb0/tumblr_p7n908E1Jb1uy4lhuo1_1280.png'),
        url('https://78.media.tumblr.com/8cd0a12b7d9d5ba2c7d26f42c25de99f/tumblr_p7n8kqHMuD1uy4lhuo2_1280.png'),
        url('https://78.media.tumblr.com/5ecb41b654f4e8878f59445b948ede50/tumblr_p7n8on19cV1uy4lhuo1_1280.png'),
        url('https://78.media.tumblr.com/28bd9a2522fbf8981d680317ccbf4282/tumblr_p7n8kqHMuD1uy4lhuo3_1280.png');
    background-repeat: repeat-x;
    background-position: 
        0 20%,
        0 100%,
        0 50%,
        0 100%,
        0 0;
    background-size: 
        2500px,
        800px,
        500px 200px,
        1000px,
        400px 260px;
    animation: 50s para infinite linear;
    }

@keyframes para {
    100% {
        background-position: 
            -5000px 20%,
            -800px 95%,
            500px 50%,
            1000px 100%,
            400px 0;
        }
    }
    .content {
        background-color:rgba(255,255,255,.99);
        border-radius:.25em;
        box-shadow:0 0 .25em rgba(0,0,0,.25);
        box-sizing:border-box;
        left:50%;
        padding:20px;
        position:fixed;
        text-align:center;
        top:50%;
        transform:translate(-50%, -50%);
        width: 350px;
      }";
}
//if random background value is 3
elseif($bg == 3){
echo "html {
    height:100%;
  }
  
  body {
    margin:0;
  }
  
  .bg {
    animation:slide 3s ease-in-out infinite alternate;
    background-image: linear-gradient(-60deg, #6c3 50%, #09f 50%);
    bottom:0;
    left:-50%;
    opacity:.5;
    position:fixed;
    right:-50%;
    top:0;
    z-index:-1;
  }
  
  .bg2 {
    animation-direction:alternate-reverse;
    animation-duration:4s;
  }
  
  .bg3 {
    animation-duration:5s;
  }
  
  
  h1 {
    font-family:monospace;
  }
  
  @keyframes slide {
    0% {
      transform:translateX(-25%);
    }
    100% {
      transform:translateX(25%);
    }
  }
  .content {
    background-color:rgba(255,255,255,.99);
    border-radius:.25em;
    box-shadow:0 0 .25em rgba(0,0,0,.25);
    box-sizing:border-box;
    left:50%;
    padding:20px;
    position:fixed;
    text-align:center;
    top:50%;
    transform:translate(-50%, -50%);
    width: 350px;
  }";
}
    ?>    
 
        .footer {
  position: fixed;
  left: 0;
  bottom: 0;
  width: 100%;
  background-color: white;
  color: black;
  text-align: center;
}
.top-bar {
  position: fixed;
  left: 0;
  top: 0;
  width: 100%;
  background-color: white;
  color: black;
  height:40px;
}
.sflogo1{
    margin-top:3px;
    margin-bottom:5px;
    margin-left:20px;
    margin-right:10px;
    text-align: left;
}
.deducatedlogo1{
    margin-top:1px;
    margin-bottom:5px;
    margin-left:9px;
    margin-right:20px;
    text-align:left;
}
.seperatortype1{
    color:White;
    font-size:25px;
}
.seperatortype1:hover{
    color:White;
}
@keyframes sweep {
  0%    {opacity: 0; margin-top: -100px;}
  100%  {opacity: 1; margin-top: 0px;}
}
    </style>
</head>
<body>
<div class="top-bar">
        <a href="https://theskyfallen.company"><img src="IMG_0183.PNG" height=30 class="sflogo1"></a>
        </div>
        <?php
        //adding extra html code for third random background
if($bg==3){
echo "<div class=\"bg\"></div>
<div class=\"bg bg2\"></div>
<div class=\"bg bg3\"></div>";
} ?>
   <div class="content">
        <h2>deducated OES - Login</h2>
        <p>This application is not connected to the Skyfallen ID service.Please login with your app specific credidentals.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="form-control">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
            </div>
        </form>
    </div>
</body>
    <div class="footer">
        <p>SkyMake Version 2 : Created 2017 - 2020 Quarter 1 Service Pack <br>The Skyfallen Company,Copyright 2016-2020</div>
</html>

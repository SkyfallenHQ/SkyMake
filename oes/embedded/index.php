<html>
<head>
    <title>Embedded Exam</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>
<?php
ini_set("display_errors",1);
ini_set("display_startup_errors",1);
error_reporting(E_ALL);

session_name('SkyMakeSessionStorage');
require_once "../../SkyMakeDatabaseConnector/SkyMakeDBconfig.php";
require_once "../../classes/user.php";

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

if(isset($_GET["examid"])){
    $examid = $_GET["examid"];
    $_SESSION["examid"] = $examid;
}else {
    if(!isset($_SESSION["examid"])){
        echo _("Exam not specified.");
    }
}
$sql = "SELECT exam_name,exam_start,exam_end,exam_qcount,exam_type FROM skymake_examdata WHERE examid='".$_SESSION["examid"]."'";
if($result = mysqli_query($link, $sql)){
    if(mysqli_num_rows($result) == 1){
        while($row = mysqli_fetch_array($result)){
            $examdata["exam_name"] = $row["exam_name"];
            $examdata["exam_start"] = $row["exam_start"];
            $examdata["exam_end"] = $row["exam_end"];
            $examdata["exam_qcount"] = $row["exam_qcount"];
            $examdata["exam_type"] = $row["exam_type"];
        }
        mysqli_free_result($result);
    } else{
        echo _("Exam invalid.");
    }
} else{
    die("ERROR: Could not able to execute $sql. " . mysqli_error($link));
}
if (time() > strtotime($examdata["exam_end"])) {
    echo _("Your time is over. \n");
    die(_("Your answer was discarded.\n"));
}
if(!isset($_SESSION["qn"])){
    $_SESSION["qn"] = 1;
}
$picurl = "";
$sql = "SELECT picurl FROM skymake_qanswers WHERE examid='".$_SESSION["examid"]."' and qn='".$_SESSION["qn"]."'";
if($result = mysqli_query($link, $sql)){
    if(mysqli_num_rows($result) == 1){
        $picurl = trim(mysqli_fetch_array($result)["picurl"]);

        if($picurl == ""){
            $picurl = "https://www.publicdomainpictures.net/pictures/280000/nahled/not-found-image-15383864787lu.jpg";
        }

        mysqli_free_result($result);
    } else{
        $picurl = "https://www.publicdomainpictures.net/pictures/280000/nahled/not-found-image-15383864787lu.jpg";
    }
} else{
    die("ERROR: Could not able to execute $sql. " . mysqli_error($link));
}
function getRemoteIPAddress() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];

    } else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    return $_SERVER['REMOTE_ADDR'];
}

if($_SESSION["qn"]==$examdata["exam_qcount"]){
    ?>
    <h3>You may leave after this question.</h3>
    <?php
}
if (isset($_POST["nextbtn"])){
    if($_SESSION["qn"]!=$examdata["exam_qcount"] + 1){
        if(empty($_POST['ANSWER'])){
            echo _("Please select one");
        }
        elseif(isset($_POST['ANSWER'])){
            $answer = $_POST['ANSWER'];
            $_SESSION["lastanswer"]= $answer;
            $_SESSION["qn"]=$_SESSION["qn"]+1;

            unset($_POST["nextbtn"]);
            $qn_internal=$_SESSION["qn"]-1;
            $uniq = $id."uniq".$qn_internal."uniq".$_SESSION["examid"];
            if($_SESSION["lastanswer"] == "Q_A" or $_SESSION["lastanswer"] == "Q_B" or $_SESSION["lastanswer"] == "Q_C" or $_SESSION["lastanswer"] == "Q_D" or $_SESSION["lastanswer"] == "Q_EMPTY"){
                $sql = "INSERT INTO skymake_answer (id, qn, answer,uniq,examid)
VALUES ('".$id."', '".$qn_internal."', '".$_SESSION["lastanswer"]."','".$uniq."','".$_SESSION["examid"]."')";
                if (mysqli_query($linktwo, $sql)) {
                    echo _("We've successfully saved your response for question:").$qn_internal;
                } else {
                    $sql = "DELETE FROM skymake_answer WHERE uniq='".$uniq."';";
                    if (mysqli_query($linktwo, $sql)){
                        $sql = "INSERT INTO skymake_answer (id, qn, answer,uniq,examid)
VALUES ('".$id."', '".$qn_internal."', '".$_SESSION["lastanswer"]."','".$uniq."','".$_SESSION["examid"]."')";

                        if (mysqli_query($linktwo, $sql)) {
                            echo _("We've successfully saved your response for question:").$qn_internal;
                        }
                        else{
                            echo "Type 2 Error: " . $sql . "<br>" . mysqli_error($linktwo);
                        }
                    }
                    else {
                        echo "Error: " . $sql . "<br>" . mysqli_error($linktwo);
                    }}
            }else {
                echo _("Invalid radiobutton value:").$_SESSION["lastanswer"];
            }
        }
    }
}
if (isset($_POST["backbtn"])){
    if($_SESSION["qn"]!=1){
        $_SESSION["qn"]=$_SESSION["qn"]-1;
        unset($_POST["backbtn"]);
    }}
if($_SESSION["qn"] > $examdata["exam_qcount"]) {
    $_SESSION["qn"] = $_SESSION["qn"] - 1;
}
?>
<style type="text/css">
    body{ font: 14px sans-serif; text-align: center; }
</style>
<div class="page-header">
    <h1><?= _("Hi") ?>, <b><?php echo htmlspecialchars($username); ?></b>. Please enter your answers for <?php echo $examdata["exam_name"]; ?>.</h1>
    <h6><?= _("Exam Schedule:") ?> <?php echo $examdata["exam_start"]." to ".$examdata["exam_end"]; ?></h6>
</div>
<form method="post">
    <img src="<?php echo $picurl; ?>"><br>
    <?php echo _("Question Number:").$_SESSION["qn"]." Out of: ".$examdata["exam_qcount"]; ?><br>
    <input name="backbtn" type="submit" class="btn btn-primary" value="<?= _("Don't Submit and Go Back") ?>">
    <?= _("Your Answer") ?>
    <input type="radio" id="Q_A" name="ANSWER" value="Q_A">
    <label for="Q_A">A</label>
    <input type="radio" id="Q_B" name="ANSWER" value="Q_B">
    <label for="Q_B">B</label>
    <input type="radio" id="Q_C" name="ANSWER" value="Q_C">
    <label for="Q_C">C</label>
    <input type="radio" id="Q_D" name="ANSWER" value="Q_D">
    <label for="Q_D">D</label>
    <input type="radio" id="Q_EMPTY" name="ANSWER" value="Q_EMPTY">
    <label for="Q_EMPTY"><?= _("Leave Empty") ?></label>
    <input id="nextbtn" name="nextbtn" type="submit" class="btn btn-primary" value="<?= _("Submit and Continue") ?>"><br>
    <p id="timer" name="timer"></p>
</form>
</body>
<script>
    // Set the date we're counting down to
    // 1. JavaScript
    // var countDownDate = new Date("Sep , 2018 15:37:25").getTime();
    // 2. PHP
    var countDownDate = <?php echo strtotime($examdata["exam_end"]) ?> * 1000;
    var now = <?php echo time() ?> * 1000;

    // Update the count down every 1 second
    var x = setInterval(function() {

        // Get todays date and time
        // 1. JavaScript
        // var now = new Date().getTime();
        // 2. PHP
        now = now + 1000;

        // Find the distance between now an the count down date
        var distance = countDownDate - now;

        // Time calculations for days, hours, minutes and seconds
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

        // Output the result in an element with id="demo"
        document.getElementById("timer").innerHTML = hours + "h " +
            minutes + "m " + seconds + "s ";

        // If the count down is over, write some text
        if (distance < 0) {
            clearInterval(x);
            document.getElementById("timer").innerHTML = "EXAM TIMEOUT";
            document.getElementById("nextbtn").disabled = true;
            setTimeout(function(){
                window.location.href = '/';
            }, 5000);
        }
    }, 1000);
</script>
</html>

<?php
require_once "config.php";
session_start();
if(isset($_GET["examid"])){
    $examid = $_GET["examid"];
    $_SESSION["examid"] = $examid;
}else {
    if(!isset($_SESSION["examid"])){
    echo "Exam not specified. You will be redirected in 3 seconds";
    sleep(3);
    header("location: /");
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
                echo "Exam invalid. You will be redirected in 3 seconds";
                sleep(3);
                header("location: /");
        }
    } else{
        die("ERROR: Could not able to execute $sql. " . mysqli_error($link));
    }
    mysqli_close($link);
function getRemoteIPAddress() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];

    } else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) { 
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    return $_SERVER['REMOTE_ADDR'];
}
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: index.php");
    exit;
}
if (isset($_POST["killsession"])){
    header("location: sessionkill.php");
}
if (isset($_POST["nextbtn"])){
    if($_SESSION["qn"]!=$examdata["exam_qcount"]){
 if(empty($_POST['ANSWER'])){
   echo "Please select one";
   }
  elseif(isset($_POST['ANSWER'])){
   $answer = $_POST['ANSWER'];
   $_SESSION["lastanswer"]= $answer;
   $_SESSION["qn"]=$_SESSION["qn"]+1;
   $_SESSION["qninfo"] = qninfo($_SESSION["qn"]);

   unset($_POST["nextbtn"]);
      $qn_internal=$_SESSION["qn"]-1;
      $uniq = $_SESSION["id"]."uniq".$qn_internal."uniq".$_SESSION["examid"];
      if($_SESSION["lastanswer"] == "Q_A" or $_SESSION["lastanswer"] == "Q_B" or $_SESSION["lastanswer"] == "Q_C" or $_SESSION["lastanswer"] == "Q_D" or $_SESSION["lastanswer"] == "Q_EMPTY"){
$sql = "INSERT INTO skymake_answer (id, qn, answer,uniq,examid)
VALUES ('".$_SESSION["id"]."', '".$qn_internal."', '".$_SESSION["lastanswer"]."','".$uniq."','".$_SESSION["examid"]."')";

if (mysqli_query($link, $sql)) {
    echo "SQL Query Succeeded:".$sql;
} else {
    $sql = "DELETE FROM skymake_answer WHERE uniq='".$uniq."';";
    if (mysqli_query($link, $sql)){
 $sql = "INSERT INTO skymake_answer (id, qn, answer,uniq)
VALUES ('".$_SESSION["id"]."', '".$qn_internal."', '".$_SESSION["lastanswer"]."','".$uniq."','".$_SESSION["examid"]."')";

    if (mysqli_query($link, $sql)) {
    echo "SQL Query Succeeded:".$sql;
    }
        else{
          echo "Type 2 Error: " . $sql . "<br>" . mysqli_error($link);
        }
    }
    else {
    echo "Error: " . $sql . "<br>" . mysqli_error($link);
}}
      }else {
          echo "Invalid radiobutton value:".$_SESSION["lastanswer"];
      }

mysqli_close($link);  }
}
}
if (isset($_POST["backbtn"])){
    if($_SESSION["qn"]!=1){
 $_SESSION["qn"]=$_SESSION["qn"]-1;
    unset($_POST["backbtn"]);
}}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SkyMake 4 - OE System</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; text-align: center; }
                .footer {
  position: fixed;
  left: 0;
  bottom: 0;
  width: 100%;
  background-color: black;
  color: white;
  text-align: center;
    </style>
</head>
<body>
    <div class="page-header">
        <h1>Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Please enter your answers for <?php echo $examdata["exam_name"]; ?>.</h1>
        <h6>From <?php echo $examdata["exam_start"]." to ".$examdata["exam_end"]; ?></h6>
    </div>
    <form method="post">
        <img src="<?php
        $sql = "SELECT FROM skymake_qanswer (picurl) WHERE examid='".$_SESSION["examid"]."' and qn='".$_SESSION["qn"]."'";
        if($result = mysqli_query($link, $sql)){
            if(mysqli_num_rows($result) == 1){
                while($row = mysqli_fetch_array($result)){
                    echo $row["picurl"];
                }
                mysqli_free_result($result);
            } else{
                echo "https://www.theskyfallen.com/cdn/imgnone.jpg";
            }
        } else{
            die("ERROR: Could not able to execute $sql. " . mysqli_error($link));
        }
        mysqli_close($link);
        ?>"><br>
     <?php echo "Question Number:".$_SESSION["qn"]." Out of: ".$examdata["exam_qcount"]; ?><br>
            <input name="backbtn" type="submit" class="btn btn-primary" value="Don't Submit and Go Back">
     Your answer:
    <input type="radio" id="Q_A" name="ANSWER" value="Q_A">
    <label for="Q_A">A</label>
        <input type="radio" id="Q_B" name="ANSWER" value="Q_B">
    <label for="Q_B">B</label>
        <input type="radio" id="Q_C" name="ANSWER" value="Q_C">
        <label for="Q_C">C</label>
            <input type="radio" id="Q_D" name="ANSWER" value="Q_D">
    <label for="Q_D">D</label>
        <input type="radio" id="Q_EMPTY" name="ANSWER" value="Q_EMPTY">
    <label for="Q_EMPTY">Leave Empty</label>
    <input id="nextbtn" name="nextbtn" type="submit" class="btn btn-primary" value="Submit and Continue"><br>
        <input name="killsession" type="submit" class="btn btn-primary" value="Kill Session"><br>
         <p id="timer" name="timer"></p>
        </form>
</body>
    <script>
    // Set the date we're counting down to
    // 1. JavaScript
    // var countDownDate = new Date("Sep , 2018 15:37:25").getTime();
    // 2. PHP
    var countDownDate = <?php echo strtotime('2020-08-27 20:00:00') ?> * 1000;
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

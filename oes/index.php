<?php
require_once "config.php";
include_once "../nps/widgets/dash.php";
session_start();
if(isset($_GET["examid"])){
    $examid = $_GET["examid"];
    $_SESSION["examid"] = $examid;
}else {
    if(!isset($_SESSION["examid"])){
    echo "Exam not specified.";
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
            echo "Exam invalid.";
    }
} else{
    die("ERROR: Could not able to execute $sql. " . mysqli_error($link));
}
if (time() > strtotime($examdata["exam_end"])) {
   echo "Your time is over. \n";
   die("Your answer was discarded.\n");
}
if(!isset($_SESSION["qn"])){
    $_SESSION["qn"] = 1;
}
$sql = "SELECT picurl FROM skymake_qanswers WHERE examid='".$_SESSION["examid"]."' and qn='".$_SESSION["qn"]."'";
if($result = mysqli_query($link, $sql)){
    if(mysqli_num_rows($result) == 1){
        while($row = mysqli_fetch_array($result)){
            $picurl = $row["picurl"];
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
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: index.php");
    exit;
}
if (isset($_POST["killsession"])){
    header("location: sessionkill.php");
}
/*if($_SESSION["qn"]==$examdata["exam_qcount"]){
    ?>
    <!-- Modal CSS -->
    <style>
        .modal-confirm {
            color: #434e65;
            width: 525px;
            margin: 30px auto;
        }
        .modal-confirm .modal-content {
            padding: 20px;
            font-size: 16px;
            border-radius: 5px;
            border: none;
        }
        .modal-confirm .modal-header {
            background: #47c9a2;
            border-bottom: none;
            position: relative;
            text-align: center;
            margin: -20px -20px 0;
            border-radius: 5px 5px 0 0;
            padding: 35px;
        }
        .modal-confirm h4 {
            text-align: center;
            font-size: 36px;
            margin: 10px 0;
        }
        .modal-confirm .form-control, .modal-confirm .btn {
            min-height: 40px;
            border-radius: 3px;
        }
        .modal-confirm .close {
            position: absolute;
            top: 15px;
            right: 15px;
            color: #fff;
            text-shadow: none;
            opacity: 0.5;
        }
        .modal-confirm .close:hover {
            opacity: 0.8;
        }
        .modal-confirm .icon-box {
            color: #fff;
            width: 95px;
            height: 95px;
            display: inline-block;
            border-radius: 50%;
            z-index: 9;
            border: 5px solid #fff;
            padding: 15px;
            text-align: center;
        }
        .modal-confirm .icon-box i {
            font-size: 64px;
            margin: -4px 0 0 -4px;
        }
        .modal-confirm .btn {
            color: #fff;
            border-radius: 4px;
            background: #eeb711;
            text-decoration: none;
            transition: all 0.4s;
            line-height: normal;
            border-radius: 30px;
            margin-top: 10px;
            padding: 6px 20px;
            border: none;
        }
        .modal-confirm .btn:hover, .modal-confirm .btn:focus {
            background: #eda645;
            outline: none;
        }
        .modal-confirm .btn span {
            margin: 1px 3px 0;
            float: left;
        }
        .modal-confirm .btn i {
            margin-left: 1px;
            font-size: 20px;
            float: right;
        }
        .trigger-btn {
            display: inline-block;
            margin: 100px auto;
        }
    </style>
    <!-- Modal HTML -->
    <div id="examFinish" class="modal fade">
        <div class="modal-dialog modal-confirm">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="icon-box">
                        <i class="material-icons">&#xE876;</i>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body text-center">
                    <h4>Great!</h4>
                    <p>This will be your last question!<br> You can change your answers until your time is over.<br> If this is your second time seeing this, don't panic! You answers are being saved.</p>
                    <button class="btn btn-success" data-dismiss="modal"><span>Dismiss</span> <i class="material-icons">&#xE5C8;</i></button>
                </div>
            </div>
        </div>
    </div>
    <!-- Show Modal On Page Load -->
    <script type="text/javascript">
        $(window).on('load',function(){
            $('#examFinish').modal('show');
        });
    </script>
    <?php
}*/
if (isset($_POST["nextbtn"])){
    if($_SESSION["qn"]!=$examdata["exam_qcount"] + 1){
 if(empty($_POST['ANSWER'])){
   echo "Please select one";
   }
  elseif(isset($_POST['ANSWER'])){
   $answer = $_POST['ANSWER'];
   $_SESSION["lastanswer"]= $answer;
   $_SESSION["qn"]=$_SESSION["qn"]+1;

   unset($_POST["nextbtn"]);
      $qn_internal=$_SESSION["qn"]-1;
      $uniq = $_SESSION["id"]."uniq".$qn_internal."uniq".$_SESSION["examid"];
      if($_SESSION["lastanswer"] == "Q_A" or $_SESSION["lastanswer"] == "Q_B" or $_SESSION["lastanswer"] == "Q_C" or $_SESSION["lastanswer"] == "Q_D" or $_SESSION["lastanswer"] == "Q_EMPTY"){
$sql = "INSERT INTO skymake_answer (id, qn, answer,uniq,examid)
VALUES ('".$_SESSION["id"]."', '".$qn_internal."', '".$_SESSION["lastanswer"]."','".$uniq."','".$_SESSION["examid"]."')";
if (mysqli_query($linktwo, $sql)) {
    echo "SQL Query Succeeded:".$sql;
} else {
    $sql = "DELETE FROM skymake_answer WHERE uniq='".$uniq."';";
    if (mysqli_query($linktwo, $sql)){
 $sql = "INSERT INTO skymake_answer (id, qn, answer,uniq,examid)
VALUES ('".$_SESSION["id"]."', '".$qn_internal."', '".$_SESSION["lastanswer"]."','".$uniq."','".$_SESSION["examid"]."')";

    if (mysqli_query($linktwo, $sql)) {
    echo "SQL Query Succeeded:".$sql;
    }
        else{
          echo "Type 2 Error: " . $sql . "<br>" . mysqli_error($linktwo);
        }
    }
    else {
    echo "Error: " . $sql . "<br>" . mysqli_error($linktwo);
}}
      }else {
          echo "Invalid radiobutton value:".$_SESSION["lastanswer"];
      }
 }
}
}
if (isset($_POST["backbtn"])){
    if($_SESSION["qn"]!=1){
 $_SESSION["qn"]=$_SESSION["qn"]-1;
    unset($_POST["backbtn"]);
}}
mysqli_close($link);
mysqli_close($linktwo);
if($_SESSION["qn"] > $examdata["exam_qcount"]) {
    $_SESSION["qn"] = $_SESSION["qn"] - 1;
}
?>
    <style type="text/css">
        body{ font: 14px sans-serif; text-align: center; }
    </style>
    <div class="page-header">
        <h1>Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Please enter your answers for <?php echo $examdata["exam_name"]; ?>.</h1>
        <h6>From <?php echo $examdata["exam_start"]." to ".$examdata["exam_end"]; ?></h6>
    </div>
    <form method="post">
        <img src="<?php echo $picurl; ?>"><br>
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

<?php
require_once "config.php";
include_once "../../nps/widgets/dash.php";
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('file_uploads', "On");
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
if(!isset($_SESSION["qn"])){
    $_SESSION["qn"] = 1;
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
if (!file_exists('Q_Uploads/'.$_SESSION["examid"])) {
    mkdir('Q_Uploads/'.$_SESSION["examid"], 0777, true);
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
if (!empty($_POST)){
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
      if($_SESSION["lastanswer"] == "Q_A" or $_SESSION["lastanswer"] == "Q_B" or $_SESSION["lastanswer"] == "Q_C" or $_SESSION["lastanswer"] == "Q_D" or $_SESSION["lastanswer"] == "Q_EMPTY"){
          $target_dir = "Q_Uploads/".$_SESSION["examid"]."/";
          $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
          $uploadOk = 1;
          $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

              $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
              if($check !== false) {
                  echo "File is an image - " . $check["mime"] . ".";
                  $uploadOk = 1;
              } else {
                  echo "File is not an image.";
                  $uploadOk = 0;
              }
          }
          if (file_exists($target_file)) {
              echo "Sorry, file already exists.";
              $uploadOk = 0;
          }
          if ($_FILES["fileToUpload"]["size"] > 500000) {
              echo "Sorry, your file is too large.";
              $uploadOk = 0;
          }
          if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
              && $imageFileType != "gif" ) {
              echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
              $uploadOk = 0;
          }
          if ($uploadOk == 0) {
              echo "Sorry, your file was not uploaded.";
          } else {
              if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                  echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
                  $sql = "INSERT INTO skymake_qanswers (examid, qn, answer,picurl)
            VALUES ('".$_SESSION["examid"]."', '".$qn_internal."', '".$_SESSION["lastanswer"]."','".$target_file."')";
                  if (mysqli_query($linktwo, $sql)) {
                      echo "SQL Query Succeeded:".$sql;
                  } else {
                      echo "There was an error with MySQL Server"; }
              } else {
                  echo "Sorry, there was an error uploading your file.";
              }
          }
 }else {
     echo "Invalid radiobutton value:".$_SESSION["lastanswer"];
 }
      }
}
if (isset($_POST["backbtn"])){
 $_SESSION["qn"]=0;
    unset($_POST["backbtn"]);
}
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
    <form method="post" enctype="multipart/form-data" id="form1">
    Select image to upload:
    <input type="file" name="fileToUpload" id="fileToUpload">
    </form>
    <form method="post" id="form2">
     <?php echo "Question Number:".$_SESSION["qn"]." Out of: ".$examdata["exam_qcount"]; ?><br>
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
    <input value="Start Over" name="backbtn" class="btn btn-dark">
        </form>
<button name="nextbtn" class="btn btn-light" onclick="submitForm()" >Submit and Continue</button>
    <script>
        submitForm = function(){
            document.getElementById("form1").submit();
            document.getElementById("form2").submit();
        }
    </script>
</body>
</html>

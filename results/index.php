<?php
require_once "config.php";
include "../nps/widgets/dash.php";
session_start();
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: /");
    exit;
}
if(!isset($_GET["examid"])){
    ?>
    <style>body{
            text-align: center;
        }</style>
    <div style="width: 100%; text-align: center; align-content: center; margin-top: 50px;">
        <h2>SkyMake Exam Results</h2>
    <form method="get" style="text-align: center; width: 500px; margin-right: auto; margin-left: auto; display: inline-block;">
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1">SkyMake Exam ID</span>
            </div>
            <select class="custom-select" id="inputGroupSelect04" aria-label="Select User" name="examid">
                <?php
                $sql = "SELECT * FROM skymake_examdata";
                if($result = mysqli_query($link,$sql)){
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_array($result)) {
                            echo "<option value='".$row["examid"]."'>".$row["examid"]." - ".$row["exam_name"]."</option>";
                        }
                    }
                }else {
                    echo "SQL Error: $sql . ".mysqli_error($link);
                }
                ?>
            </select>
        </div>
        <button type="submit" class="btn btn-light">Submit</button>
    </form>
    </div>
<?php
    die();
}
$sql = "SELECT exam_name,exam_start,exam_end,exam_qcount,exam_type FROM skymake_examdata WHERE examid='".$_GET["examid"]."'";
if($result = mysqli_query($link, $sql)){
    if(mysqli_num_rows($result) == 1){
        while($row = mysqli_fetch_array($result)){
            $examdata["exam_name"] = $row["exam_name"];
            $examdata["exam_start"] = $row["exam_start"];
            $examdata["exam_end"] = $row["exam_end"];
            $examdata["exam_qcount"] = $row["exam_qcount"];
            $examdata["exam_type"] = $row["exam_type"];
            if($row["exam_creator"] == "no-one"){
                $examdata["exam_creator"] = "Anonymously Created Exam";
            } else{
                $examdata["exam_creator"] = $row["exam_creator"];
            }

        }
        mysqli_free_result($result);
    } else{
        echo "Exam invalid.";
        die("<a href='/'>Home Page</a>");
    }
} else{
    die("ERROR: Could not able to execute $sql. " . mysqli_error($link));
}
if (new DateTime() < new DateTime($examdata["exam_end"])) {
    echo "Your time is not over. \n";
    echo "Your results won't be shown..\n";
    die("<a href='/'>Home Page</a>");
}
if($_SESSION["user_role"] == "admin"){
    header("Location: /results/ranking/");
}
$_SESSION["examid"] = $_GET["examid"];
$link2 = $linktwo;
$sql = "SELECT qn,answer FROM skymake_answer WHERE id=".$_SESSION["id"]." and examid='".$_SESSION["examid"]."'";
$sql2 = "SELECT answer FROM skymake_qanswers WHERE examid='".$_SESSION["examid"]."'";
if ($res = mysqli_query($link, $sql)) { 
    if ($res2 = mysqli_query($link2, $sql2)) { 
    if (mysqli_num_rows($res) == $examdata["exam_qcount"] and mysqli_num_rows($res2) == $examdata["exam_qcount"]) {
        ?>
<div style="text-align: center;">
    <h1><?php echo $examdata["exam_name"]; ?></h1>
    <h4><?php echo "From ".$examdata["exam_start"]." to ".$examdata["exam_end"]." ".$examdata["exam_qcount"]." Questions"; ?></h4>
    <h6>Created by: <?php echo $examdata["exam_creator"]; ?></h6>
        <?php
        echo "<table class='table'>";
        echo "<thead>";
        echo "<tr>"; 
        echo "<th scope='col'>Question Nr.</th>";
        echo "<th scope='col'>Your Answer</th>";
        echo "<th scope='col'>Right Answer</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
        $qn_internal = 0;
        $m1 = 0;
        $m2 = 0;
        $e2 = 0;
        $e1 = 0;
        while ($row = mysqli_fetch_array($res)) { 
            $row2 = mysqli_fetch_array($res2);
            $qn_internal++;
            echo "<tr>"; 
            echo "<th scope='row'>".$row['qn']."</th>";
            echo "<td>".$row['answer']."</td>";
            echo "<td>".$row2['answer']."</td>";
            echo "</tr>";
            $examtype = $examdata["exam_type"];
            /*if($examtype="90withMSL") {
                if ($row['answer'] != $row2['answer']) {
                    if ($row['answer'] == "Q_EMPTY") {
                        if (x < 51 and x > 20) {
                            $e1++;
                            $m1++;
                        } else {
                            $e2++;
                            $m2++;
                        }
                    } else {
                        if (x < 51 and x > 20) {
                            $m1++;
                        } else {
                            $m2++;
                        }
                    }
                }
                $points = 500-($m1+($m1-$e1)/3+($m2-$e2)/3*4+$m2*4);
            }else{ */
                if ($row['answer'] != $row2['answer']) {
                    if ($row['answer'] == "Q_EMPTY") {
                        $points = $points;
                    } else {
                        $points = $points - 10;
                    }
                }else{
                    $points = $points + 20;
                }
            //}
        }
        echo "</tbody>";
        echo "</table><p>".$points."</p></div>";
                $sql = "INSERT INTO skymake_result (un, p, examid) VALUES ('".$_SESSION["username"]."','".$points."','".$_SESSION["examid"]."');";
        if (mysqli_query($link, $sql)) { 
       echo "<p>We've successfully added your data to the database.</p>";
       echo "<p><a href=\"/results/ranking\">Show ranking.</a></p>";
     }else {
            $sql = "INSERT INTO skymake_result (un, p, examid) VALUES ('" . $_SESSION["username"] . "','" . $points . "','" . $_SESSION["examid"] . "');";
            if (mysqli_query($link, $sql)) {
                echo "<p>We've updated your results in the database.</p>";
                echo "<p><a href=\"/results/ranking/\">Show ranking.</a></p>";
            } else
                echo "<p>Sorry. We could not update the database.</p>";
        }
        }else {
        echo "<p>You have not entered or finished this exam. Exam Answer Key Has".mysqli_num_rows($res)." Exam Data Has ".$examdata["exam_qcount"]." And You Have ".mysqli_num_rows($res2)." Answers</p>";
        }
        mysqli_free_result($res);
        mysqli_free_result($res2);
    }
}
mysqli_close($link); 
?> 
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
    header("location: /");
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
if (new DateTime() < new DateTime($examdata["exam_end"])) {
    echo "Your time is not over. \n";
    echo "Your results won't be shown..\n";
    echo "Redirecting in 10 seconds \n";
    sleep(10);
    header("location: /");
}
$_SESSION["examid"] = $_GET["examid"];
$link2 = $linktwo;
$sql = "SELECT qn,answer FROM skymake_answer WHERE id=".$_SESSION["id"]." and examid='".$_SESSION["examid"]."'";
$sql2 = "SELECT answer FROM skymake_qanswers WHERE examid='".$_SESSION["examid"]."'";
if ($res = mysqli_query($link, $sql)) { 
    if ($res2 = mysqli_query($link2, $sql2)) { 
    if (mysqli_num_rows($res) == $examdata["exam_qcount"] and mysqli_num_rows($res2) == $examdata["exam_qcount"]) {
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
            if($examtype="90withMSL") {
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
            }else{
                if ($row['answer'] != $row2['answer']) {
                    if ($row['answer'] == "Q_EMPTY") {
                        $points = $points;
                    } else {
                        $points = $points - 10;
                    }
                }else{
                    $points = $points + 20;
                }
            }
        }
        echo "</tbody>";
        echo "</table><p>".$points."</p>"; 
                $sql = "INSERT INTO skymake_result (un, p) VALUES ('".$_SESSION["username"]."','".$points."');";
        if (mysqli_query($link, $sql)) { 
       echo "<p>Query Executed Successfully.</p>"; 
       echo "<p><a href=\"ranking.php\">Show ranking.</a></p>";  
     }else
        {
            echo "<p>Query failed. ECN:3</p>";
                   echo "<p><a href=\"ranking.php\">Show ranking.</a></p>";  
        }
        mysqli_free_res($res); 
        mysqli_free_res($res2); 
    }
}}  
mysqli_close($link); 
?> 
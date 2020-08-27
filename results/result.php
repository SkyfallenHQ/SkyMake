<?php
require_once "config.php";
session_start();
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: /");
    exit;
}
// Check connection
$link2 = $linktwo;
$sql = "SELECT qn,answer FROM skymake_answer WHERE id=".$_SESSION["id"].";";
$sql2 = "SELECT answer FROM skymake_answer WHERE id=3;";
if ($res = mysqli_query($link, $sql)) { 
    if ($res2 = mysqli_query($link2, $sql2)) { 
    if (mysqli_num_rows($res) == 90 and mysqli_num_rows($res2) == 90) { 
        echo "<table>"; 
        echo "<tr>"; 
        echo "<th>Question Nr.</th>"; 
        echo "<th>Your Answer</th>"; 
        echo "<th>Right Answer</th>"; 
        echo "</tr>"; 
        $qn_internal = 0;
        $m1 = 0;
        $m2 = 0;
        $e2 = 0;
        $e1 = 0;
        while ($row = mysqli_fetch_array($res)) { 
            $row2 = mysqli_fetch_array($res2);
            $qn_internal++;
            echo "<tr>"; 
            echo "<td>".$row['qn']."</td>"; 
            echo "<td>".$row['answer']."</td>";
            echo "<td>".$row2['answer']."</td>";
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
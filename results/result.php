<?php
require_once "config.php";
session_start();
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: index.php");
    exit;
}
define('DB_SERVER', 'theskyfallen.com');
define('DB_USERNAME', 'admin_oesu');
define('DB_PASSWORD', 'OeSu2020**');
define('DB_NAME', 'admin_oes');
 
/* Attempt to connect to MySQL database */
$link2 = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
 
// Check connection
if($link2 === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
$sql = "SELECT qn,answer FROM answer WHERE id=".$_SESSION["id"].";"; 
$sql2 = "SELECT answer FROM answer WHERE id=3;"; 
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
            if($row['answer'] != $row2['answer']){
                if($row['answer'] == "Q_EMPTY"){
                    if (x < 51 and x > 20){
                    $e1++;
                    $m1++;   
                }else{
                    $e2++;
                    $m2++;
                }}
                else{
                if (x < 51 and x > 20){
                    $m1++;
                }else{
                    $m2++;
                }
                }
            }
        }
        $points = 500-($m1+($m1-$e1)/3+($m2-$e2)/3*4+$m2*4);
        echo "</table><p>".$points."</p>"; 
                $sql = "INSERT INTO result (un, p) VALUES ('".$_SESSION["username"]."','".$points."');";
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
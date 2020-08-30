<?php
require_once "../../SkyMakeDatabaseConnector/SkyMakeDBconfig.php";
include "../../nps/widgets/dash.php";
$_SESSION["examid"] = $_GET["examid"];
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_name("SkyMakeSessionStorage");
session_start();
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: /");
    exit;
}
if(!isset($_SESSION["examid"])){
    header("location: /");
}

$sql = "SELECT * FROM skymake_result WHERE examid='".$_SESSION["examid"]."'";
if($result = mysqli_query($link, $sql)){
    if(mysqli_num_rows($result) > 0){
        echo '<div style="text-align: center;">';
        echo "<table class='table' id='table_rank' style='width:80%; margin-right: auto; margin-left: auto;'>";
        echo "<thead>";
        echo "<tr>";
        echo "<th scope='col'>Username</th>";
        echo "<th scope='col'>Points</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
        while($row = mysqli_fetch_array($result)){
            echo "<tr>";
                echo "<td>" . $row['p'] . "</td>";
                echo "<td>" . $row['un'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "<p><button class='btn btn-dark' onclick=\"sortTable()\">Sort</button></p>
        <script>
function sortTable() {
  var table, rows, switching, i, x, y, shouldSwitch;
  table = document.getElementById(\"table_rank\");
  switching = true;
  while (switching) {
    switching = false;
    rows = table.rows;
    for (i = 1; i < (rows.length - 1); i++) {
      shouldSwitch = false;
      x = rows[i].getElementsByTagName(\"TD\")[0];
      y = rows[i + 1].getElementsByTagName(\"TD\")[0];
      if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
        shouldSwitch = true;
        break;
      }
    }
    if (shouldSwitch) {
      rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
      switching = true;
    }
  }
}
</script>";
        mysqli_free_result($result);
    } else{
        echo "No records matching your query were found.";
    }
} else{
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
}
 
// Close connection
mysqli_close($link);
?>
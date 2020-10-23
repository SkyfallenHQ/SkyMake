<?php
require_once "../../SkyMakeDatabaseConnector/SkyMakeDBconfig.php";
session_name("SkyMakeSessionStorage");
session_start();
if (isset($_GET["lang"])) {
    $locale = $_GET["lang"].".UTF-8";
    $_SESSION["locale"] = $locale;
}
else if (isset($_SESSION["locale"])) {
    $locale  = $_SESSION["locale"];
}
else {
    $locale = "en_US";
    $_SESSION["locale"] = $locale;
}

$txtd = "skymake";
textdomain($txtd);
bindtextdomain($txtd,"locale");
bind_textdomain_codeset($txtd,"UTF-8");

putenv("LANG=".$_SESSION["locale"]);
putenv("LANGUAGE=".$_SESSION["locale"]);

$results = setlocale(LC_ALL,$_SESSION["locale"]);

include "../../nps/widgets/dash.php";
$_SESSION["examid"] = $_GET["examid"];
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
        echo "<th scope='col'>"._("Username")."</th>";
        echo "<th scope='col'>"._("Points")."</th>";
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
        echo "<p><button class='btn btn-dark' onclick=\"sortTable()\">"._("Sort")."</button></p>
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
        echo _("No records matching your query were found.");
    }
} else{
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
}
 
// Close connection
mysqli_close($link);
?>
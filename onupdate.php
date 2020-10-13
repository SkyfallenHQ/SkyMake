<?php
// This code will be executed when SkyMake is updated to a specific package and then be deleted.
include_once "SkyMakeDatabaseConnector/SkyMakeDBconfig.php";
$val = mysqli_query($link,'select 1 from `skymake_board` LIMIT 1');

if($val !== FALSE)
{
    echo "DB already created. Completing update...";
}
else
{
    $sql = "CREATE TABLE `skymake_board` ( `entry` INT NOT NULL AUTO_INCREMENT , `classid` VARCHAR(255) NOT NULL , `sender` VARCHAR(255) NOT NULL , `message` VARCHAR(255) NOT NULL , UNIQUE (`entry`)) ENGINE = InnoDB;";
}
<?php


class SMDB
{
    public static function execute($link,$sql){
        if ($result = mysqli_query($link, $sql)) {
            return true;
        } else {
            die("SQL Failure.Traceback:" . $sql . " Detailed info:" . mysqli_error($link));
        }
    }
    public static function getSetting($link,$setting){
        $sql = "SELECT value FROM skymake_operationvalues WHERE setting=\"".$setting."\";";
        if($result = mysqli_query($link, $sql)){
            if(mysqli_num_rows($result) == 1){
                while($row = mysqli_fetch_array($result)){
                    return $row['value'];
                }
            }
            else{
                return "none";
            }
        }
    }
    public static function setSetting($link,$settingname,$settingvalue){
        $sql = "INSERT INTO skymake_operationvalues (setting,value) VALUES ('".$settingname."','".$settingvalue."');";
        if ($result = mysqli_query($link, $sql)) {
            return true;
        } else {
            die("SQL Failure.Traceback:" . $sql . " Detailed info:" . mysqli_error($link));
        }
    }
    public static function deleteSetting($link,$settingname){
        $sql = "DELETE FROM skymake_operationvalues WHERE setting=\"".$settingname."\";";
        if ($result = mysqli_query($link, $sql)) {
            return true;
        } else {
            die("SQL Failure.Traceback:" . $sql . " Detailed info:" . mysqli_error($link));
        }
    }
}
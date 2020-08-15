<?php


class SMUserClass
{
    function getRole($link,$username){
        $sql = "SELECT role FROM skymake_roles WHERE username=\"".$username."\";";
        if($result = mysqli_query($link, $sql)){
            if(mysqli_num_rows($result) == 1){
                while($row = mysqli_fetch_array($result)){
                    $role = $row['role'];
                    mysqli_free_result($result);
                }
            }
            else {
                $role = "unverified";
            }
        }
        else{
            die("ERROR: Could not able to execute $sql. " . mysqli_error($link));
        }
        mysqli_close($link);
        return $role;
    }
    function setRole($link,$username,$newrole){
        $isroleadded = false;
        $sql = "SELECT role FROM skymake_roles WHERE username=\"".$username."\";";
        if($result = mysqli_query($link, $sql)){
            if(mysqli_num_rows($result) == 1){
                while($row = mysqli_fetch_array($result)){
                    $isroleadded = true;
                    mysqli_free_result($result);
                }
            }
            else{
                $isroleadded = false;
            }
        }
        else{
            die("ERROR: Could not able to execute $sql. " . mysqli_error($link));
        }
        mysqli_close($link);
        if($isroleadded) {
            $sql = "DELETE FROM skymake_roles WHERE username=\"" . $username . "\";";
            if ($result = mysqli_query($link, $sql)) {
                $isroleadded = false;
            } else {
                die("SQL Failure.Traceback:" . $sql . " Detailed info:" . mysqli_error($link));
            }
        }
        mysqli_close($link);
        $sql = "INSERT INTO skymake_roles (username,role) VALUES ('".$username."','".$newrole."');";
            if ($result = mysqli_query($link, $sql)) {
                return true;
            } else {
                die("SQL Failure.Traceback:" . $sql . " Detailed info:" . mysqli_error($link));
            }
    }
    function getStudentClassID($link,$student){
        $sql = "SELECT classid FROM skymake_class_assigned WHERE username=\"".$student."\";";
        if($result = mysqli_query($link, $sql)){
            if(mysqli_num_rows($result) == 1){
                while($row = mysqli_fetch_array($result)){
                    $retval = $row['classid'];
                    mysqli_free_result($result);
                }
            }
            else {
                $retval = "unassigned";
            }
        }
        else{
            die("ERROR: Could not able to execute $sql. " . mysqli_error($link));
        }
        mysqli_close($link);
        return $retval;
    }
}

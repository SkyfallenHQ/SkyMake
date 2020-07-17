<?php
function SkyMakePOSTrequest($url,$key1,$value1,$key2,$value2){
    $data = array($key1 => $value1, $key2 => $value2);
    $options = array(
        'http' => array(
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data)
        )
    );
    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    if ($result === FALSE) { /* Handle error */ }

    var_dump($result);
}
function getassignedlessons($link){
    $sql = "SELECT * FROM skymake_assignments WHERE studentusername='".$_SESSION["username"]."'";
    if($result = mysqli_query($link, $sql)){
        if(mysqli_num_rows($result) > 0){
            $retarr = array();
            while($row = mysqli_fetch_array($result)){
                array_push( $retarr,$row['lesson']);
            }
            mysqli_free_result($result);
        } else{
            return "none";
        }
    } else{
        echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
    }

// Close connection
    return $retarr;
    mysqli_close($link);
}
function getassignedteachers($link){
    $sql = "SELECT * FROM skymake_assignments WHERE studentusername='".$_SESSION["username"]."'";
    if($result = mysqli_query($link, $sql)){
        if(mysqli_num_rows($result) > 0){
            $retarr = array();
            while($row = mysqli_fetch_array($result)){
                array_push( $retarr,$row['teacher']);
            }
            mysqli_free_result($result);
        } else{
            return "none";
        }
    } else{
        echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
    }

// Close connection
    return $retarr;
    mysqli_close($link);
}
function getassignedtimes($link){
    $sql = "SELECT * FROM skymake_assignments WHERE studentusername='".$_SESSION["username"]."'";
    if($result = mysqli_query($link, $sql)){
        if(mysqli_num_rows($result) > 0){
            $retarr = array();
            while($row = mysqli_fetch_array($result)){
                array_push( $retarr,$row['time']);
            }
            mysqli_free_result($result);
        } else{
            return "none";
        }
    } else{
        echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
    }

// Close connection
    return $retarr;
    mysqli_close($link);
}
function getassignedtopics($link){
    $sql = "SELECT * FROM skymake_assignments WHERE studentusername='".$_SESSION["username"]."'";
    if($result = mysqli_query($link, $sql)){
        if(mysqli_num_rows($result) > 0){
            $retarr = array();
            while($row = mysqli_fetch_array($result)){
                array_push( $retarr,$row['topic']);
            }
            mysqli_free_result($result);
        } else{
            return "none";
        }
    } else{
        echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
    }

// Close connection
    return $retarr;
    mysqli_close($link);
}
function getassignedunits($link){
    $sql = "SELECT * FROM skymake_assignments WHERE studentusername='".$_SESSION["username"]."'";
    if($result = mysqli_query($link, $sql)){
        if(mysqli_num_rows($result) > 0){
            $retarr = array();
            while($row = mysqli_fetch_array($result)){
                array_push( $retarr,$row['unit']);
            }
            mysqli_free_result($result);
        } else{
            return "none";
        }
    } else{
        echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
    }

// Close connection
    return $retarr;
    mysqli_close($link);
}
function getassignedbgurls($link){
    $sql = "SELECT * FROM skymake_assignments WHERE studentusername='".$_SESSION["username"]."'";
    if($result = mysqli_query($link, $sql)){
        if(mysqli_num_rows($result) > 0){
            $retarr = array();
            while($row = mysqli_fetch_array($result)){
                array_push( $retarr,$row['bgurl']);
            }
            mysqli_free_result($result);
        } else{
            return "none";
        }
    } else{
        echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
    }

// Close connection
    return $retarr;
    mysqli_close($link);
}

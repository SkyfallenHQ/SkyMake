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
function is_odd($number){
    if($number % 2 == 0){
        return false;
    }
    else{
        return true;
    }
}
function getassignedlessons($link){
    $sql = "SELECT lesson FROM skymake_assignments WHERE classid='".$_SESSION["classid"]."'";
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
        echo "ERROR: Could not able to execute".$sql.".". mysqli_error($link);
    }

// Close connection
    return $retarr;
    mysqli_close($link);
}
function getassignedlessonquery($link,$coursenroller){
    $assignmentsarray = array();
    $sql = "SELECT lessonid FROM skymake_assignments WHERE classid='".$_SESSION["classid"]."'";
    if($result = mysqli_query($link, $sql)){
        if(mysqli_num_rows($result) > 0){
            while($row = mysqli_fetch_array($result)){
                array_push($assignmentsarray,$row["lessonid"]);
            }
            mysqli_free_result($result);
        } else{
            return;
        }
    } else{
        echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
    }

    // Close connection
    mysqli_close($link);
    return array_search($coursenroller,$assignmentsarray);
}
function getassignedteachers($link){
    $sql = "SELECT teacher FROM skymake_assignments WHERE classid='".$_SESSION["classid"]."'";
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
    $sql = "SELECT time FROM skymake_assignments WHERE classid='".$_SESSION["classid"]."'";
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
    $sql = "SELECT topic FROM skymake_assignments WHERE classid='".$_SESSION["classid"]."'";
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
    $sql = "SELECT unit FROM skymake_assignments WHERE classid='".$_SESSION["classid"]."'";
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
    $sql = "SELECT bgurl FROM skymake_assignments WHERE classid='".$_SESSION["classid"]."'";
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
function getassignedids($link){
    $sql = "SELECT lessonid FROM skymake_assignments WHERE classid='".$_SESSION["classid"]."'";
    if($result = mysqli_query($link, $sql)){
        if(mysqli_num_rows($result) > 0){
            $retarr = array();
            while($row = mysqli_fetch_array($result)){
                array_push( $retarr,$row['lessonid']);
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
function getassignedidsteacher($link){
    $sql = "SELECT lessonid FROM skymake_assignments WHERE teacheruser='".$_SESSION["teacheruser"]."'";
    if($result = mysqli_query($link, $sql)){
        if(mysqli_num_rows($result) > 0){
            $retarr = array();
            while($row = mysqli_fetch_array($result)){
                array_push( $retarr,$row['lessonid']);
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
function getsetting($link,$setting){
    $sql = "SELECT value FROM skymake_operationvalues WHERE setting='".$setting."'";
    if($result = mysqli_query($link, $sql)){
        if(mysqli_num_rows($result) == 1){
            while($row = mysqli_fetch_array($result)){
                $retvalue = $row['value'];
            }
            mysqli_free_result($result);
        } else{
            return "errorundefined";
        }
    } else{
        echo "ERROR: CANNOT GET SETTINGS -- Could not able to execute $sql. " . mysqli_error($link);
    }

// Close connection
    return $retvalue;
    mysqli_close($link);
}
function getlessoncontents($link,$lessonid){
    $ret = "";
    $sql = "SELECT * FROM skymake_lessoncontent WHERE lessonid='".$lessonid."'";
    if($result = mysqli_query($link, $sql)){
        if(mysqli_num_rows($result) > 0){
            while($row = mysqli_fetch_array($result)){
                $ret = $ret."<a href='".$row['content-link']."'><p class='lesson-content'>" . $row['content-type']."  (";
                $ret = $ret.$row['content-id'] . ")</p</a><br>";
            }
            // Free result set
            mysqli_free_result($result);
            return $ret;
        } else{
            return "No lesson content was added.";
        }
    } else{
        return "ERROR: Could not able to execute $sql. " . mysqli_error($link);
    }

// Close connection
    mysqli_close($link);
}
function isContentValid($link,$contentid){
    $sql = "SELECT * FROM skymake_lessoncontent WHERE `content-id` ='".$contentid."'";
    if($result = mysqli_query($link, $sql)){
        if(mysqli_num_rows($result) == 1){
            mysqli_free_result($result);
            return true;
        } else{
            return false;
        }
    } else{
        echo "ERROR: Could not able to execute".$sql.".". mysqli_error($link);
    }

// Close connection
    mysqli_close($link);
}
function getLiveClassToken($link,$contentid,$classid,$check_class = true)
{
    $ret = array();
    if($check_class) {
        $sql = "SELECT token FROM skymake_lctokens WHERE contentid='" . $contentid . "' and classid='" . $classid . "'";
    } else{
        $sql = "SELECT token FROM skymake_lctokens WHERE contentid='" . $contentid . "'";
    }
    if ($result = mysqli_query($link, $sql)) {
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_array($result)) {
                array_push($ret,$row["token"]);
            }
            mysqli_free_result($result);
        } else {
            $ret = false;
        }
    }
    else {
            echo "ERROR: Could not able to execute" . $sql . "." . mysqli_error($link);
    }
    return $ret;
    mysqli_close($link);
}
function setLiveClassToken($link,$contentid,$classid,$token)
{
    $sql = "INSERT INTO skymake_lctokens(classid,token,contentid) VALUES ('".$classid."','".$token."','".$contentid."')";
    if ($result = mysqli_query($link, $sql)) {
        return true;
    }
    else {
        echo "ERROR: Could not able to execute" . $sql . "." . mysqli_error($link);
        return false;
    }
    mysqli_close($link);
}


function getassignedlessonsteacher($link){
    $sql = "SELECT lesson FROM skymake_assignments WHERE teacheruser='".$_SESSION["teacheruser"]."'";
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
        echo "ERROR: Could not able to execute".$sql.".". mysqli_error($link);
    }

// Close connection
    return $retarr;
    mysqli_close($link);
}
function getassignedlessonqueryteacher($link,$coursenroller){
    $assignmentsarray = array();
    $sql = "SELECT lessonid FROM skymake_assignments WHERE teacheruser='".$_SESSION["teacheruser"]."'";
    if($result = mysqli_query($link, $sql)){
        if(mysqli_num_rows($result) > 0){
            while($row = mysqli_fetch_array($result)){
                array_push($assignmentsarray,$row["lessonid"]);
            }
            mysqli_free_result($result);
        } else{
            return;
        }
    } else{
        echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
    }

    // Close connection
    mysqli_close($link);
    return array_search($coursenroller,$assignmentsarray);
}
function getassignedtimesteacher($link){
    $sql = "SELECT time FROM skymake_assignments WHERE teacheruser='".$_SESSION["teacheruser"]."'";
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
function getassignedtopicsteacher($link){
    $sql = "SELECT topic FROM skymake_assignments WHERE teacheruser='".$_SESSION["teacheruser"]."'";
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
function getassignedunitsteacher($link){
    $sql = "SELECT unit FROM skymake_assignments WHERE teacheruser='".$_SESSION["teacheruser"]."'";
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
function getassignedbgurlsteacher($link){
    $sql = "SELECT bgurl FROM skymake_assignments WHERE teacheruser='".$_SESSION["teacheruser"]."'";
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
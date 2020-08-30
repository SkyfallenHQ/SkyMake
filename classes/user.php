<?php
    class SMUser
    {
        public static function getRole($link, $username)
        {
            $sql = "SELECT * FROM skymake_roles WHERE username=\"" . $username . "\";";
            if ($result = mysqli_query($link, $sql)) {
                if (mysqli_num_rows($result) == 1) {
                    while ($row = mysqli_fetch_array($result)) {
                        return $row['role'];
                    }
                } else {
                    return "unverified";
                }
            } else {
                die("ERROR: Could not able to execute $sql. " . mysqli_error($link));
            }
        }

        public static function setRole($link, $username, $newrole)
        {
            $isroleadded = false;
            $sql = "SELECT role FROM skymake_roles WHERE username=\"" . $username . "\";";
            if ($result = mysqli_query($link, $sql)) {
                if (mysqli_num_rows($result) == 1) {
                    while ($row = mysqli_fetch_array($result)) {
                        $isroleadded = true;
                        mysqli_free_result($result);
                    }
                } else {
                    $isroleadded = false;
                }
            } else {
                die("ERROR: Could not able to execute $sql. " . mysqli_error($link));
            }
            if ($isroleadded) {
                $sql = "DELETE FROM skymake_roles WHERE username=\"" . $username . "\";";
                if ($result = mysqli_query($link, $sql)) {
                    $isroleadded = false;
                } else {
                    die("SQL Failure.Traceback:" . $sql . " Detailed info:" . mysqli_error($link));
                }
            }
            $sql = "INSERT INTO skymake_roles (username,role) VALUES ('" . $username . "','" . $newrole . "');";
            if ($result = mysqli_query($link, $sql)) {
                return true;
            } else {
                die("SQL Failure.Traceback:" . $sql . " Detailed info:" . mysqli_error($link));
            }
        }

        public static function getStudentClassID($link, $student)
        {
            $sql = "SELECT classid FROM skymake_class_assigned WHERE username='" . $student . "'";
            if ($result = mysqli_query($link, $sql)) {
                if (mysqli_num_rows($result) == 1) {
                    while ($row = mysqli_fetch_array($result)) {
                        $retval = $row['classid'];
                        mysqli_free_result($result);
                    }
                } else {
                    $retval = "unassigned";
                }
            } else {
                die("ERROR: Could not able to execute $sql. " . mysqli_error($link));
            }
            return $retval;
        }
    }
<?php

class MCS
{
    public static function crashLog($crashdata,$crashfile,$reason){
        global $fp;
        if(file_exists("../../logs/SkyMakeCrashes")) {
            $fp = fopen('../../logs/SkyMakeCrashes', 'a');
        } else {
            $fp = fopen('../../logs/SkyMakeCrashes', 'w') or die("SkyMake has crashed. Can't create crashlog file.");
        }
            fwrite($fp, date("D M d, Y G:i") . " - " . $crashdata . " - " . $crashfile . " - " . $reason);
            fclose($fp);

    }

    public static function log($data){
        global $fp;
        if(file_exists("../../logs/SkyMakeLogs")) {
            $fp = fopen('../../logs/SkyMakeLogs', 'a');
        } else {
            $fp = fopen('../../logs/SkyMakeLogs', 'w') or die("SkyMake has crashed. Can't create log file");
        }
        fwrite($fp, date("D M d, Y G:i")." - ".$data);
        fclose($fp);
    }
    public static function getCertificate(){
        $ret = array();
        $url = 'https://pki.theskyfallen.com/distributionsigning/root.pem';
        $file_name = basename($url);
        if(file_put_contents( $file_name,file_get_contents($url))) {
           $ret["success"] = true;
        }else{
            $ret["success"] = false;
        }
        $ret["filename"] = $file_name;
        return $ret;
    }
    public static function connectUpdateServerSecurity($filename){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt ($ch, CURLOPT_CAINFO, dirname(__FILE__)."/".$filename);
        curl_setopt($ch,CURLOPT_URL, "https://swupdate.theskyfallen.com");
        $response = curl_exec($ch);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $header = substr($response, 0, $header_size);
        $body = substr($response, $header_size);
        $ret["header"] = $header;
        $ret["body"] = $body;
        $ret["response"] = $response;
        if (curl_errno($ch)) {
            $ret["error"] = curl_error($ch);
        }
        curl_close($ch);
        return $ret;
    }
}
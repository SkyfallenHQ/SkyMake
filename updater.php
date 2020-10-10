<?php

require_once "SkyMakeConfiguration.php";

session_name("SkyMakeSessionStorage");
session_start();

if(!$_SESSION["loggedin"]){
    header("Location: /?act=signin");
}

function rrmdir($dir,$rmitself = true) {
    if (is_dir($dir)) {
        $objects = scandir($dir);
        foreach ($objects as $object) {
            if ($object != "." && $object != ".." && $object != "UserUploads") {
                if (is_dir($dir. DIRECTORY_SEPARATOR .$object) && !is_link($dir."/".$object))
                    rrmdir($dir. DIRECTORY_SEPARATOR .$object);
                else
                    if($object != "SkyMakeConfiguration.php" and $object != "SkyMakeDBconfig.php" and $object != "updater.php") {
                        unlink($dir . DIRECTORY_SEPARATOR . $object);
                    }
            }
        }
        if($rmitself) {
            rmdir($dir);
        }
    }
}

if(isset($_SESSION["UPDATE_AUTHORIZED"]) and $_SESSION["UPDATE_AUTHORIZED"]) {
    include_once "SkyfallenCodeLib/UpdatesConsoleConnector.php";
    rrmdir(getcwd(),false);
     $ret = \SkyfallenCodeLibrary\UpdatesConsoleConnector::downloadLatestVersion(UPDATES_PROVIDER_APP_ID,UPDATE_SEED,UPDATES_PROVIDER_URL,"");
    if($ret["success"]) {
        if(\SkyfallenCodeLibrary\UpdatesConsoleConnector::installUpdate($ret["path"],getcwd())){
            echo "Updated Successfully";
            unlink($ret["path"]);
            $_SESSION["UPDATE_AUTHORIZED"] = false;
            if(file_exists("onupdate.php")){
                include_once "onupdate.php";
                unlink("onupdate.php");
            }
            header("location: /");
        }else
        {
            echo "Failed to unpack update.";
        }
    }else {
        echo "Failed to download update from the server.";
    }
    }
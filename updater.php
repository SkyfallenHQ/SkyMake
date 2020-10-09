<?php
//Edit for your own updates server
define("UPDATES_PROVIDER_URL","https://swupdate.theskyfallen.com");
define("UPDATES_PROVIDER_APP_ID","541a2fb0429491f158f100ce7dcb0b86");
define("UPDATE_SEED","Stable");

session_name("SkyMakeSessionStorage");
session_start();

function delete_directory($dirname) {
    if (is_dir($dirname))
        $dir_handle = opendir($dirname);
    if (!$dir_handle)
        return false;
    while($file = readdir($dir_handle)) {
        if ($file != "." && $file != "..") {
            if (!is_dir($dirname."/".$file))
                if($file != "SkyMakeDBconfig.php" or $file != "SkyMakeConfiguration.php" or $file != "updater.php") {
                    unlink($dirname . "/" . $file);
                }
            else
                delete_directory($dirname.'/'.$file);
        }
    }
    closedir($dir_handle);
    rmdir($dirname);
    return true;
}

if($_SESSION["UPDATE_AUTHORIZED"] = "TRUE") {
    include_once "SkyfallenCodeLib/UpdatesConsoleConnector.php";
    delete_directory(getcwd());
    $ret = \SkyfallenCodeLibrary\UpdatesConsoleConnector::downloadLatestVersion(UPDATES_PROVIDER_APP_ID,UPDATE_SEED,UPDATES_PROVIDER_URL,"");
    if($ret["success"]) {
        if(\SkyfallenCodeLibrary\UpdatesConsoleConnector::installUpdate($ret["path"],"")){
            echo "Updated Successfully";
        }else
        {
            echo "Failed to unpack update.";
        }
    }else {
        echo "Failed to download update from the server.";
    }
}
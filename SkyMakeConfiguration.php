<?php
define("SkyMakeOnConfigConnect", "CONFCONNOK");
//Start editing here!
define("SFLC_HOST","meet.jit.si");
// That's all. Now stop.

/****************************************************************/
//                 Skyfallen Security Check
//    This will check if the software update server is secure
//       as it can be a threat while updating the software
/****************************************************************/

// Initialize a file URL to the variable
$url = 'https://pki.theskyfallen.com/distributionsigning/root.pem';

// Use basename() function to return the base name of file
$file_name = basename($url);

// Use file_get_contents() function to get the file
// from url and use file_put_contents() function to
// save the file by using base name
if(file_put_contents( $file_name,file_get_contents($url))) {
    echo "Certificate downloaded successfully";
}
else {
    die("We could not reach Skyfallen Servers.");
}
$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HEADER, 1);
curl_setopt ($ch, CURLOPT_CAINFO, dirname(__FILE__)."/root.pem");
curl_setopt($ch,CURLOPT_URL, "https://swupdate.theskyfallen.com");
$response = curl_exec($ch);
$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$header = substr($response, 0, $header_size);
$body = substr($response, $header_size);

echo $header;
//echo $body;
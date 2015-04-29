<?php

require(join(DIRECTORY_SEPARATOR, array(dirname(dirname(__FILE__)), 'lib', 'Pili.php')));


// Replace with your keys here
$accessKey = 'YOUR_ACCESS_KEY';
$secretKey = 'YOUR_SECRET_KEY';


// Instantiate an Pili client
$pili = new Pili($accessKey, $secretKey); # => Object


// Create a new stream
try {

    $hubName         = 'myHub';  // requried, must be exists, replace with your <hubName>
    $title           = NULL;     // optional, default is auto-generated
    $publishKey      = NULL;     // optional, a secret key for signing the <publishToken>
    $publishSecurity = NULL;     // optional, can be "dynamic" or "static", default is "dynamic"

    $stream = $pili->createStream($hubName, $title, $publishKey, $publishSecurity);

    echo "createStream() =>\n";
    var_export($stream);
    echo "\n\n";

} catch (Exception $e) {
    echo 'createStream() failed. Caught exception: ',  $e->getMessage(), "\n";
}



// Generate a publish url
$publishSecurity = 'dynamic'; // optional, can be "dynamic" or "static", default is "dynamic"
$nonce           = 1;         // optional, for "dynamic" only, default is: time()

$publishUrl = $pili->publishUrl($stream['id'], $stream['publishKey'], $publishSecurity, $nonce);

echo "publishUrl() =>\n";
echo $publishUrl;
echo "\n\n";



// Generate RTMP live play URL
$rtmpPlayHost = 'live.z1.glb.pili.qiniucdn.com';  // required, replace with your customized domain
$streamId     = $stream['id'];                    // required
$preset       = NULL; // optional, just like '720p', '480p', '360p', '240p'. Presets should be defined first.

$rtmpLiveUrl = $pili->rtmpLiveUrl($rtmpPlayHost, $streamId, $preset);

echo "rtmpLiveUrl() =>\n";
echo $rtmpLiveUrl;
echo "\n\n";



// Generate HLS live play URL
$hlsPlayHost  = 'hls1.z1.glb.pili.qiniuapi.com'; // required, replace with your customized domain
$streamId     = $stream['id'];                   // required
$preset       = NULL; // optional, just like '720p', '480p', '360p', '240p'. Presets should be defined first.

$hlsLiveUrl = $pili->hlsLiveUrl($hlsPlayHost, $streamId, $preset);

echo "hlsLiveUrl() =>\n";
echo $hlsLiveUrl;
echo "\n\n";



// Generate HLS playback URL
$hlsPlayHost  = 'hls1.z1.glb.pili.qiniuapi.com'; // required, replace with your customized domain
$streamId     = $stream['id'];                   // required
$startTime    = time() - 3600;                   // required
$endTime      = time();                          // required
$preset       = NULL; // optional, just like '720p', '480p', '360p', '240p'. Presets should be defined first.

$hlsPlaybackUrl = $pili->hlsPlaybackUrl($hlsPlayHost, $streamId, $startTime, $endTime, $preset);

echo "hlsPlaybackUrl() =>\n";
echo $hlsPlaybackUrl;
echo "\n\n";



// Get an exist stream
try {

    $streamId = $stream['id'];
    $stream = $pili->getStream($streamId); # => Array

    echo "getStream() =>\n";
    var_export($stream);
    echo "\n\n";

} catch (Exception $e) {
    echo "getStream() failed. Caught exception: ",  $e->getMessage(), "\n";
}



// Update an exist stream
try {

    $streamId        = $stream['id'];      // required
    $publishKey      = '0de4308acc48056a'; // optional, a secret key for signing the <publishToken>
    $publishSecurity = NULL;               // optional, can be "dynamic" or "static", default is "dynamic"

    $stream = $pili->setStream($streamId, $publishKey, $publishSecurity); # => Array

    echo "setStream() =>\n";
    var_export($stream);
    echo "\n\n";

} catch (Exception $e) {
    echo "setStream() failed. Caught exception: ",  $e->getMessage(), "\n";
}



// List streams
try {

    $hubName = 'myHub';  // requried
    $marker  = NULL;     // optional
    $limit   = NULL;     // optional

    $streams = $pili->listStreams($hubName, $marker, $limit); # => Array

    echo "listStreams() =>\n";
    var_export($streams);
    echo "\n\n";

} catch (Exception $e) {
    echo "listStreams() failed. Caught exception: ",  $e->getMessage(), "\n";
}



// Get recording segments from an exist stream
try {

    $streamId  = $stream['id']; // required
    $startTime = NULL;          // optional
    $endTime   = NULL;          // optional

    $segments = $pili->getStreamSegments($streamId, $startTime, $endTime); # => Array

    echo "getStreamSegments() =>\n";
    var_export($segments);
    echo "\n\n";

} catch (Exception $e) {
    echo "getStreamSegments() failed. Caught exception: ",  $e->getMessage(), "\n";
}



// Delete an exist stream
try {

    $streamId  = $stream['id']; // required
    $result = $pili->delStream($streamId); # => Array

    echo "delStream() =>\n";
    var_dump($result);
    echo "\n\n";

} catch (Exception $e) {
    echo "delStream() failed. Caught exception: ",  $e->getMessage(), "\n";
}

?>

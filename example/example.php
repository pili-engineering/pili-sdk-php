<?php

require(join(DIRECTORY_SEPARATOR, array(dirname(dirname(__FILE__)), 'lib', 'PiliIO.php')));

// Replace with your keys
$accessKey = 'YOUR_ACCESS_KEY';
$secretKey = 'YOUR_SECRET_KEY';

// Instantiate an PiliIO client
$pili = new PiliIO($accessKey, $secretKey); # => Object

// Create a new Stream
try {

    $stream = $pili->createStream();

    /* or

    $stream = $pili->createStream(array(
        'is_private' => false,                    # optional, default is false
        'key'        => 'stream secret key',      # optional, default is auto generated
        'comment'    => 'name it or describe it', # optional, like a alias or description
    )); # => Array

     */

    $streamId = $stream['id']; # The only one should be to write the database

} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}


// Get an exist Stream
try {

    $stream = $pili->getStream($streamId); # => Array

    // Live broadcasting URL
    $rtmpPushUrl = $stream['push_url'][0]['RTMP'];
    $rtmpLiveUrl = $stream['live_url']['[original]']['RTMP'];
    $hlsLiveUrl  = $stream['live_url']['[original]']['HLS'];

    // Signing a pushing url, then send it to the pusher client.
    $nonce = time();
    $rtmpPushUrl = $pili->signPushUrl($rtmpPushUrl, $stream['key'], $nonce); # => String
    // ...

    // If the stream is private, we need signing it.
    // Then send it to the player client.
    if (true === $stream['is_private']) {
        $expiry = time() + 3600;
        $rtmpLiveUrl = $pili->signPlayUrl($rtmpLiveUrl, $stream['key'], $expiry); # => String
        $hlsLiveUrl = $pili->signPlayUrl($hlsLiveUrl, $stream['key'], $expiry); # => String
    }
    // ...

} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}

// VOD
// Get the play url of that stream recording segments
// Then send it to the player client.
try {

    $startTime = time() - 3600;
    $endTime = time();
    $expiry = time() + 3600;

    $hlsPlayUrl = $pili->playStreamSegments($streamId, $startTime, $endTime); # => String

    if (true === $stream['is_private']) {
        $hlsPlayUrl = $pili->signPlayUrl($hlsPlayUrl, $stream['key'], $expiry); # => String
    }

} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}


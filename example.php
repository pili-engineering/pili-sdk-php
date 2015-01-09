<?php

require(join(DIRECTORY_SEPARATOR, array(dirname(__FILE__), 'lib', 'PiliIO.php')));

// Replace with your keys
$accessKey = 'YOUR_ACCESS_KEY';
$secretKey = 'YOUR_SECRET_KEY';

// Instantiate an PiliIO client
$pili = new PiliIO($accessKey, $secretKey); # => Object

// Create a new Stream
try {

    $stream = $pili->createStream(array(
        'is_private' => false,                  # optional, default is false
        'stream_key' => 'my stream secret key', # optional, like password, default is auto generated
        'comment'    => 'a public streaming',   # optional, like a alias
    )); # => Array

    // or

    // $stream = $pili->createStream();

    $streamId = $stream['id']; # It's the only thing should write to the database

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
    $rtmpPushUrl = $pili->signPushUrl($rtmpPushUrl, $stream['stream_key'], $nonce); # => String
    // ...

    // If the stream is private, we need signing it.
    // Then send it to the player client.
    if (true === $stream['is_private']) {
        $expiry = time() + 3600;
        $rtmpLiveUrl = $pili->signPlayUrl($rtmpLiveUrl, $stream['stream_key'], $expiry); # => String
        $hlsLiveUrl = $pili->signPlayUrl($hlsLiveUrl, $stream['stream_key'], $expiry); # => String
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
        $hlsPlayUrl = $pili->signPlayUrl($hlsPlayUrl, $stream['stream_key'], $expiry); # => String
    }

} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}


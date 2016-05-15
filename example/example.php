<?php

require(join(DIRECTORY_SEPARATOR, array(dirname(dirname(__FILE__)), 'lib', 'Pili.php')));

$ak="7O7hf7Ld1RrC_fpZdFvU8aCgOPuhw2K4eapYOdII";
$sk="6Rq7rMSUHHqOgo0DJjh15tHsGUBEH9QhWqqyj4ka";
$hubName="PiliSDKTest";

$c=new Qiniu\Credentials($ak,$sk);
$hub=new Pili\Hub($c,$hubName );
try {
    $streamKey="php-sdk-test".time();
    $resp=$hub->create($streamKey);
    print_r($resp);
    $stream = $hub->get($streamKey);
    print_r($stream);
    $resp=$hub->listStreams("php-sdk-test", 1, "");
    print_r($resp);
    $resp=$hub->listLiveStreams("php-sdk-test", 1, "");
    print_r($resp);

    $records= $stream->historyRecord(1463217523,1463303923);
    print_r($records);
    
    $url=$stream->RTMPPublishURL("publish-rtmp.test.com", $hubName, $streamKey, 3600,$ak,$sk);
    echo $url,"\n";

    $url=$stream->RTMPPlayURL("live-rtmp.test.com", $hubName, $streamKey);
    echo $url,"\n";

    $url=$stream->HLSPlayURL("live-hls.test.com", $hubName, $streamKey);
    echo $url,"\n";

    $url=$stream->HDLPlayURL("live-hdl.test.com", $hubName, $streamKey);
    echo $url,"\n";

    $url=$stream->SnapshotPlayURL("live-snapshot.test.com", $hubName, $streamKey);
    echo $url,"\n";


} catch(\Exception $e) {
    echo "Error:",$e;
}

try {
    $stream->enable();
    $status = $stream->liveStatus();
    print_r($status);
}catch(\Exception $e) {
    echo "Error:",$e;
}

try{
    $stream->disable();
    $status=$stream->liveStatus();
    print_r($status);
}catch(\Exception $e) {
    echo "Error:",$e;
}

try{
    $fname=$stream->save(1463217523,1463303923);
    print_r($fname);
}catch(\Exception $e) {
    echo "Error:",$e;
}


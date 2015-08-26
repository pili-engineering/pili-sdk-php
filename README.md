# Pili Streaming Cloud server-side library for PHP

## Features

- Stream Create,Get,List
    - [x] $hub->createStream()
    - [x] $hub->getStream()
    - [x] $hub->listStreams()
- Stream operations else
    - [x] stream->toJSONString()
    - [x] stream->update()
    - [x] stream->disable()
    - [x] stream->enable()
    - [x] stream->status()
    - [x] stream->rtmpPublishUrl()
    - [x] stream->rtmpLiveUrls()
    - [x] stream->hlsLiveUrls()
    - [x] stream->httpFlvLiveUrls()
    - [x] stream->segments()
    - [x] stream->hlsPlaybackUrls()
    - [x] stream->snapshot()
    - [x] stream->saveAs()
    - [x] stream->delete()


## Contents

- [Installation](#installation)
- [Usage](#usage)
    - [Configuration](#configuration)
    - [Hub](#hub)
        - [Instantiate a Pili Hub object](#instantiate-a-pili-hub-object)
        - [Create a new Stream](#create-a-new-stream)
        - [Get a Stream](#get-a-stream)
        - [List Streams](#List-streams)
    - [Stream](#stream)
        - [To JSON string](#to-json-string)
        - [Update a Stream](#update-a-stream)
        - [Disable a Stream](#disable-a-stream)
        - [Enable a Stream](#enable-a-stream)
        - [Get Stream status](#get-stream-status)
        - [Generate RTMP publish URL](#generate-rtmp-publish-url)
        - [Generate RTMP live play URLs](#generate-rtmp-live-play-urls)
        - [Generate HLS live play URLs](generate-hls-live-play-urls)
        - [Generate Http-Flv live play URLs](generate-http-flv-live-play-urls)
        - [Get Stream segments](#get-stream-segments)
        - [Generate HLS playback URLs](generate-hls-playback-urls)
        - [Save Stream as a file](#save-stream-as-a-file)
        - [Snapshot Stream](#snapshot-stream)
        - [Delete a Stream](#delete-a-stream)
- [History](#history)


## Installaion

### Requirements

- PHP >= 5.3.0

### Install with Composer

If you're using [Composer](http://getcomposer.org) to manage dependencies, you can add pili-sdk-php with it.

```bash
# Install Composer
curl -sS https://getcomposer.org/installer | php
```

You can add Pili as a dependency using the `composer.phar` CLI:

```bash
php composer.phar require pili-engineering/pili-sdk-php:dev-master
```

Alternatively, you can specify pili-sdk-php as a dependency in your project's
existing `composer.json` file:

```js
{
    "require": {
        "pili-engineering/pili-sdk-php": "dev-msater"
    }
}
```

After installing, you need to require Composer's autoloader:

```php
require 'vendor/autoload.php';
```

You can find out more on how to install Composer, configure autoloading, and
other best-practices for defining dependencies at <http://getcomposer.org>.

### Install source from GitHub

The `pili-sdk-php` requires PHP `v5.3+`. Download the PHP library from Github, and require in your script like so:

To install the source code:

```bash
$ git clone https://github.com/pili-engineering/pili-sdk-php.git
```

And include it in your scripts:

```php
require_once '/path/to/pili-sdk-php/lib/Pili.php';
```

### Install source from zip/tarball

Alternatively, you can fetch a [tarball](https://github.com/pili-engineering/pili-sdk-php/tarball/master) or [zipball](https://github.com/pili-engineering/pili-sdk-php/zipball/master):

```bash
$ curl -L https://github.com/pili-engineering/pili-sdk-php/tarball/master | tar xzv

(or)

$ wget https://github.com/pili-engineering/pili-sdk-php/tarball/master -O - | tar xzv
```

And include it in your scripts:

```php
require_once '/path/to/pili-sdk-php/lib/Pili.php';
```


## Usage

### Configuration

```php
// Replace with your keys here
define('ACCESS_KEY', 'Qiniu_AccessKey');
define('SECRET_KEY', 'Qiniu_SecretKey');

// Replace with your hub name
define('HUB', 'Pili_Hub_Name'); // The Hub must be exists before use

// Change API host as necessary
// 
// pili.qiniuapi.com as default
// pili-lte.qiniuapi.com is the latest RC version
// 
// $cfg = \Pili\Config::getInstance();
// $cfg->API_HOST = 'pili.qiniuapi.com'; // default
```


### Hub

#### Instantiate a Pili Hub object

```php
// Instantiate an Hub object
$credentials = new \Qiniu\Credentials(ACCESS_KEY, SECRET_KEY); #=> Credentials Object
$hub = new \Pili\Hub($credentials, HUB); # => Hub Object
```


#### Create a new Stream

```php
try {

    $title           = NULL;     // optional, auto-generated as default
    $publishKey      = NULL;     // optional, auto-generated as default
    $publishSecurity = NULL;     // optional, can be "dynamic" or "static", "dynamic" as default

    $stream = $hub->createStream($title, $publishKey, $publishSecurity); # => Stream Object

    echo "createStream() =>\n";
    var_export($stream);
    echo "\n\n";

    /*
    echo $stream->id;
    echo $stream->createdAt;
    echo $stream->updatedAt;
    echo $stream->title;
    echo $stream->hub;
    echo $stream->disabled;
    echo $stream->publishKey;
    echo $stream->publishSecurity;
    echo $stream->hosts;
    echo $stream->hosts["publish"]["rtmp"];
    echo $stream->hosts["live"]["rtmp"];
    echo $stream->hosts["live"]["http"];
    echo $stream->hosts["playback"]["http"];
    */

} catch (Exception $e) {
    echo 'createStream() failed. Caught exception: ',  $e->getMessage(), "\n";
}
/*
Pili\Stream::__set_state(array(
   '_auth' => 
  Pili\Auth::__set_state(array(
     '_accessKey' => '74kG54cpbbkbhTMhnauZLsJObodYXecvlyUnL3AL',
     '_secretKey' => 'gRgMaR7aGmyVrrkkXDVM19zlVq2K2v1ezufRtCpI',
  )),
   '_data' => 
  array (
    'id' => 'z1.coding.55d7a219e3ba5723280000b5',
    'createdAt' => '2015-08-21T18:11:37.057-04:00',
    'updatedAt' => '2015-08-21T18:32:05.186076957-04:00',
    'title' => '55d7a219e3ba5723280000b5',
    'hub' => 'coding',
    'disabled' => false,
    "publishKey":"734de946-11e0-487a-8627-30bf777ed5a3",
    "publishSecurity":"dynamic",
    'hosts' => 
    array (
      'publish' => 
      array (
        'rtmp' => 'iuel7l.publish.z1.pili.qiniup.com',
      ),
      'live' => 
      array (
        'http' => 'iuel7l.live1-http.z1.pili.qiniucdn.com',
        'rtmp' => 'iuel7l.live1-rtmp.z1.pili.qiniucdn.com',
      ),
      'playback' => 
      array (
        'http' => 'iuel7l.playback1.z1.pili.qiniucdn.com',
      ),
    ),
  ),
))
*/
```


#### Get a Stream

```php
try {

    $streamId = $stream->id;

    $stream = $hub->getStream($streamId); # => Stream Object

    echo "getStream() =>\n";
    var_export($stream);
    echo "\n\n";

} catch (Exception $e) {
    echo "getStream() failed. Caught exception: ",  $e->getMessage(), "\n";
}
/*
Pili\Stream::__set_state(array(
   '_auth' => 
  Pili\Auth::__set_state(array(
     '_accessKey' => '74kG54cpbbkbhTMhnauZLsJObodYXecvlyUnL3AL',
     '_secretKey' => 'gRgMaR7aGmyVrrkkXDVM19zlVq2K2v1ezufRtCpI',
  )),
   '_data' => 
  array (
    'id' => 'z1.coding.55d7a219e3ba5723280000b5',
    'createdAt' => '2015-08-21T18:11:37.057-04:00',
    'updatedAt' => '2015-08-21T18:32:05.186076957-04:00',
    'title' => '55d7a219e3ba5723280000b5',
    'hub' => 'coding',
    'disabled' => false,
    "publishKey":"734de946-11e0-487a-8627-30bf777ed5a3",
    "publishSecurity":"dynamic",
    'hosts' => 
    array (
      'publish' => 
      array (
        'rtmp' => 'iuel7l.publish.z1.pili.qiniup.com',
      ),
      'live' => 
      array (
        'http' => 'iuel7l.live1-http.z1.pili.qiniucdn.com',
        'rtmp' => 'iuel7l.live1-rtmp.z1.pili.qiniucdn.com',
      ),
      'playback' => 
      array (
        'http' => 'iuel7l.playback1.z1.pili.qiniucdn.com',
      ),
    ),
  ),
))
*/
```


#### List Streams

```php
try {

    $marker       = NULL;      // optional
    $limit        = NULL;      // optional
    $title_prefix = NULL;      // optional

    $result = $hub->listStreams($marker, $limit, $title_prefix); # => Array

    echo "listStreams() =>\n";
    var_export($result);
    echo "\n\n";

} catch (Exception $e) {
    echo "listStreams() failed. Caught exception: ",  $e->getMessage(), "\n";
}
/*
array (
  'marker' => '2',
  'items' => 
  array (
      0 => Stream Object,
      1 => Stream Object,
  )  
*/
```


### Stream

#### To JSON string

```php
$result = $stream->toJSONString(); # => string
echo "Stream toJSONString() =>\n";
var_export($result);
echo "\n\n";
/*
'{
    "id":"z1.coding.55d7a219e3ba5723280000b5",
    "createdAt":"2015-08-21T18:11:37.057-04:00",
    "updatedAt":"2015-08-21T18:30:32.548-04:00",
    "title":"55d7a219e3ba5723280000b5",
    "hub":"coding",
    "disabled":false,
    "publishKey":"734de946-11e0-487a-8627-30bf777ed5a3",
    "publishSecurity":"dynamic",
    "hosts":{
        "publish":{"rtmp":"iuel7l.publish.z1.pili.qiniup.com"},
        "live":{
            "http":"iuel7l.live1-http.z1.pili.qiniucdn.com",
            "rtmp":"iuel7l.live1-rtmp.z1.pili.qiniucdn.com"
        },
        "playback":{
            "http":"iuel7l.playback1.z1.pili.qiniucdn.com"
        }
    }
}'
*/
```


### Update a Stream

```php
try {

    $stream->publishKey      = 'new_secret_words'; // optional
    $stream->publishSecurity = 'static';           // optional, can be "dynamic" or "static"
    $stream->disabled        = NULL;               // optional, can be "true" of "false"

    $stream = $stream->update(); # => Stream Object

    echo "Stream update() =>\n";
    var_export($stream);
    echo "\n\n";

} catch (Exception $e) {
    echo "Stream update() failed. Caught exception: ",  $e->getMessage(), "\n";
}
/*
Pili\Stream::__set_state(array(
   '_auth' => 
  Pili\Auth::__set_state(array(
     '_accessKey' => '74kG54cpbbkbhTMhnauZLsJObodYXecvlyUnL3AL',
     '_secretKey' => 'gRgMaR7aGmyVrrkkXDVM19zlVq2K2v1ezufRtCpI',
  )),
   '_data' => 
  array (
    'id' => 'z1.coding.55d7a219e3ba5723280000b5',
    'createdAt' => '2015-08-21T18:11:37.057-04:00',
    'updatedAt' => '2015-08-21T18:32:05.186076957-04:00',
    'title' => '55d7a219e3ba5723280000b5',
    'hub' => 'coding',
    'disabled' => false,
    'publishKey' => 'new_secret_words',
    'publishSecurity' => 'static',
    'hosts' => 
    array (
      'publish' => 
      array (
        'rtmp' => 'iuel7l.publish.z1.pili.qiniup.com',
      ),
      'live' => 
      array (
        'http' => 'iuel7l.live1-http.z1.pili.qiniucdn.com',
        'rtmp' => 'iuel7l.live1-rtmp.z1.pili.qiniucdn.com',
      ),
      'playback' => 
      array (
        'http' => 'iuel7l.playback1.z1.pili.qiniucdn.com',
      ),
    ),
  ),
))
*/
```


#### Disable a Stream

```php
$stream = $stream->disable(); # => Stream Object
echo "Stream disable() =>\n";
var_export($stream->disabled);
echo "\n\n";
/*
true
*/
```


#### Enable a Stream

```php
$stream = $stream->enable(); # => Stream Object
echo "Stream enable() =>\n";
var_export($stream->disabled);
echo "\n\n";
/*
false
*/
```


#### Get Stream status

```php
try {

    $result = $stream->status(); # => Array

    echo "Stream status() =>\n";
    var_export($result);
    echo "\n\n";

} catch (Exception $e) {
    echo "Stream status() failed. Caught exception: ",  $e->getMessage(), "\n";
}
/*
array (
  'addr' => '222.73.202.226:2572',
  'status' => 'connected',
  'bytesPerSecond' => 16870.200000000001,
  'framesPerSecond' => 
  array (
    'audio' => 42.200000000000003,
    'video' => 14.733333333333333,
    'data' => 0.066666666666666666,
  ),
)
*/
```


#### Generate RTMP publish URL

```php
$publishUrl = $stream->rtmpPublishUrl();
echo "Stream rtmpPublishUrl() =>\n";
echo $publishUrl;
echo "\n\n";
/*
rtmp://iuel7l.publish.z1.pili.qiniup.com/coding/55d7a219e3ba5723280000b5?key=new_secret_words
*/
```


#### Generate RTMP live play URLs

```php
$urls = $stream->rtmpLiveUrls();
echo "Stream rtmpLiveUrls() =>\n";
var_export($urls);
echo "\n\n";
/*
array (
  'ORIGIN' => 'rtmp://iuel7l.live1-rtmp.z1.pili.qiniucdn.com/coding/55d7a219e3ba5723280000b5',
)
*/
```


#### Generate HLS play live URLs

```php
$urls = $stream->hlsLiveUrls();
echo "Stream hlsLiveUrls() =>\n";
var_export($urls);
echo "\n\n";
/*
array (
  'ORIGIN' => 'http://iuel7l.live1-http.z1.pili.qiniucdn.com/coding/55d7a219e3ba5723280000b5.m3u8',
)
*/
```


#### Generate Http-Flv live play URLs

```php
$urls = $stream->httpFlvLiveUrls();
echo "Stream httpFlvLiveUrls() =>\n";
var_export($urls);
echo "\n\n";
/*
array (
  'ORIGIN' => 'http://iuel7l.live1-http.z1.pili.qiniucdn.com/coding/55d7a219e3ba5723280000b5.flv',
)
*/
```


#### Get Stream segments

```php
try {

    $start = NULL;    // optional, in second, unix timestamp
    $end   = NULL;    // optional, in second, unix timestamp
    $limit = NULL;    // optional, uint

    $result = $stream->segments($start, $end, $limit); # => Array

    echo "Stream segments() =>\n";
    var_export($result);
    echo "\n\n";

} catch (Exception $e) {
    echo "Stream segments() failed. Caught exception: ",  $e->getMessage(), "\n";
}
/*
array (
  'segments' => 
  array (
    0 => 
    array (
      'start' => 1440196065,
      'end' => 1440196124,
    ),
    1 => 
    array (
      'start' => 1440198072,
      'end' => 1440198092,
    ),
  ),
)
*/
```


#### Generate HLS playback URLs

```php
$start     = 1440196065;  // required, in second, unix timestamp
$end       = 1440196105;  // required, in second, unix timestamp

$urls = $stream->hlsPlaybackUrls($start, $end);
echo "Stream hlsPlaybackUrls() =>\n";
var_export($urls);
echo "\n\n";
/*
array (
  'ORIGIN' => 'http://iuel7l.playback1.z1.pili.qiniucdn.com/coding/55d7a219e3ba5723280000b5.m3u8?start=1440196065&end=1440196105',
)
*/
```


#### Save Stream as a file

```php
try {

    $name      = 'videoName.mp4'; // required
    $format    = 'mp4';           // required
    $start     = 1440196065;      // required, in second, unix timestamp
    $end       = 1440196105;      // required, in second, unix timestamp
    $notifyUrl = NULL;            // optional

    $result = $stream->saveAs($name, $format, $start, $end, $notifyUrl = NULL); # => Array

    echo "Stream saveAs() =>\n";
    var_export($result);
    echo "\n\n";

} catch (Exception $e) {
    echo "Stream saveAs() failed. Caught exception: ",  $e->getMessage(), "\n";
}
/*
array (
  'url' => 'http://iuel7l.media1.z1.pili.qiniucdn.com/recordings/z1.coding.55d7a219e3ba5723280000b5/videoName.m3u8',
  'targetUrl' => 'http://iuel7l.vod1.z1.pili.qiniucdn.com/recordings/z1.coding.55d7a219e3ba5723280000b5/videoName.mp4',
  'persistentId' => 'z1.55d7a6e77823de5a49a8899b',
)
*/
```


While invoking `saveAs()` and `snapshot()`, you can get processing state via Qiniu FOP Service using `persistentId`.  
API: `curl -D GET http://api.qiniu.com/status/get/prefop?id={PersistentId}`  
Doc reference: <http://developer.qiniu.com/docs/v6/api/overview/fop/persistent-fop.html#pfop-status>  


#### Snapshot Stream

```php
try {

    $name      = 'imageName.jpg'; // required
    $format    = 'jpg';           // required
    $time      = 1440196100;      // optional, in second, unix timestamp
    $notifyUrl = NULL;            // optional

    $result = $stream->snapshot($name, $format, $time, $notifyUrl); # => Array

    echo "Stream snapshot() =>\n";
    var_export($result);
    echo "\n\n";

} catch (Exception $e) {
    echo "Stream snapshot() failed. Caught exception: ",  $e->getMessage(), "\n";
}
/*
array (
  'targetUrl' => 'http://iuel7l.static1.z1.pili.qiniucdn.com/snapshots/z1.coding.55d7a219e3ba5723280000b5/imageName.jpg',
  'persistentId' => 'z1.55d7a6e77823de5a49a8899a',
)
*/
```


#### Delete a Stream

```php
try {
    $result = $stream->delete(); # => NULL
    echo "Stream delete() =>\n";
    var_dump($result);
    echo "\n\n";
} catch (Exception $e) {
    echo "Stream delete() failed. Caught exception: ",  $e->getMessage(), "\n";
}
/*
NULL
*/
```


## History

- 1.5.0
    - Add Credentials and Transport class
    - Renamed $client to $hub
- 1.4.0
    - Add Stream Create,Get,List
        - $hub->createStream()
        - $hub->getStream()
        - $hub->listStreams()
    - Add Stream operations else
        - $stream->toJSONString()
        - $stream->update()
        - $stream->disable()
        - $stream->enable()
        - $stream->status()
        - $stream->segments()
        - $stream->rtmpPublishUrl()
        - $stream->rtmpLiveUrls()
        - $stream->hlsLiveUrls()
        - $stream->httpFlvLiveUrls()
        - $stream->hlsPlaybackUrls()
        - $stream->snapshot()
        - $stream->saveAs()
        - $stream->delete()

# Pili Streaming Cloud server-side library for PHP

## Features

- Stream Create,Get,List
    - [x] $hub->create()
    - [x] $hub->get()
    - [x] $hub->listLiveStreams()
    - [x] $hub->listStreams()
- Stream operations else
    - [x] stream->enable()
    - [x] stream->disable()
    - [x] stream->liveStatus()
    - [x] stream->historyRecord()
    - [x] stream->save()
    - [x] stream->RTMPPublishURL()
    - [x] stream->RTMPPlayURL()
    - [x] stream->HLSPlayURL()
    - [x] stream->HDLPlayURL()
    - [x] stream->SnapshotPlayURL()


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
        - [Enable a stream](#enable-a-stream)
        - [Disable a Stream](#disable-a-stream)
        - [Get Stream status](#get-stream-status)
        - [Get History record](#get-history-record)
        - [Save Stream as a file](#save-stream-as-a-file)
        - [Generate RTMP publish URL](#generate-rtmp-publish-url)
        - [Generate RTMP live play URLs](#generate-rtmp-live-play-urls)
        - [Generate HLS live play URLs](generate-hls-live-play-urls)
        - [Generate Http-Flv live play URLs](generate-http-flv-live-play-urls)
        - [Snapshot Stream](#snapshot-stream)


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
        "pili-engineering/pili-sdk-php": "dev-master"
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
try{
    $streamKey="php-sdk-test".time();
    $resp=$hub->create($streamKey);
    print_r($resp);
}catch(\Exception $e) {
         echo "Error:",$e;
}
```


#### Get a Stream

```php
try{
    $streamKey="php-sdk-test".time();
    $resp=$hub->get($streamKey);
    print_r($resp);
}catch(\Exception $e) {
         echo "Error:",$e;
}
```


#### List Streams

```php
try{
    $streamKey="php-sdk-test".time();
    $resp=$hub->listStreams($streamKey, 1, "");
    print_r($resp);
}catch(\Exception $e) {
         echo "Error:",$e;
}
```


### Stream

### Enable a Stream

```php
   $stream->enable();
```


#### Disable a Stream

```php
   $stream->disable();
```


#### Get Stream status

```php
   try{
       $status=$stream->liveStatus();
       print_r($status);
   }catch(\Exception $e) {
       echo "Error:",$e;
   }
```

#### Get History record

```php
    $records= $stream->historyRecord(1463217523,1463303923);
    print_r($records);
```

#### Save Stream as a file

```php
    try{
        $fname=$stream->save(1463217523,1463303923);
        print_r($fname);
    }catch(\Exception $e) {
        echo "Error:",$e;
    }
```


#### Generate RTMP publish URL

```php
    $url=$stream->RTMPPublishURL("publish-rtmp.test.com", $hubName, $streamKey, 3600,$ak,$sk);
```


#### Generate RTMP live play URLs

```php
    $url=$stream->RTMPPlayURL("live-rtmp.test.com", $hubName, $streamKey);
```


#### Generate HLS play live URLs

```php
    $url=$stream->HLSPlayURL("live-hls.test.com", $hubName, $streamKey);
```


#### Generate Http-Flv live play URLs

```php
    $url=$stream->HDLPlayURL("live-hdl.test.com", $hubName, $streamKey);
```

#### Snapshot Stream

```php
    $url=$stream->SnapshotPlayURL("live-snapshot.test.com", $hubName, $streamKey);
```


## History
- 2.0.0
    - pili.v2
- 1.5.4
    - Use $stream->saveAs in $stream->hlsPlaybackUrls

- 1.5.3
    - Update $stream->disable($disabledTill)

- 1.5.2
    - Update $stream->rtmpPublishUrl()

- 1.5.1
    - Update API
        - $hub->listStreams($marker=NULL, $limit=NULL, $title_prefix=NULL, $status=NULL)
        - $stream->saveAs($name, $format=NULL, $start=NULL, $end=NULL, $notifyUrl=NULL, $pipeline=NULL)
        - $stream->snapshot($name, $format, $time=NULL, $notifyUrl=NULL, $pipeline=NULL)
        - $stream->hlsPlaybackUrls($start=-1, $end=-1)
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

# PILI直播 PHP服务端SDK 使用指南

## 功能列表

- 直播流的创建、获取和列举
    - [x] hub.createStream()  // 创建流
    - [x] hub.getStream()  // 获取流
    - [x] hub.listStreams()  // 列举流
- 直播流的其他功能
    - [x] stream.toJsonString()  // 流信息转为json
    - [x] stream.update()      // 更新流
    - [x] stream.disable()      // 禁用流
    - [x] stream.enable()    // 启用流
    - [x] stream.status()     // 获取流状态
    - [x] stream.rtmpPublishUrl()   // 生成推流地址
    - [x] stream.rtmpLiveUrls()    // 生成rtmp播放地址
    - [x] stream.hlsLiveUrls()   // 生成hls播放地址
    - [x] stream.httpFlvLiveUrls()   // 生成flv播放地址
    - [x] stream.segments()      // 获取流片段
    - [x] stream.hlsPlaybackUrls()  // 生成hls回看地址
    - [x] stream.saveAs()        // 流另存为文件
    - [x] stream.snapshot()      // 获取快照
    - [x] stream.delete()    // 删除流


## 目录

- [安装](#installation)
- [用法](#usage)
    - [配置](#configuration)
    - [Hub](#hub)
        - [实例化hub对象](#instantiate-a-pili-hub-object)
        - [创建流](#create-a-new-stream)
        - [获取流](#get-an-exist-stream)
        - [列举流](#list-streams)
    - [直播流](#stream)
        - [流信息转为json](#to-json-string)
        - [更新流](#update-a-stream)
        - [禁用流](#disable-a-stream)
        - [启用流](#enable-a-stream)
        - [获取流状态](#get-stream-status)
        - [生成推流地址](#generate-rtmp-publish-url)
        - [生成rtmp播放地址](#generate-rtmp-live-play-urls)
        - [生成hls播放地址](#generate-hls-play-urls)
        - [生成flv播放地址](#generate-http-flv-live-play-urls)
        - [获取流片段](#get-stream-segments)
        - [生成hls回看地址](#generate-hls-playback-urls)
        - [流另存为文件](#save-stream-as-a-file)
        - [获取快照](#snapshot-stream)
        - [删除流](#delete-a-stream)
- [History](#history)


<a id="installation"></a>
## 安装

### 使用要求

- PHP >= 5.3.0

### 使用Composer安装

如果你需要使用[Composer](http://getcomposer.org) 来管理依赖, 你可以使用Composer来添加 pili-sdk-php.

```bash
# 安装Composer
curl -sS https://getcomposer.org/installer | php
```

使用 Composer 获取最新版本的pili-sdk-php :

```bash
php composer.phar require pili-engineering/pili-sdk-php:dev-master
```

或者, 你可以在你项目中的`composer.json`里面指定pili-sdk-php为依赖包 :

```js
{
    "require": {
        "pili-engineering/pili-sdk-php": "dev-msater"
    }
}
```

安装之后, 你代码中 require Composer生成的 autoloader:

```php
require 'vendor/autoload.php';
```

如果需要更详细的关于 Composer 的使用说明，你可以访问 Composer 的官方网站http://getcomposer.org/，或对应的中文网站 http://www.phpcomposer.com/。

### 通过GitHub使用源码安装

`pili-sdk-php` 需要 PHP `v5.3+`，下载php-sdk的源码，然后像下面介绍的这样导入:

安装源码:

```bash
$ git clone https://github.com/pili-engineering/pili-sdk-php.git
```

导入到你的脚本中:

```php
require_once '/path/to/pili-sdk-php/lib/Pili.php';
```

### 通过zip/tarball安装源码

或者，直接这里获取： [tarball](https://github.com/pili-engineering/pili-sdk-php/tarball/master) or [zipball](https://github.com/pili-engineering/pili-sdk-php/zipball/master):

```bash
$ curl -L https://github.com/pili-engineering/pili-sdk-php/tarball/master | tar xzv

(or)

$ wget https://github.com/pili-engineering/pili-sdk-php/tarball/master -O - | tar xzv
```

导入到你的脚本中:

```php
require_once '/path/to/pili-sdk-php/lib/Pili.php';
```


<a id="usage"></a>
## 用法:

<a id="configuration"></a>
### 配置

```php
// Replace with your keys here
define('ACCESS_KEY', 'Qiniu_AccessKey');
define('SECRET_KEY', 'Qiniu_SecretKey');

// Replace with your hub name
define('HUB', 'Pili_Hub_Name'); // 使用前必须需要先要获得HUB

# 如有需要可以更改API host
# 
# 默认为 pili.qiniuapi.com
# pili-lte.qiniuapi.com 为最近更新版本
# 
# conf.API_HOST = 'pili.qiniuapi.com' # 默认
```


### Hub

<a id="instantiate-a-pili-hub-object"></a>
#### 实例化hub对象

```php
// Instantiate an Hub object
$credentials = new \Qiniu\Credentials(ACCESS_KEY, SECRET_KEY); #=> Credentials Object
$hub = new \Pili\Hub($credentials, HUB); # => Hub Object
```


<a id="create-a-new-stream"></a>
#### 创建流

```php
try {

    $title           = NULL;     // 选填，默认自动生成
    $publishKey      = NULL;     // 选填，默认自动生成
    $publishSecurity = NULL;     // 选填, 可以为 "dynamic" 或 "static", 默认为 "dynamic" 

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

<a id="get-an-exist-stream"></a>
#### 获取流

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


<a id="list-streams"></a>
#### 列举流

```php
try {

    $marker       = NULL;      // 可选
    $limit        = NULL;      // 可选
    $title_prefix = NULL;      // 可选

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


<a id="stream"></a>
### 直播流

<a id="to-json-string"></a>
#### 流信息转为json

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


<a id="update-a-stream"></a>
#### 更新流

```php
try {

    $stream->publishKey      = 'new_secret_words'; // 选填
    $stream->publishSecurity = 'static';           // 选填, 可以为 "dynamic" 或 "static", 默认为 "dynamic"
    $stream->disabled        = NULL;               // 选填, 可以为 "true" 或 "false"

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


<a id="disable-a-stream"></a>
#### 禁用流

```php
$stream = $stream->disable(); # => Stream Object
echo "Stream disable() =>\n";
var_export($stream->disabled);
echo "\n\n";
/*
true
*/
```


<a id="enable-a-stream"></a>
#### 启用流

```php
$stream = $stream->enable(); # => Stream Object
echo "Stream enable() =>\n";
var_export($stream->disabled);
echo "\n\n";
/*
false
*/
```


<a id="get-stream-status"></a>
#### 获取流状态

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


<a id="generate-rtmp-publish-url"></a>
#### 生成推流地址

```php
$publishUrl = $stream->rtmpPublishUrl();
echo "Stream rtmpPublishUrl() =>\n";
echo $publishUrl;
echo "\n\n";
/*
rtmp://iuel7l.publish.z1.pili.qiniup.com/coding/55d7a219e3ba5723280000b5?key=new_secret_words
*/
```


<a id="generate-rtmp-live-play-urls"></a>
#### 生成rtmp播放地址

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


<a id="generate-hls-play-urls"></a>
#### 生成hls播放地址

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


<a id="generate-http-flv-live-play-urls"></a>
#### 生成flv播放地址

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


<a id="get-stream-segments"></a>
#### 获取流片段 

```php
try {

    $start = NULL;    // 选填, 单位为秒, 为UNIX时间戳
    $end   = NULL;    // 选填, 单位为秒, 为UNIX时间戳
    $limit = NULL;    // 选填, uint 

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


<a id="generate-hls-playback-urls"></a>
#### 生成hls回看地址

```php
$start     = 1440196065;  // 必填, 单位为秒, 为UNIX时间戳
$end       = 1440196105;  // 必填, 单位为秒, 为UNIX时间戳

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


<a id="save-stream-as-a-file"></a>
#### 流另存为文件

```php
try {

    $name      = 'videoName.mp4'; // 必填
    $format    = 'mp4';           // 必填
    $start     = 1440196065;      // 必填, 单位为秒, 为UNIX时间戳
    $end       = 1440196105;      // 必填, 单位为秒, 为UNIX时间戳
    $notifyUrl = NULL;            // 选填

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


当使用 `saveAs()` 和 `snapshot()` 的时候, 由于是异步处理， 你可以在七牛的FOP接口上使用 `persistentId`来获取处理进度.参考如下：   
API: `curl -D GET http://api.qiniu.com/status/get/prefop?id={persistentId}`  
文档说明: <http://developer.qiniu.com/docs/v6/api/overview/fop/persistent-fop.html#pfop-status> 

<a id="snapshot-stream"></a>
#### 获取快照

```php
try {

    $name      = 'imageName.jpg'; // 必填
    $format    = 'jpg';           // 必填
    $time      = 1440196100;      // 选填, 单位为秒, 为UNIX时间戳
    $notifyUrl = NULL;            // 选填

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


<a id="delete-a-stream"></a>
#### 删除流

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

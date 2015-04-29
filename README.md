# Pili SDK for PHP

## Requirements

- PHP >= 5.3.0

## Install with Composer

If you're using [Composer](http://getcomposer.org) to manage dependencies, you can add pili-sdk-php with it.

```bash
# Install Composer
curl -sS https://getcomposer.org/installer | php
```

You can add PILI as a dependency using the `composer.phar` CLI:

```bash
php composer.phar require pili-io/pili-sdk-php:dev-master
```

Alternatively, you can specify pili-sdk-php as a dependency in your project's
existing `composer.json` file:

```js
{
    "require": {
        "pili-io/pili-sdk-php": "dev-msater"
    }
}
 ```

After installing, you need to require Composer's autoloader:

```php
require 'vendor/autoload.php';
```

You can find out more on how to install Composer, configure autoloading, and
other best-practices for defining dependencies at <http://getcomposer.org>.

## Install source from GitHub

The `pili-sdk-php` requires PHP `v5.3+`. Download the PHP library from Github, and require in your script like so:

To install the source code:

```bash
$ git clone https://github.com/pili-io/pili-sdk-php.git
```

And include it in your scripts:

```php
require_once '/path/to/pili-sdk-php/lib/Pili.php';
```

## Install source from zip/tarball

Alternatively, you can fetch a [tarball](https://github.com/pili-io/pili-sdk-php/tarball/master) or [zipball](https://github.com/pili-io/pili-sdk-php/zipball/master):

```bash
$ curl -L https://github.com/pili-io/pili-sdk-php/tarball/master | tar xzv

(or)

$ wget https://github.com/pili-io/pili-sdk-php/tarball/master -O - | tar xzv
```

And include it in your scripts:

```php
require_once '/path/to/pili-sdk-php/lib/Pili.php';
```

## Usage

Instantiate an Pili client:

```php
// Replace with your keys
$pili = new Pili($accessKey, $secretKey); # => Object
```


Create a new Stream:

```php
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
```


Generate a publish url:

```php
$publishSecurity = 'dynamic'; // optional, can be "dynamic" or "static", default is "dynamic"
$nonce           = 1;         // optional, for "dynamic" only, default is: time()

$publishUrl = $pili->publishUrl($stream['id'], $stream['publishKey'], $publishSecurity, $nonce);

echo "publishUrl() =>\n";
echo $publishUrl;
echo "\n\n";
```


Generate RTMP live play URL:

```php
$rtmpPlayHost = 'live.z1.glb.pili.qiniucdn.com';  // required
$streamId     = $stream['id'];                    // required
$preset       = NULL; // optional, just like '720p', '480p', '360p', '240p'. Presets should be defined first.

$rtmpLiveUrl = $pili->rtmpLiveUrl($rtmpPlayHost, $streamId, $preset);

echo "rtmpLiveUrl() =>\n";
echo $rtmpLiveUrl;
echo "\n\n";
```


Generate HLS live play URL:

```php
$hlsPlayHost  = 'hls1.z1.glb.pili.qiniuapi.com'; // required
$streamId     = $stream['id'];                   // required
$preset       = NULL; // optional, just like '720p', '480p', '360p', '240p'. Presets should be defined first.

$hlsLiveUrl = $pili->hlsLiveUrl($hlsPlayHost, $streamId, $preset);

echo "hlsLiveUrl() =>\n";
echo $hlsLiveUrl;
echo "\n\n";
```


Generate HLS playback URL:

```php
$hlsPlayHost  = 'hls1.z1.glb.pili.qiniuapi.com'; // required
$streamId     = $stream['id'];                   // required
$startTime    = time() - 3600;                   // required
$endTime      = time();                          // required
$preset       = NULL; // optional, just like '720p', '480p', '360p', '240p'. Presets should be defined first.

$hlsPlaybackUrl = $pili->hlsPlaybackUrl($hlsPlayHost, $streamId, $startTime, $endTime, $preset);

echo "hlsPlaybackUrl() =>\n";
echo $hlsPlaybackUrl;
echo "\n\n";
```


Get an exist stream:

```php
try {

    $streamId = $stream['id'];
    $stream = $pili->getStream($streamId); # => Array

    echo "getStream() =>\n";
    var_export($stream);
    echo "\n\n";

} catch (Exception $e) {
    echo "getStream() failed. Caught exception: ",  $e->getMessage(), "\n";
}
```


Update an exist stream:

```php
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
```


List streams:

```php
try {

    $hubName = 'coding'; // requried
    $marker  = NULL;     // optional
    $limit   = NULL;     // optional

    $streams = $pili->listStreams($hubName, $marker, $limit); # => Array

    echo "listStreams() =>\n";
    var_export($streams);
    echo "\n\n";

} catch (Exception $e) {
    echo "listStreams() failed. Caught exception: ",  $e->getMessage(), "\n";
}
```


Get recording segments from an exist stream:

```php
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
```


Delete an exist stream:

```php
try {

    $streamId  = $stream['id']; // required
    $result = $pili->delStream($streamId); # => Array

    echo "delStream() =>\n";
    var_dump($result);
    echo "\n\n";

} catch (Exception $e) {
    echo "delStream() failed. Caught exception: ",  $e->getMessage(), "\n";
}
```
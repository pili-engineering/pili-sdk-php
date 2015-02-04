# PILI SDK for PHP

The PILI SDK for PHP enables PHP developers to use Pili Live Streaming Cloud Services in their PHP code for building robust applications and software.

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
require_once '/path/to/pili-sdk-php/lib/PiliIO.php';
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
require_once '/path/to/pili-sdk-php/lib/PiliIO.php';
```

## Usage

```php
// Instantiate an PiliIO client
$pili = new PiliIO($accessKey, $secretKey); # => Object

// Create a new Stream
$stream = $pili->createStream();

/* or

$stream = $pili->createStream(array(
    'is_private' => false,                    # optional, default is false
    'key'        => 'stream secret key',      # optional, default is auto generated
    'comment'    => 'name it or describe it', # optional, like a alias or description
)); # => Array

*/

// List exist Streams
$pili->listStreams(); # => Array

// Query a Stream
$pili->getStream($streamId); # => Array

// Update a Stream
$pili->setStream($streamId, array(
    'is_private' => true,
    'key'        => 'a new stream secret key',
    'comment'    => 'a private streaming',
)); # => Array

// Delete a Stream
$pili->delStream($streamId); # => NULL

// Get Status on a Stream
$pili->getStreamStatus($streamId); # => Array

// Get recording segments from a Stream
$pili->getStreamSegments($streamId, $startTime, $endTime); # => Array

// Get the play url of those stream recording segments
$pili->playStreamSegments($streamId, $startTime, $endTime); # => String

// Delete recording segments on a Stream
$pili->delStreamSegments($streamId, $startTime, $endTime); # => Array

// Signing a push url
$pili->signPushUrl($pushUrl, $streamKey, $nonce); # => String

// Signing a private play url
$pili->signPlayUrl($playUrl, $streamKey, $expiry); # => String

```

## Quick Example

```php
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

```

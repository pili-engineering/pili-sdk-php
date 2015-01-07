# PILI SDK for PHP

The PILI SDK for PHP enables PHP developers to use PILI Live Streaming Cloud Services in their PHP code for building robust applications and software.


## Requirements

- PHP 5.4.0+
- [Guzzle](https://github.com/guzzle/guzzle) - PHP HTTP client and framework

## Installation

The recommended way to install PILI is with [Composer](http://getcomposer.org). 
Composer is a dependency management tool for PHP that allows you to declare the 
dependencies your project needs and installs them into your project.

```bash

    # Install Composer
    curl -sS https://getcomposer.org/installer | php
```

You can add PILI as a dependency using the `composer.phar` CLI:

```bash

    php composer.phar require pili-io/pili-php
```

Alternatively, you can specify PILI as a dependency in your project's
existing `composer.json` file:

```js

    {
      "require": {
         "pili-io/pili-php": "*"
      }
   }
 ```

After installing, you need to require Composer's autoloader:

```php

    require 'vendor/autoload.php';
```

You can find out more on how to install Composer, configure autoloading, and
other best-practices for defining dependencies at [getcomposer.org](http://getcomposer.org).


## Quick Example

```php

    <?php
    require 'vendor/autoload.php';

    use Pili\Application;

    // Instantiate an PILI client
    $app = new Application($accessKey, $secretKey); # => Object

    // Create a new Streaming
    $app->createStream(array(
        'is_private' => false, 
        'comment'    => 'a public streaming',
    )); # => Array

    // List exist Streams
    $app->listStreams(); # => Array

    // Query a Stream
    $app->getStream($streamId); # => Array

    // Update a Stream
    $app->setStream($streamId, array(
        'is_private' => true, 
        'stream_key' => 'a new stream key', 
        'comment'    => 'a private streaming',
    )); # => Array

    // Delete a Stream
    $app->delStream($streamId); # => NULL

    // Get Status on a Stream
    $app->getStreamStatus($streamId); # => Array

    // Get recording segments from a Stream
    $app->getStreamSegments($streamId, $startTime, $endTime); # => Array

    // Get the play url of those stream recording segments
    $app->playStreamSegments($streamId, $startTime, $endTime); # => Array

    // Delete recording segments on a Stream
    $app->delStreamSegments($streamId, $startTime, $endTime); # => Array

    // Signing a push url
    $app->signPushUrl($pushUrl, $streamKey, $nonce); # => String

    // Signing a private play url
    $app->signPlayUrl($playUrl, $streamKey, $expiry); # => String
```


## Contributing

### Guidelines

1. PILI follows PSR-0, PSR-1, and PSR-2.
2. It is meant to be lean and fast with very few dependencies.
3. PILI has a minimum PHP version requirement of PHP 5.4. Pull requests must
   not require a PHP version greater than PHP 5.4.
4. All pull requests must include unit tests to ensure the change works as
   expected and to prevent regressions.

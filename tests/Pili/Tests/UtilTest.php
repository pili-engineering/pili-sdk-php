<?php

namespace Pili\Tests;

use Qiniu\Utils;

class Base64Test extends \PHPUnit_Framework_TestCase
{
    public function testUrlSafe()
    {
        $a = '你好';
        $b = Utils::base64UrlEncode($a);
        $this->assertEquals($a, Utils::base64UrlDecode($b));
    }
}

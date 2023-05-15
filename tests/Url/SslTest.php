<?php

use PHPUnit\Framework\TestCase;
use FrUtility\Url\Ssl;
use FrUtility\Url\Url;
use FrUtility\Url\Utility as UrlUtil;

class SslTest extends TestCase
{
    public function testSsl()
    {
        $url = 'https://ryo1999.com';

        $domain = UrlUtil::getDomain($url);

        $ssl = new Ssl($domain);

        $this->assertSame('live', $ssl->status);
        $this->assertTrue($ssl->expire_left > 0);
    }

    public function testSslFromUrl()
    {
        $url = 'https://ryo1999.com';

        $Url = new Url($url);

        $this->assertSame('live', $Url->getSsl()->status);
        $this->assertTrue($Url->getSsl()->expire_left > 0);
    }
}

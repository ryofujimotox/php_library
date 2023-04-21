<?php

use PHPUnit\Framework\TestCase;
use FrUtility\Other\Ssl;
use FrUtility\Other\Url;

class SslTest extends TestCase
{
    /**
     *
     * SSL情報の確認
     *
     */
    public function testSsl()
    {
        $url = 'https://ryo1999.com';
        $domain = Url::getDomain($url);
        $ssl = new Ssl($domain);

        $this->assertSame('live', $ssl->status);
        $this->assertTrue($ssl->expire_left > 0);
    }
}

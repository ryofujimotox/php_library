<?php

use PHPUnit\Framework\TestCase;
use FrUtility\Other\Request;

class RequestTest extends TestCase
{
    /**
     *
     * GET処理確認
     *
     */
    public function testRequest()
    {
        // 変わる可能性高いね
        $url = 'https://umayadia-apisample.azurewebsites.net/api/persons';
        $result = Request::get($url);
        $this->assertSame('徳川家康', $result['data'][0]['name']);
    }
}

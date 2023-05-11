<?php
use PHPUnit\Framework\TestCase;
use FrUtility\Url\Url;

/**
 *
 * 細かくは UrlUtilityTest の方で見る。
 *
 */
class UrlMainTest extends TestCase
{
    /**
     * ドメイン確認
     */
    public function testDomain()
    {
        $Url = new Url('https://ryo1999.com');
        $this->assertEquals($Url->getDomain(), 'ryo1999.com');
    }

    /**
     * url変更
     */
    public function testModifyParams()
    {
        $Url = new Url('https://ryo1999.com');
        $Url->modifyParams(['test' => '1']);
        $url = $Url->getString();
        $this->assertEquals($url, 'https://ryo1999.com?test=1');
    }

    /**
     * パラメ取得
     */
    public function testGetParams()
    {
        //
        $Url = new Url('https://ryo1999.com?test=1');
        $this->assertEquals($Url->getParams(), ['test' => 1]);

        //
        $Url = new Url('https://ryo1999.com?test=1');
        $this->assertEquals($Url->getParams(['test2' => 2]), ['test' => 1, 'test2' => 2]);
    }
}

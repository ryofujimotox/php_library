<?php

use PHPUnit\Framework\TestCase;
use FrUtility\Other\Url;

class UrlTest extends TestCase
{
    /**
     *
     * GETパラメータを再構築する
     *
     */
    public function testUrlQuery()
    {
        // これをつける。
        $updateParams = ['test' => 'test', 'OK' => 'OK'];

        // 追加
        $newParams = Url::getParams($this->getUrl(), $updateParams);
        $wantParams = $updateParams;
        $this->assertSame($newParams, $wantParams);

        // 書き換え
        $base_added = ['test' => 'remove', 'OK' => 'NO'];
        $newParams = Url::getParams($this->getUrl($base_added), $updateParams);
        $wantParams = $updateParams;
        $this->assertSame($newParams, $wantParams);

        // 1つ追加
        $base_added = ['test1' => 'test'];
        $newParams = Url::getParams($this->getUrl($base_added), $updateParams);
        $wantParams = array_merge($base_added, $updateParams);
        $this->assertSame($newParams, $wantParams);

        // 2つ追加
        $base_added = ['test1' => 'test', 'test2' => 'test'];
        $newParams = Url::getParams($this->getUrl($base_added), $updateParams);
        $wantParams = array_merge($base_added, $updateParams);
        $this->assertSame($newParams, $wantParams);

        // 1つ削除
        $base_added = ['test1' => 'test', 'test2' => 'test'];
        $updateParams = ['test1' => null];
        $newParams = Url::getParams($this->getUrl($base_added), $updateParams);
        $wantParams = ['test2' => 'test'];
        $this->assertSame($newParams, $wantParams);

        // 全部削除
        $base_added = ['test1' => 'test', 'test2' => 'test'];
        $updateParams = ['test1' => null, 'test2' => null];
        $newParams = Url::getParams($this->getUrl($base_added), $updateParams);
        $wantParams = [];
        $this->assertSame($newParams, $wantParams);
    }

    public function getUrl($param = [])
    {
        $base = 'https://ryo1999.com';
        return Url::modifyParams($base, $param);
    }

    /**
     *
     * URLのGETパラメータをつける。
     *
     */
    public function testUrl()
    {
        $base = 'https://ryo1999.com';

        // これをつける。
        $updateParams = ['test' => 'test', 'OK' => 'OK'];

        // これがつくはず
        $add = 'test=test&OK=OK';

        /**
         *
         */
        $_base = $base;
        $url = Url::modifyParams($_base, $updateParams);
        $this->assertSame("{$_base}?{$add}", $url);
        //　編集 - 削除
        $url = Url::modifyParams($url, ['test' => null]);
        $this->assertSame("{$_base}?OK=OK", $url);

        /**
         *
         */
        $_base = $base . '?';
        $url = Url::modifyParams($_base, $updateParams);
        $this->assertSame("{$_base}{$add}", $url);
        // 編集 - 書き換え
        $url = Url::modifyParams($url, ['OK' => 'NO']);
        $this->assertSame("{$_base}test=test&OK=NO", $url);

        /**
         *
         */
        $_base = $base . '/test/';
        $url = Url::modifyParams($_base, $updateParams);
        $this->assertSame("{$_base}?{$add}", $url);
        // 編集 - 全部削除
        $url = Url::modifyParams($url, ['test' => null, 'OK' => null]);
        $this->assertSame("{$_base}", $url);

        //
        $_base = $base . '/test/?';
        $url = Url::modifyParams($_base, $updateParams);
        $this->assertSame("{$_base}{$add}", $url);
        // 編集 - 追加
        $url = Url::modifyParams($url, ['test2' => 'test', 'OK2' => 'OK2']);
        $this->assertSame("{$_base}test=test&OK=OK&test2=test&OK2=OK2", $url);
    }
}

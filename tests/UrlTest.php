<?php

use FrUtility\Extended\ArrayKit;
use PHPUnit\Framework\TestCase;
use FrUtility\Other\Url;

class UrlTest extends TestCase
{
    /**
     * GETパラメータを再構築できているか確認用のデータプロパイダ
     *
     * @return array テストデータ
     */
    public function safeProvider(): array
    {
        return [
            [
                [], // 初期値として設定するパラメータ
                ['a' => 1, 'b' => 2], // 更新するパラメータ
                ['a' => 1, 'b' => 2], // 期待する結果
            ],
            [
                ['a' => 1, 'b' => 2],
                ['a' => 20],
                ['a' => 20, 'b' => 2],
            ],
            [
                ['a' => 1, 'b' => 2],
                ['a' => null, 'b' => 20, 'c' => 'OK'],
                ['b' => 20, 'c' => 'OK'],
            ],
        ];
    }

    /**
     * GETパラメータを再構築する
     * @dataProvider safeProvider
     */
    public function testUrlQuery(array $baseParams, array $updateParams, array $wantParams)
    {
        $newParams = Url::getParams($this->getUrl($baseParams), $updateParams);
        $matched = ArrayKit::are_match($newParams, $wantParams);
        $this->assertTrue($matched);
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

    /**
     * URLを取得するだけ
     */
    private function getUrl($param = [])
    {
        $base = 'https://ryo1999.com';
        return Url::modifyParams($base, $param);
    }
}

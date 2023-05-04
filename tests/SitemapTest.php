<?php

use PHPUnit\Framework\TestCase;
use FrUtility\Other\Sitemap;
use FrUtility\Other\Filer;

class SitemapTest extends TestCase
{
    /**
     *
     * Sitemapの確認
     *
     */
    public function testSitemap()
    {
        // サイトマップ.xmlを生成する
        $url = 'https://ryo1999.com';
        $filepath = '/sitemap/stmp.xml';
        //

        $root = dirname(dirname(__FILE__)) . '/public/';
        $data = [
            [
                'path' => '/',
                'date' => '1999-03-29'
            ]
        ];
        $Sitemap = new Sitemap();
        $isMake = $Sitemap->create($url, $data, $root, $filepath);

        //
        $this->assertTrue($isMake);

        // 不要ディレクトリの削除
        Filer::rm($root);
    }
}

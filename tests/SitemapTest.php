<?php

use PHPUnit\Framework\TestCase;
use FrUtility\Other\Sitemap;

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
        $siteURL = 'https://ryo1999.com';
        $rootDir = dirname(dirname(__FILE__)) . '/public/';
        $filePath = '/sitemap/stmp.xml';
        $data = [
            [
                'path' => '/dwa',
                'date' => '1999-03-29'
            ],
            [
                'path' => '/test',
                'date' => '1999-03-29'
            ]
        ];
        $Sitemap = new Sitemap($siteURL, $rootDir, $filePath);

        $isMake = $Sitemap->create($data, false);

        //
        $this->assertTrue($isMake);

        // 不要ディレクトリの削除
        $Sitemap->remove();
    }
}

<?php
namespace FrUtility\Other;

require dirname(__FILE__) . '/../../lib/sitemap-php-master/Sitemap.php';

use FrUtility\Other\Filer;
use FrUtility\Other\Request;

class Sitemap
{
    /**
     * @var array $setting サイトマップ生成設定
     */
    private $setting = [
        'siteUrl' => '', // https://ryo1999.com
        'rootDir' => '', // var/www/public
        'filePath' => '', // sitemap/stmp.xml
        'dir' => '', // sitemap/
        'filename' => '', // stmp

        //
        'fileFullPath' => '', // /var/www/public/sitemap/stmp.xml
        'fileDir' => '', // /var/www/public/sitemap/
        'fileUrl' => '', // https://ryo1999.com/sitemap/
    ];

    /**
     * @var string $sitemap_index_name stmp用のindexパス
     */
    private $sitemap_index_name = '-index.xml';

    /**
     * Sitemapクラスを初期化する
     *
     * @param string $siteUrl サイトのURL（例：https://ryo1999.com）
     * @param string $rootDir ドキュメントルートのパス（例：/var/www/public）
     * @param string $filePath サイトマップのファイルパス（例：/sitemap/stmp.xml）
     *
     */
    public function __construct(string $siteUrl, string $rootDir, string $filePath = '/sitemap/stmp.xml')
    {
        $this->setup($siteUrl, $rootDir, $filePath);
    }

    /**
     *
     * @param array $data [ ["path" => string, "date" => Date,     "priority" => '1.0', "changefreq" => "daily"] ]
     * @param bool $upload サイトマップの更新通知を送るかどうか
     * @return bool
     */
    public function create(array $data, bool $upload = true): bool
    {
        $created = $this->createSitemap($data);
        if (!$created) {
            return false;
        }

        // サイトマップの更新通知を送る
        if ($upload) {
            return $this->upload();
        }

        return true;
    }

    /**
     * サイトマップの更新通知を送る
     */
    public function upload(): bool
    {
        $uploadToGoogle = $this->uploadToGoogle($this->getSitemapIndexUrl());// https://ryo1999.com/sitemap/stmp-index.xml
        return $uploadToGoogle;
    }

    /**
     * 不要なディレクトリを削除する
     * @return bool 削除できたかどうか
     */
    public function remove(): bool
    {
        return $this->removeSitemapDirectory();
    }

    /**
     * Sitemapの設定をセットアップする
     *
     * @param string $siteUrl サイトのURL（例：https://ryo1999.com）
     * @param string $rootDir ドキュメントルートのパス（例：/var/www/public）
     * @param string $filePath サイトマップのファイルパス（例：/sitemap/stmp.xml）
     *
     */
    private function setup($siteUrl, $rootDir, $filePath): void
    {
        $this->setting['siteUrl'] = $siteUrl;
        $this->setting['rootDir'] = trim($rootDir, '/');
        $this->setting['filePath'] = trim($filePath, '/');
        $this->setting['fileFullPath'] = '/' . $this->setting['rootDir'] . '/' . $this->setting['filePath'];

        //
        if (preg_match("#^(.*/)([^/\.]+)(.+)#", $this->setting['filePath'], $matches)) {
            $this->setting = array_merge($this->setting, [
                'dir' => $matches[1] ?? '',
                'filename' => $matches[2] ?? '',
            ]);
            $this->setting['fileDir'] = '/' . $this->setting['rootDir'] . '/' . $this->setting['dir'];
            $this->setting['fileUrl'] = $siteUrl . '/' . $this->setting['dir'];
        }
    }

    // https://ryo1999.com/sitemap/stmp-index.xml
    private function getSitemapIndexUrl(): string
    {
        return $this->setting['fileUrl'] . $this->setting['filename'] . $this->sitemap_index_name;
    }

    /**
     *
     * @param array $data [ ["path" => string, "date" => "2022-01-01",     "priority" => '1.0', "changefreq" => "daily"] ]
     * @return bool
     */
    private function createSitemap(array $data): bool
    {
        // ディレクトリ作成
        Filer::mkdir($this->setting['fileDir']);

        //
        $stmp = new \Sitemap($this->setting['siteUrl']);
        $stmp->setPath($this->setting['fileDir']);// /var/www/public/sitemap/
        $stmp->setFilename($this->setting['filename']);// stmp

        // 子ディレクトリ
        foreach ($data as $site) {
            $stmp->addItem($site['path'], ($site['priority'] ?? '1.0'), ($site['changefreq'] ?? 'daily'), $site['date']);
        }

        // stmp-index.xmlを生成する
        $stmp->createSitemapIndex($this->setting['fileUrl'], 'Today');// https://ryo1999.com/sitemap/

        // 1秒以内に更新されていたら真を返す
        $maked = Filer::isFileUpdatedWithinSeconds($this->setting['fileFullPath']);
        return $maked;
    }

    /**
     *
     * Googleにサイトマップ更新通知を送る
     *
     * @param string $sitemap_url SitemapIndexUrl
     * @return bool
     *
     */
    private function uploadToGoogle(string $sitemap_url): bool
    {
        $request = "https://www.google.com/ping?sitemap=$sitemap_url";

        $options = [
            'returnJson' => false
        ];
        $result = Request::get($request, [], $options);

        // 成功確認
        $success = preg_match('/successfully/u', $result);
        return $success;
    }

    /**
     * サイトマップのディレクトリを削除する
     * @return bool 削除できたかどうか
     */
    public function removeSitemapDirectory(): bool
    {
        // ファイルディレクトリのパス
        $filePath = $this->setting['fileDir'];

        // ディレクトリの削除
        Filer::rm($filePath);

        //
        $isRemoved = !file_exists($filePath);
        return $isRemoved;
    }
}

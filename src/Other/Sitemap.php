<?php
namespace FrUtility\Other;

require dirname(__FILE__) . '/../../lib/sitemap-php-master/Sitemap.php';

class Sitemap
{
    /**
     *
     * @param string $url
     * @param array $data [ ["path" => string, "date" => Date, "priority" => '1.0', "changefreq" => "daily"] ]
     * @param string $root
     * @param string $filepath
     *
     * @return bool
     *
     */
    public function create(string $url, array $data, string $root, string $filepath = '/sitemap/stmp.xml'): bool
    {
        // 整形
        $_file = [
            'root' => trim($root, '/'), // var/www/public
            'filepath' => trim($filepath, '/'), // sitemap/stmp.xml
            'dir' => '', // sitemap/
            'filename' => '', // stmp
            'extension' => '', // .xml
            //
            'fileDir' => '', // /var/www/public/sitemap/
            'fileUrl' => '', // https://ryo1999.com/sitemap/
            'stmpUrl' => '', // https://ryo1999.com/sitemap/stmp.xml
        ];
        if (preg_match("#^(.*/)([^/\.]+)(.+)#", $_file['filepath'], $matches)) {
            $_file = array_merge($_file, [
                'dir' => $matches[1] ?? '',
                'filename' => $matches[2] ?? '',
                'extension' => $matches[3] ?? '',
            ]);
            $_file['fileDir'] = '/' . $_file['root'] . '/' . $_file['dir'];
            $_file['fileUrl'] = $url . '/' . $_file['dir'];
            $_file['stmpUrl'] = $url . '/' . $_file['filepath'];
        }

        //
        $stmp = new \Sitemap($url);

        // ディレクトリ作成
        Filer::mkdir($_file['fileDir']);

        $stmp->setPath($_file['fileDir']);// /var/www/public/sitemap/
        $stmp->setFilename($_file['filename']);// stmp

        // 子ディレクトリ
        foreach ($data as $site) {
            $stmp->addItem($site['path'], ($site['priority'] ?? '1.0'), ($site['changefreq'] ?? 'daily'), $site['date']);
        }

        // stmp-index.xmlを生成する
        $stmp->createSitemapIndex($_file['fileUrl'], 'Today');// https://ryo1999.com/sitemap/

        // Googleにアップロードする
        return $this->uploadToGoogle($_file['stmpUrl']);// https://ryo1999.com/sitemap/stmp.xml
    }

    /**
     *
     * Googleにサイトマップ更新通知を送る
     *
     * @param string $url URL
     * @return bool
     *
     */
    public function uploadToGoogle(string $url): bool
    {
        $request = "https://www.google.com/ping?sitemap=$url";

        $options = [
            'returnJson' => false
        ];
        $result = Request::get($request, [], $options);

        // 成功確認
        $success = preg_match('/successfully/u', $result);
        return $success;
    }
}

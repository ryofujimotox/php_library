<?php
namespace FrUtility\Other;

/**
 *
 * SSL状態-有効期限等の取得
 *
 * @var string $detail SSL情報
 * @var string $status live | dead | error
 * @var DateTime|null $expire_date 有効期限
 * @var int $expire_left 残り有効日数
 *
 */
class Url
{
    /**
     *
     * 適当なURLからドメインを抜き出す
     *
     * @param string $url 適当なURL( https:://〇〇.com/index.html )
     * @return string 〇〇.com
     *
     */
    public static function getDomain(string $url): string
    {
        $url_split = parse_url($url);
        $host = ($url_split['host'] ?? '');
        // $host = preg_replace('/^www\./', '', $host);
        return $host;
    }
}

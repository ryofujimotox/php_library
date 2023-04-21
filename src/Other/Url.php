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

    /**
     * 指定されたURLのクエリストリングを変更して、変更後のURLを返します。
     *
     * @param string $url 変更対象のURL
     * @param array $updateParams 変更するパラメータ。パラメータの値が`null`の場合は、URLからパラメータを削除します。
     * @return string 変更後のURL
     */
    public static function modifyParams(string $url, array $updateParams): string
    {
        // URLを構成要素に分解
        $url_parts = parse_url($url);

        // クエリストリングを再構築
        $queryParams = self::getParams($url, $updateParams);
        if ($queryParams) {
            $query_string = http_build_query($queryParams);
            $url_parts['query'] = $query_string;
        } else {
            unset($url_parts['query']);
        }

        // URLを再構成して返す
        return self::unparse($url_parts);
    }

    /**
     * URLのクエリパラメータを更新した配列を返す
     *
     * @param string $url URL文字列
     * @param array $updateParams 更新するクエリパラメータの連想配列
     * @return array 更新されたクエリパラメータの連想配列
     */
    public static function getParams(string $url, array $updateParams = []): array
    {
        // URLからクエリパラメータを抽出して連想配列に変換
        $urlParts = parse_url($url);
        parse_str($urlParts['query'] ?? '', $originalParams);

        // クエリパラメータを更新
        foreach ($updateParams as $key => $value) {
            if ($value === null) {
                unset($originalParams[$key]);
            } else {
                $originalParams[$key] = $value;
            }
        }

        // 更新されたクエリパラメータを連想配列として返す
        return $originalParams;
    }

    /**
     * 構成要素からURLを再構成して返します。
     *
     * @param array $parsed_url 構成要素の配列
     * @return string 再構成されたURL
     */
    private static function unparse(array $parsed_url): string
    {
        $scheme = isset($parsed_url['scheme']) ? ($parsed_url['scheme'] . '://') : '';
        $host = $parsed_url['host'] ?? '';
        $port = isset($parsed_url['port']) ? (':' . $parsed_url['port']) : '';
        $user = $parsed_url['user'] ?? '';
        $pass = isset($parsed_url['pass']) ? (':' . $parsed_url['pass']) : '';
        $pass = ($user || $pass) ? ($pass . '@') : '';
        $path = $parsed_url['path'] ?? '';
        $query = isset($parsed_url['query']) ? ('?' . $parsed_url['query']) : '';
        $fragment = isset($parsed_url['fragment']) ? ('#' . $parsed_url['fragment']) : '';
        return "$scheme$user$pass$host$port$path$query$fragment";
    }
}

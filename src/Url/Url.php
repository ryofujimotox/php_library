<?php
namespace FrUtility\Url;

use FrUtility\Url\Utility;

class Url
{
    private string $url;

    /**
     * @param string $url URL文字列
     */
    public function __construct(string $url)
    {
        $this->url = $url;
    }

    /**
     * @return string URL
     */
    public function getString(): string
    {
        return $this->url;
    }

    /**
     *
     * 適当なURLからドメインを抜き出す
     *
     * @return string 〇〇.com
     *
     */
    public function getDomain(): string
    {
        return Utility::getDomain($this->url);
    }

    /**
     * 指定されたURLのクエリストリングを変更して、変更後のURLを返します。
     *
     * @param array $updateParams 変更するパラメータ。パラメータの値が`null`の場合は、URLからパラメータを削除します。
     * @return string 変更後のURL
     */
    public function modifyParams(array $updateParams): string
    {
        $this->url = Utility::modifyParams($this->url, $updateParams);
        return $this->url;
    }

    /**
     * URLのクエリパラメータを更新した配列を返す
     *
     * @param array $updateParams 更新するクエリパラメータの連想配列
     * @return array 更新されたクエリパラメータの連想配列
     */
    public function getParams(array $updateParams = []): array
    {
        return Utility::getParams($this->url, $updateParams);
    }
}

<?php
namespace FrUtility\Other;

/**
 *
 * GETやPOSTでリクエストを行う
 *
 */
class Request
{
    /**
     *
     * GETしてその結果を返す
     *
     * @param string $url URL
     * @param array $params GETパラメーター　["key" => "value"]
     *
     * @return mixed json_encoded
     *
     */
    public static function get(string $url, array $params = [])
    {
        // GETパラメータ付与
        if ($params) {
            $query_string = http_build_query($params);
            $url .= "?{$query_string}";
        }

        $get_curl = curl_init();
        curl_setopt($get_curl, CURLOPT_URL, $url); // url-setting
        curl_setopt($get_curl, CURLOPT_CUSTOMREQUEST, 'GET'); // メソッド指定 Ver. GET
        // curl_setopt($get_curl, CURLOPT_HTTPHEADER, array('Content-type: application/json')); // HTTP-HeaderをSetting
        curl_setopt($get_curl, CURLOPT_SSL_VERIFYPEER, false); // サーバ証明書の検証は行わない。
        curl_setopt($get_curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($get_curl, CURLOPT_RETURNTRANSFER, true); // レスポンスを文字列で受け取る

        // 3. curl(HTTP通信)を実行する => レスポンスを変数に入れる
        $get_response = curl_exec($get_curl);

        // 4. HTTP通信の情報を得る
        $get_http_info = curl_getinfo($get_curl);

        // 5. curlの処理を終了 => コネクションを切断
        curl_close($get_curl);

        //
        $result = $get_response ? json_decode($get_response, true) : '';
        return $result;
    }
}

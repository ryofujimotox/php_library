<?php
namespace FrUtility\Other;

use FrUtility\Other\Csv;

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
     * @param array $options
     *
     * @return mixed json_encoded
     *
     */
    public static function get(string $url, array $params = [], array $options = [])
    {
        $options = array_merge(
            [
                'returnJson' => true
            ],
            $options,
        );
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

        // 返却
        $result = $get_response;
        if (!$result) {
            return $result;
        }
        if ($options['returnJson']) {
            return json_decode($result, true);
        }
        return $result;
    }

    /**
     * 指定されたURLにPOSTリクエストを送信します。
     *
     * @param string $url - リクエストを送信するURL
     * @param callable $callback - CURLハンドルに対して設定を行うコールバック関数
     * @return string - リクエストに対するレスポンス
     */
    public static function post(string $url, callable $callback): string
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);

        // 実行
        $callback($ch);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

    /**
     * 指定されたCSVデータを含むPOSTリクエストを送信します。
     *
     * @param string $url - リクエストを送信するURL
     * @param array $csvData - 送信するCSVデータ
     * @return string - リクエストに対するレスポンス
     */
    public static function postCsvByArray(string $url, array $csvData): string
    {
        //
        $csvPostFunction = function (string $tempPath) use ($url): string {
            // CURLハンドルに対して設定を行うコールバック関数
            $csvCallback = function ($ch) use ($tempPath) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, array(
                    'file' => new \CURLFile($tempPath, 'text/csv', 'data.csv'),
                ));
            };

            // POSTリクエストを送信し、レスポンスを返す
            $response = self::post($url, $csvCallback);
            return (string) $response;
        };

        $requested = Csv::executeByArray($csvData, $csvPostFunction);
        return (string) $requested;
    }
}

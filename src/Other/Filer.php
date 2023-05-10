<?php
namespace FrUtility\Other;

use \Exception;
use FrUtility\DateTime\DateTime;

class Filer
{
    /**
     *
     * 再起的にディレクトリ生成する。
     * 存在確認もする。
     * @param string $path パス
     * @param int $permissions 0777
     *
     */
    public static function mkdir(string $path, int $permissions = 0777): void
    {
        if (file_exists($path)) {
            return;
        }

        // 作成
        mkdir($path, $permissions, true);
    }

    /**
     * ファイルが指定された秒数以内に更新されたものかどうかをチェックする
     *
     * @param string $filePath ファイルへのパス
     * @param int $seconds 指定された秒数（デフォルトは1秒）
     * @param string $timezone ファイル更新日時のタイムゾーン（デフォルトは'Asia/Tokyo'）
     * @return bool 指定された秒数以内にファイルが更新されている場合はtrue、それ以外の場合はfalseを返す
     */
    public static function isFileUpdatedWithinSeconds(string $filePath, int $seconds = 1, string $timezone = 'Asia/Tokyo'): bool
    {
        // タイムゾーンを設定
        date_default_timezone_set($timezone);

        // 現在時刻のDateTimeオブジェクトを取得
        $currentTime = new DateTime();

        // ファイルの更新日時をDateTimeオブジェクトとして取得
        $lastModifiedTime = self::getLastModified($filePath, $timezone);

        // 現在時刻とファイルの更新日時の差分を秒で算出
        $differenceInSeconds = $currentTime->getTimestamp() - $lastModifiedTime->getTimestamp();

        // 差分が指定された秒数以下であればtrueを返す
        return $differenceInSeconds <= $seconds;
    }

    /**
     * ファイルの更新日時をDate型で取得する
     *
     * @param string $filePath ファイルパス
     * @param string $timezone タイムゾーン
     * @return DateTime ファイルの更新日時
     * @throws Exception ファイルが存在しない場合や更新日時を取得できない場合に例外をスロー
     */
    public static function getLastModified(string $filePath, string $timezone = 'Asia/Tokyo'): DateTime
    {
        if (!file_exists($filePath)) {
            throw new Exception('ファイルが存在しません');
        }

        $lastModified = filemtime($filePath);
        if ($lastModified === false) {
            throw new Exception('更新日時を取得できませんでした');
        }

        $dateTime = new DateTime();
        $dateTime = $dateTime->setSafeTimestamp($lastModified, $timezone);
        return $dateTime;
    }

    /**
     *
     * 再起的に削除する
     *
     * @param string $path パス
     *
     */
    public static function rm(string $path): void
    {
        if (!file_exists($path)) {
            // 存在しない対象が指定されれば、削除済みということで return
            return;
        }

        if (is_file($path)) {
            // ファイルである（ファイルシステムの階層構造の末端である）ならば削除して return
            unlink($path);
            return;
        }

        // ディレクトリであるならば内部を見て一つ一つ適切に処理
        if ($handle = opendir($path)) {
            // ディレクトリの中全てを一つ一つ読み進める
            while (false !== ($item = readdir($handle))) {
                if ($item === '.' || $item === '..') {
                    // カレントディレクトリか親ディレクトリならば何もせずにループ続行
                    continue;
                }
                // カレントディレクトリでも親ディレクトリでもないのならば再帰呼び出し
                // 再帰先ではディレクトリならばもっと掘り進めて、ファイルならば削除する
                self::rm($path . DIRECTORY_SEPARATOR . $item);
            }
            // 読み終わったらディレクトリハンドルを閉じる
            closedir($handle);
            // 既に中を全て削除済みである現参照ディレクトリを削除する
            rmdir($path);
        }
    }

    /**
     * サンプルファイルを作成する関数
     *
     * @param string $name ファイル名
     * @param string $content ファイルのコンテンツ
     * @param int|null $mtime ファイルの最終更新時刻(unixタイムスタンプ)
     * @return string 作成したファイルのパス
     */
    public static function createSampleFile(string $name = 'test.txt', string $content = 'test', ?int $mtime = null): string
    {
        $testFilePath = __DIR__ . "/{$name}";
        file_put_contents($testFilePath, $content);
        if ($mtime !== null) {
            touch($testFilePath, $mtime);
        }
        return $testFilePath;
    }
}

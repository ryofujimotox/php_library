<?php
namespace FrUtility\Other;

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
     *
     * 削除する
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
}

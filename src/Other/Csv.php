<?php
namespace FrUtility\Other;

class Csv
{
    /**
     * CSVデータを一時ファイルに保存して、指定された関数を実行する。
     * @param array $csv_data CSVデータ
     * @param callable $callback 実行する関数。引数にCSV一時ファイルのパスをとる必要がある。 (tmp_file:string) => (){}
     * @param string|null $tmp_path 一時ファイルのパス。省略された場合は自動的に作成される。
     * @return mixed 関数の実行結果
     */
    public static function executeByArray(array $csv_data, callable $callback, ?string $tmp_path = null)
    {
        // CSVデータを一時ファイルに保存する
        $csv_file = fopen('php://temp/maxmemory', 'w');
        foreach ($csv_data as $row) {
            fputcsv($csv_file, $row);
        }
        rewind($csv_file);
        $file_content = stream_get_contents($csv_file);
        fclose($csv_file);

        if (!$tmp_path) {
            // 一時ファイルのパスが省略された場合は自動的に作成する
            $tmp_path = tempnam(sys_get_temp_dir(), 'csv');
        }
        file_put_contents($tmp_path, $file_content);

        // 関数を実行する
        $result = $callback($tmp_path);

        // 一時ファイルを削除する
        unlink($tmp_path);

        return $result;
    }
}

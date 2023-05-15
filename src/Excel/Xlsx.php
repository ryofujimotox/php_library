<?php
namespace FrUtility\Excel;

use PhpOffice\PhpSpreadsheet;

class Xlsx
{
    /**
     * 指定されたExcelファイルを読み込み、全てのページのデータを配列形式で返します。
     *
     * @param string $path Excelファイルのパス
     * @param bool $isHeaderSeparate trueならheaderと内容を分ける。 [ "header" => [], "data" => [] ]
     * @return array Excelファイル内のデータ配列
     */
    public static function getArrayFromXlsx(string $path, bool $isHeaderSeparate = true): array
    {
        $spreadsheet = PhpSpreadsheet\IOFactory::load($path);

        //
        $datas = [];
        foreach ($spreadsheet->getAllSheets() as $sheet) {
            $sheet_data = self::getArrayFromSheet($sheet);
            $title = $sheet->getTitle();
            if ($isHeaderSeparate) {
                ['header' => $header,'data' => $data] = self::separateHeaderAndData($sheet_data);
            } else {
                ['header' => $header,'data' => $data] = ['header' => [], 'data' => $sheet_data];
            }
            $datas[] = compact('title', 'header', 'data');
        }
        return $datas;
    }

    /**
     * XLSXファイルを作成し、保存する。
     *
     * @example /tests/ExcelTest.php ExcelTest::testCreateXlsxFromArray(){}
     *
     * @param array $array testsを参照 ExcelTest::testCreateXlsxFromArray(){}
     * @param string $filePath 保存するファイル名 nullの場合はtmp保存
     * @param array $options オプション
     * @return string|null 作成されたXLSXファイルのパス。
     */
    public static function createXlsxFromArray(array $array, string $filePath = null, array $options = []): ?string
    {
        $options = array_merge([
            // 既に存在するファイルを編集する場合
            'load_file' => null,
            // loadしたシートを後ろに足すか、前に足すか
            'load_position' => 'end',
        ], $options);

        extract($options);

        // ロードするか新規作成する。
        $spreadsheet = self::createSpreadsheet($load_file);

        // 1シートごとにデータを挿入する
        foreach ($array as $pageNum => $data) {
            // シート指定
            if ($pageNum) {
                $sheet = $spreadsheet->createSheet($pageNum);
            } else {
                if ($load_file) {
                    $sheet = $spreadsheet->createSheet(0);
                } else {
                    $sheet = $spreadsheet->getActiveSheet();
                }
            }

            // シート名を設定する
            if ($title = ($data['title'] ?? '')) {
                $sheet->setTitle($title);
            }

            // 一行ずつ挿入する
            $sheet_data = self::mergeHeaderAndDataToArray($data);// ヘッダー行とそれ以外の行を、一つの連想配列に結合する。
            $sheet->fromArray($sheet_data, null, 'A1');// fromArray(セットする配列, セットしない値, 開始場所のセル)
        }

        // 1ページ目へ
        $spreadsheet->setActiveSheetIndex(0);

        // ファイル保存パス取得
        if (!$filePath) {
            $filePath = tempnam(sys_get_temp_dir(), 'tmp');
        }

        // XLSXファイルとして保存
        $writer = PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save($filePath);

        return $filePath;
    }

    /**
     *
     * 指定pathのエクセルが存在する場合、それを返す。
     * 存在しない場合、新規作成する。
     *
     * @param ?string path エクセルへのパス
     * @return
     *
     */
    private static function createSpreadsheet(?string $path): PhpSpreadsheet\Spreadsheet
    {
        if ($path) {
            $spreadsheet = PhpSpreadsheet\IOFactory::load($path);
        } else {
            $spreadsheet = new PhpSpreadsheet\Spreadsheet();
        }
        return $spreadsheet;
    }

    /**
     * ワークシートから配列データを取得する関数。
     *
     * @param PhpSpreadsheet\Worksheet\Worksheet $sheet 対象のワークシートオブジェクト
     * @return array 取得した配列データ
     */
    private static function getArrayFromSheet(PhpSpreadsheet\Worksheet\Worksheet $sheet): array
    {
        // ワークシート内の最大領域座標（"A1:XXXnnn" XXX:最大カラム文字列, nnn:最大行）
        $range = $sheet->calculateWorksheetDimension();

        // ワークシート内の全てのデータを取得（配列データとして）
        $data = $sheet->rangeToArray($range);
        return $data;
    }

    /**
     * ヘッダー行とそれ以外の行を、一つの連想配列に結合する。
     *
     * @param array $dictionary ヘッダー行とデータ行を含む配列 [ "header" => [1,2,3], "data" => [a,i,u] ]
     * @return array 配列データ [ [1,2,3], [a,i,u] ]
     */
    private static function mergeHeaderAndDataToArray(array $dictionary): array
    {
        // 格納するシート用のデータ
        $sheetData = [];

        // ヘッダー行の取得; ヘッダーがなければ先頭行から生成する。
        $headerRow = $dictionary['header'] ?? [] ?: array_keys($dictionary['data'][0] ?? []);
        if (!empty($headerRow)) {
            $sheetData[] = $headerRow;
        }

        // 列データの処理
        $dataColumns = $dictionary['data'] ?? [];
        foreach ($dataColumns as $column) {
            // 列データを値のみの配列に変換して格納
            $sheetData[] = array_values($column);
        }

        // 抽出されたシートデータを返す
        return $sheetData;
    }

    /**
     * 配列データから、ヘッダー行とそれ以外の行に分ける
     *
     * @param array $data 配列データ [ [1,2,3], [a,i,u] ]
     * @return array ヘッダー行とデータ行を含む配列 [ "header" => [1,2,3], "data" => [a,i,u] ]
     */
    private static function separateHeaderAndData(array $data): array
    {
        // 配列の長さが1未満の場合、空の配列を返す
        if (count($data) < 1) {
            return [];
        }

        // 配列の0番目をヘッダー行、それ以外をデータ行として分ける
        $header = array_shift($data);
        return ['header' => $header, 'data' => $data];
    }
}

<?php

use PHPUnit\Framework\TestCase;
use FrUtility\Excel\Xlsx;

class XlsxTest extends TestCase
{
    /**
     * 配列からエクセルが正しく変換されているかの確認
     *
     * @dataProvider successProvider
     */
    public function testCreateXlsxFromArray($array)
    {
        // 配列 to エクセル
        $path = __DIR__ . '/../test.xlsx';
        Xlsx::createXlsxFromArray($array, $path);

        // エクセル to 配列
        $excel_data = Xlsx::getArrayFromXlsx($path);

        // 確認処理
        $page_index = 0;// 1ページ目
        $this->assertEquals($array[$page_index]['header'], $excel_data[$page_index]['header']);// 1ページ目の1行目(header)
        $this->assertEquals(array_values($array[$page_index]['data']), $excel_data[$page_index]['data']);// 1ページ目の全データ

        $page_index = 1;// 2ページ目
        $this->assertEquals(array_keys($array[$page_index]['data'][0]), $excel_data[$page_index]['header']);// 2ページ目の1行目(header)
        $array_datas = array_map(function ($_data) {
            return array_values($_data);
        }, $array[$page_index]['data']);
        $this->assertEquals($array_datas, $excel_data[$page_index]['data']);// 2ページ目の全データ

        // 削除処理
        unlink($path);
    }

    /**
     * 配列からエクセルが正しく変換されているかの確認( tmp )
     *
     * @dataProvider successProvider
     */
    public function testCreateXlsxFromArrayTmp($array)
    {
        // 配列 to エクセル
        $path = Xlsx::createXlsxFromArray($array);

        // エクセル to 配列
        $excel_data = Xlsx::getArrayFromXlsx($path, false);

        // 確認処理
        $page_index = 0;// 1ページ目
        $this->assertEquals($array[$page_index]['title'], $excel_data[$page_index]['title']);// 1ページ目のタイトル

        // 削除処理
        unlink($path);
    }

    /**
     * 有効な引数を用いた testCreateXlsxFromArray() のパラメータプロバイダ
     */
    public function successProvider()
    {
        $sample1 = [
            [
                'title' => '1ページ目',
                'header' => ['1ページ目の値1', '1ページ目の値2'],
                'data' => [
                    [
                        '値1-1', '値1-2'
                    ],
                    [
                        '値2-1', '値2-2'
                    ],
                ]
            ],
            [
                'title' => '2ページ目',
                // 'header' => ['1ページ目の値', 'OK'],
                'data' => [
                    [
                        'カラム1' => '2値1-1',
                        'カラム2' => '2値1-2'
                    ],
                    [
                        'カラム1' => '2値2-1',
                        'カラム2' => '2値2-2'
                    ],
                ]
            ]
        ];

        return [
            [$sample1],
        ];
    }
}

<?php

use PHPUnit\Framework\TestCase;
use FrUtility\Other\Filer;

class IsFileUpdatedWithinSecondsTest extends TestCase
{
    /**
     * 正常系データプロバイダ
     * 5秒前に作成されたファイルに対して、一定時間以内に更新されたかどうかを判定します。
     * @return array テスト用のパラメータの配列
     */
    public function provideValidTestData(): array
    {
        return [
            [10, true], // 10秒以内に更新された場合、true
            [1, false], // 1秒以内に更新されていない場合、false
            [5, true], // 同じ秒に更新された場合、true
        ];
    }

    /**
     * テストを実行する関数
     * @param int $timeThreshold 何秒以内に更新されたかを判定するための時間の閾値
     * @param bool $expectedUpdateResult 期待される更新結果（true: 更新された、false: 更新されていない）
     * @dataProvider provideValidTestData
     */
    public function testIsFileUpdatedWithinSeconds正常系(int $timeThreshold, bool $expectedUpdateResult): void
    {
        // テスト用のファイルを作成し、最終更新日時を現在時刻から5秒前に設定する
        $testFilePath = Filer::createSampleFile('test.txt', '', time() - 5);

        // テスト対象の関数を呼び出し、結果を確認する
        $actualUpdateResult = Filer::isFileUpdatedWithinSeconds($testFilePath, $timeThreshold);
        $this->assertEquals($actualUpdateResult, $expectedUpdateResult);

        // テスト用のファイルを削除する
        unlink($testFilePath);
    }

    /**
     * ファイルが存在しない場合、例外が発生することを確認するテスト
     */
    public function testIsFileUpdatedWithinSecondsエラー系FileNotFound(): void
    {
        // 存在しないファイルパスを指定して、例外が発生することを確認する
        $this->expectException(\Exception::class);
        Filer::isFileUpdatedWithinSeconds('/path/to/invalid/file.txt');
    }
}

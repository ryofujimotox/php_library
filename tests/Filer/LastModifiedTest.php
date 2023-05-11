<?php
use PHPUnit\Framework\TestCase;
use FrUtility\Other\Filer;

class LastModifiedTest extends TestCase
{
    /**
     * @dataProvider TestProviders\TimestampProvider::safeProvider
     */
    public function testGetLastModified正常系(int $timestamp, string $expectedDatetime, string $timezone): void
    {
        // テスト用のファイルを作成し、最終更新日時を指定されたタイムスタンプに設定する
        $testFilePath = Filer::createSampleFile('test.txt', '', $timestamp);

        // テスト対象の関数を呼び出し、結果を確認する
        $modified = Filer::getLastModified($testFilePath, $timezone);
        $this->assertEquals($modified->format('Y-m-d H:i:s'), $expectedDatetime);

        // テスト用のファイルを削除する
        unlink($testFilePath);
    }

    /**
     * ファイルが存在しない場合、例外が発生することを確認するテスト
     */
    public function testGetLastModifiedエラー系FileNotFound(): void
    {
        // 存在しないファイルパスを指定して、例外が発生することを確認する
        $this->expectException(\Exception::class);
        Filer::getLastModified('/path/to/invalid/file.txt');
    }
}

<?php

use PHPUnit\Framework\TestCase;
use FrUtility\Other\Filer;

class FilerTest extends TestCase
{
    /**
     * ファイルの最終更新日時が現在時刻から1秒以内の場合、正しくtrueを返すことを確認するテスト
     */
    public function testIsFileUpdatedWithinSecondsReturnsTrueIfLastModifiedWithinOneSecond(): void
    {
        // テスト用のファイルを作成し、最終更新日時を現在時刻から1秒前に設定する
        $testFilePath = Filer::createSampleFile('test.txt', '', time() - 5);

        // テスト対象の関数を呼び出し、結果を確認する
        $result = Filer::isFileUpdatedWithinSeconds($testFilePath, 10);
        $this->assertTrue($result);

        // テスト用のファイルを削除する
        unlink($testFilePath);
    }

    /**
     * ファイルの最終更新日時が現在時刻から2秒以上前の場合、正しくfalseを返すことを確認するテスト
     */
    public function testIsFileUpdatedWithinSecondsReturnsFalseIfLastModifiedBeforeTwoSeconds(): void
    {
        // テスト用のファイルを作成し、最終更新日時を現在時刻から1秒前に設定する
        $testFilePath = Filer::createSampleFile('test.txt', '', time() - 5);

        // テスト対象の関数を呼び出し、結果を確認する
        $result = Filer::isFileUpdatedWithinSeconds($testFilePath, 1);
        $this->assertFalse($result);

        // テスト用のファイルを削除する
        unlink($testFilePath);
    }

    /**
     * ファイルが存在しない場合、例外が発生することを確認するテスト
     */
    public function testIsFileUpdatedWithinSecondsThrowsExceptionIfFileNotFound(): void
    {
        // 存在しないファイルパスを指定して、例外が発生することを確認する
        $this->expectException(\Exception::class);
        Filer::isFileUpdatedWithinSeconds('/path/to/invalid/file.txt');
    }
}

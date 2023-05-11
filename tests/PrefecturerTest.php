<?php

use PHPUnit\Framework\TestCase;
use FrUtility\Table\Prefecturer;
use FrUtility\Extended\ArrayKit;

class PrefecturerTest extends TestCase
{
    /**
     * 並び替えた後の全リストから、最初と最後を確認するためのデータプロバイダ
     *
     * @return array テストデータ
     */
    public function safityOrderProvider(): array
    {
        return [
            [['tokyo', 'aomori'], ['東京都', '青森県', '北海道'], ['沖縄県']],
            [['tokyo', 'okinawa', 'aomori'],  ['東京都', '沖縄県', '青森県', '北海道'],  ['鹿児島県']]
        ];
    }

    /**
     * 絞り込みが正しく動作していることを確認するためのデータプロバイダ
     *
     * @return array テストデータ
     */
    public function safityNeedleProvider(): array
    {
        return [
            [['tokyo'], ['東京都']],
            [['niigata', 'tokyo'], ['東京都', '新潟県']],
            [['tochigi', 'kagoshima', 'yamaguchi', 'ibaraki'], ['茨城県', '栃木県', '山口県', '鹿児島県']]
        ];
    }

    /**
     *
     *
     * 地方でグループした都道府県リストが、正しく取得できていること
     *
     *
     */
    public function testGetGroupList()
    {
        $Prefecturer = new Prefecturer();

        //
        $needle = ['aomori', 'tokyo', 'miyagi', 'okinawa', 'hiroshima', 'okayama'];
        $Prefecturer->where($needle)->sort($needle)->groups('特別', ['aomori', 'miyagi'], '特別2', ['tokyo']);
        $group_list = $Prefecturer->getGroupList();

        // この配列通りに取得できていること
        $want = [
            [
                'name' => '特別',
                'prefs' => ['青森県', '宮城県']
            ],
            [
                'name' => '特別2',
                'prefs' => ['東京都']
            ],
            [
                'name' => '九州',
                'prefs' => ['沖縄県']
            ],
            [
                'name' => '中国・四国',
                'prefs' => ['広島県', '岡山県']
            ],
        ];
        foreach ($want as $_index => $_want) {
            $current_prefs = array_column($group_list[$_index]['prefs'], 'pref_jp');
            $this->assertTrue($group_list[$_index]['name'] == $_want['name'], '正しいグループ名ではありません: ' . $_want['name']);
            $this->assertSame($current_prefs, $_want['prefs'], 'グループメンバーが正しくありません: ' . $_want['name']);
        }
    }

    /**
     * 並び順
     * @dataProvider safityOrderProvider
     */
    public function testGetListOrder($order, $currentFirst, $currentLast)
    {
        // 東京 -> 青森 -> 北海道 ,,,の順に並べる。　
        $want = [$currentFirst, $currentLast];
        $list = $this->getListJP($order);

        // 最初と最後を確認する。
        $first_end = ArrayKit::slice_firla($list, count($want[0]), count($want[1]));
        $want_first_end = ArrayKit::flatten($want);
        $this->assertSame($first_end, $want_first_end);
    }

    /**
     * 絞り込み
     * @dataProvider safityNeedleProvider
     */
    public function testGetListNeedle($needle, $want)
    {
        $list = $this->getListJP([], $needle);
        $this->assertSame($list, ArrayKit::flatten($want));
    }

    /**
     *
     * 並び替え + 絞り込み
     *
     */
    public function testGetList()
    {
        // 青森 -> 東京 -> 北海道 の順番で +宮城も取得する
        $order = ['aomori', 'tokyo', 'hokkaido'];
        $needle = ['aomori', 'miyagi', 'tokyo', 'hokkaido'];
        $want = ['青森県', '東京都', '北海道', '宮城県'];
        $list = $this->getListJP($order, $needle);
        $this->assertSame($list, ArrayKit::flatten($want));
    }

    /**
     *
     * リスト取得
     *
     * @param array $order 指定した都道府県の順番で取得する 例: [ "tokyo", "aomori", "okinawa" ]
     * @param array $needle 指定した都道府県のみを取得する 例: [ "okinawa", "tokyo" ]
     * @param string $key キー
     *
     * @return array 日本語の都道府県リスト 例: [ "東京", "沖縄" ]
     *
     */
    public function getListJP(array $order = [], array $needle = [], string $key = 'pref_jp'): array
    {
        $Prefecturer = new Prefecturer();

        if ($order) {
            $Prefecturer->sort($order);
        }

        if ($needle) {
            $Prefecturer->where($needle);
        }

        $list = $Prefecturer->getList();

        // 指定のキーのみで再構築
        $list_jp = array_column($list, $key);
        return $list_jp;
    }
}

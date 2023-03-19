<?php
namespace FrUtility\Table;

use FrUtility\Extended\DictionaryKit;

/**
 * Class Prefecturer
 * @package Prefecturer
 */
class Prefecturer {
    public $list;

    /**
     *
     * 初期設定
     *
     */
    public function __construct() {
        // 扱いやすいリストにする
        $list = $this->getFlatList();

        //
        $this->list = $list;
    }

    /**
     *
     * 都道府県リストを取得する
     *
     * @return array 内容はgetFlatList [  [ pref_en => "aomori", pref_jp => "青森", area_en => "tohoku", area_jp => "東北" ]  ]
     *
     */
    public function getList(): array {
        return $this->list;
    }

    /**
     *
     * 平坦化なリストを、地方ごとにまとめる。
     * 事前にgroupしていた場合、地方とは別に分けられる。
     *
     */
    public function getGroupList() {
        $list = $this->list;

        //
        $result = [];
        foreach ($list as $prefdata) {
            $key = 'area_jp';

            $group_name = $prefdata['group_name'] ?? '';
            if ($group_name) {
                $key = 'group_name';
            }

            $area = $result[$prefdata[$key]] ?? [];
            if (!$area) {
                $area = [
                    'name' => $group_name ? $group_name : $prefdata['area_jp'] ?? '',
                    'group_name' => $group_name,
                    'area_jp' => $prefdata['area_jp'] ?? '',
                    'area_en' => $prefdata['area_en'] ?? '',
                    'prefs' => []
                ];
            }
            $area['prefs'][] = $prefdata;

            //
            $result[$prefdata[$key]] = $area;
        }

        //
        $result = array_values($result);
        return $result;
    }

    /**
     *
     * 絞り込む
     *
     * @param array $needle_prefectures 必要な値で絞り込む [ "aomori", "tokyo" ]
     * @param string $key = どこの値で絞り込むか(default: pref_en)
     *
     * @return object $this ->sort()
     */
    public function where(array $needle_prefectures, string $key = 'pref_en'): object {
        // 指定した値をもつ連想配列のみで、構成する
        $this->list = array_filter($this->list, function ($data) use ($needle_prefectures, $key) {
            return in_array($data[$key] ?? '', $needle_prefectures);
        });
        return $this;
    }

    /**
     *
     * 指定した値の順番で、都道府県リストを並び替える
     *
     * @param array $value_list  例: [ "tokyo", "aomori" ] => [ "tokyo", "aomori", "hokkaido",,,, ]
     * @param string $key  例: pref_en
     *
     * @return object $this ->where()
     *
     */
    public function sort(array $value_list, string $key = 'pref_en'): object {
        $this->list = DictionaryKit::sort_by_values($this->list, $key, $value_list);
        return $this;
    }

    /**
     *
     * getGroupList時に分けるために、グループを設定する
     *
     * @param string $name グループ名
     * @param array $prefs グループに加える都道府県リスト
     * @param mixed ...$continue $nameと$prefsの繰り返し
     *
     * @return object $this ->getGroupList
     *
     */
    public function groups($name, $prefs, ...$continue): object {
        $params = array_merge(
            [$name, $prefs],
            $continue
        );
        $params = array_chunk($params, 2);

        foreach ($params as $param) {
            @list($name, $prefs) = $param;
            if (!$name || !$prefs) {
                throw new \Exception("グループ({$name})に含める都道府県が指定されておりません");
            }
            $this->group($name, $prefs);
        }

        return $this;
    }

    /**
     *
     * getGroupList時に分けるために、グループを設定する
     *
     * @param string $name グループ名
     * @param array $prefs グループに加える都道府県リスト
     *
     * @return object $this ->getGroupList
     *
     */
    public function group(string $group_name, array $needle_prefs): object {
        $this->list = array_map(function ($data) use ($group_name, $needle_prefs) {
            $_pref = $data['pref_en'] ?? '';
            if (!in_array($_pref, $needle_prefs)) {
                return $data;
            }
            $data['group_name'] = $group_name;
            return $data;
        }, $this->list);
        return $this;
    }

    /**
     *
     * readable_listを平坦化し扱いやすくする。
     * 見にくいが扱いやすい
     *
     */
    private function getFlatList(): array {
        $result = [];
        foreach ($this->readable_list as $area) {
            foreach ($area['prefs'] ?? [] as $pref) {
                $result[] = [
                    'pref_en' => $pref['en'],
                    'pref_jp' => $pref['jp'],
                    'area_en' => $area['area_en'],
                    'area_jp' => $area['area_jp'],
                ];
            }
        }
        return $result;
    }

    /**
     *
     * 都道府県リスト
     *
     */
    private $readable_list = [
        [
            'area_en' => 'hokkaido',
            'area_jp' => '北海道',
            'prefs' => [
                [
                    'en' => 'hokkaido',
                    'jp' => '北海道',
                ],
            ]
        ],
        [
            'area_en' => 'tohoku',
            'area_jp' => '東北',
            'prefs' => [
                [
                    'en' => 'aomori',
                    'jp' => '青森県',
                ],
                [
                    'en' => 'iwate',
                    'jp' => '岩手県',
                ],
                [
                    'en' => 'akita',
                    'jp' => '秋田県',
                ],
                [
                    'en' => 'miyagi',
                    'jp' => '宮城県',
                ],
                [
                    'en' => 'yamagata',
                    'jp' => '山形県',
                ],
                [
                    'en' => 'fukushima',
                    'jp' => '福島県',
                ],
            ]
        ],
        [
            'area_en' => 'kanto',
            'area_jp' => '関東',
            'prefs' => [
                [
                    'en' => 'ibaraki',
                    'jp' => '茨城県',
                ],
                [
                    'en' => 'tochigi',
                    'jp' => '栃木県',
                ],
                [
                    'en' => 'gunma',
                    'jp' => '群馬県',
                ],
                [
                    'en' => 'saitama',
                    'jp' => '埼玉県',
                ],
                [
                    'en' => 'chiba',
                    'jp' => '千葉県',
                ],
                [
                    'en' => 'tokyo',
                    'jp' => '東京都',
                ],
                [
                    'en' => 'kanagawa',
                    'jp' => '神奈川県',
                ],
            ]
        ],
        [
            'area_en' => 'chubu',
            'area_jp' => '中部',
            'prefs' => [
                [
                    'en' => 'yamanashi',
                    'jp' => '山梨県',
                ],
                [
                    'en' => 'nagano',
                    'jp' => '長野県',
                ],
                [
                    'en' => 'niigata',
                    'jp' => '新潟県',
                ],
                [
                    'en' => 'toyama',
                    'jp' => '富山県',
                ],
                [
                    'en' => 'ishikawa',
                    'jp' => '石川県',
                ],
                [
                    'en' => 'fukui',
                    'jp' => '福井県',
                ],
                [
                    'en' => 'shizuoka',
                    'jp' => '静岡県',
                ],
                [
                    'en' => 'aichi',
                    'jp' => '愛知県',
                ],
                [
                    'en' => 'gifu',
                    'jp' => '岐阜県',
                ],
            ]
        ],
        [
            'area_en' => 'kinki',
            'area_jp' => '近畿',
            'prefs' => [
                [
                    'en' => 'mie',
                    'jp' => '三重県',
                ],
                [
                    'en' => 'shiga',
                    'jp' => '滋賀県',
                ],
                [
                    'en' => 'kyoto',
                    'jp' => '京都府',
                ],
                [
                    'en' => 'oosaka',
                    'jp' => '大阪府',
                ],
                [
                    'en' => 'hyogo',
                    'jp' => '兵庫県',
                ],
                [
                    'en' => 'nara',
                    'jp' => '奈良県',
                ],
                [
                    'en' => 'wakayama',
                    'jp' => '和歌山県',
                ],
            ]
        ],
        [
            'area_en' => 'chugoku_shikoku',
            'area_jp' => '中国・四国',
            'prefs' => [
                [
                    'en' => 'tottori',
                    'jp' => '鳥取県',
                ],
                [
                    'en' => 'shimane',
                    'jp' => '島根県',
                ],
                [
                    'en' => 'okayama',
                    'jp' => '岡山県',
                ],
                [
                    'en' => 'hiroshima',
                    'jp' => '広島県',
                ],
                [
                    'en' => 'yamaguchi',
                    'jp' => '山口県',
                ],
                [
                    'en' => 'kagawa',
                    'jp' => '香川県',
                ],
                [
                    'en' => 'ehime',
                    'jp' => '愛媛県',
                ],
                [
                    'en' => 'tokushima',
                    'jp' => '徳島県',
                ],
                [
                    'en' => 'kochi',
                    'jp' => '高知県',
                ],
            ]
        ],
        [
            'area_en' => 'kyusyu',
            'area_jp' => '九州',
            'prefs' => [
                [
                    'en' => 'fukuoka',
                    'jp' => '福岡県',
                ],
                [
                    'en' => 'saga',
                    'jp' => '佐賀県',
                ],
                [
                    'en' => 'nagasaki',
                    'jp' => '長崎県',
                ],
                [
                    'en' => 'kumamoto',
                    'jp' => '熊本県',
                ],
                [
                    'en' => 'ooita',
                    'jp' => '大分県',
                ],
                [
                    'en' => 'miyazaki',
                    'jp' => '宮崎県',
                ],
                [
                    'en' => 'kagoshima',
                    'jp' => '鹿児島県',
                ],
                [
                    'en' => 'okinawa',
                    'jp' => '沖縄県',
                ],
            ]
        ],
    ];
}

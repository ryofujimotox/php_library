<?php
namespace FrUtility\DateTime;

use FrUtility\Extended\TextKit;
use \DateTime as BaseDateTime;

class Wareki
{
    /**
     * 元号を考慮した日付フォーマットを行う
     *
     * @param string $format フォーマット指定文字列
     * @param \DateTimeInterface $datetime 対象の日付と時刻を表す DateTime オブジェクト
     * @return string フォーマットされた文字列
     */
    public static function formatWareki(string $format, \DateTimeInterface $datetime): string
    {
        // 日付に対応する元号情報を取得
        $era = self::getEraFromDatetime($datetime);

        // デフォルトのパラメータを設定
        $params = [
            'K' => '', // 元号名(漢字)
            'k' => '', // 元号名(略字)
            'Q' => '', // 英語表記の元号名
            'q' => '', // 英語略表記の元号名
            'x' => '0', // 元号における年数
            'X' => '00', // 元号における年数(2桁0詰め)
        ];

        if ($era) {
            // 元号がある場合はパラメータを更新
            $year_of_era = $datetime->format('Y') - $era['start_date']->format('Y') + 1;
            $params = [
                'K' => $era['jp'], // 元号名(漢字)
                'k' => $era['jp_abbr'], // 元号名(略字)
                'Q' => $era['en'], // 英語表記の元号名
                'q' => $era['en_abbr'], // 英語略表記の元号名
                'x' => $year_of_era, // 元号における年数
                'X' => sprintf('%02d', $year_of_era), // 元号における年数(2桁0詰め)
            ];
        }

        // フォーマットパラメータを適用して文字列を取得
        $formatted_string = TextKit::formatText($format, $params, false);
        return $formatted_string;
    }

    /**
     * 元号を判定して返す
     * @param \DateTimeInterface $datetime 対象の日付と時刻を表す DateTime オブジェクト
     * @return array|null 元号の情報。該当する元号がない場合はnullを返す
     */
    public static function getEraFromDatetime(\DateTimeInterface $datetime): ?array
    {
        // 元号一覧
        $era_list = [
            // 令和(2019年5月1日〜)
            [
                'jp' => '令和', 'jp_abbr' => '令',
                'en' => 'Reiwa', 'en_abbr' => 'R',
                'time' => '20190501'
            ],
            // 平成(1989年1月8日〜)
            [
                'jp' => '平成', 'jp_abbr' => '平',
                'en' => 'Heisei', 'en_abbr' => 'H',
                'time' => '19890108'
            ],
            // 昭和(1926年12月25日〜)
            [
                'jp' => '昭和', 'jp_abbr' => '昭',
                'en' => 'Showa', 'en_abbr' => 'S',
                'time' => '19261225'
            ],
            // 大正(1912年7月30日〜)
            [
                'jp' => '大正', 'jp_abbr' => '大',
                'en' => 'Taisho', 'en_abbr' => 'T',
                'time' => '19120730'
            ],
            // 明治(1873年1月1日〜)
            // ※明治5年以前は旧暦を使用していたため、明治6年以降から対応
            [
                'jp' => '明治', 'jp_abbr' => '明',
                'en' => 'Meiji', 'en_abbr' => 'M',
                'time' => '18730101'
            ],
        ];

        // 元号を判定する
        foreach ($era_list as $era) {
            $era_start_date = new BaseDateTime($era['time']);

            if ($datetime->format('Ymd') >= $era_start_date->format('Ymd')) {
                return array_merge($era, ['start_date' => $era_start_date]);
            }
        }

        // 該当する元号がない場合はnullを返す
        return null;
    }
}

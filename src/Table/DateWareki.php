<?php
namespace FrUtility\Table;

use DateTime;
use DateTimeInterface;
use Exception;
use FrUtility\Extended\DateTimeKit;

/**
 *
 * $Wareki = new DateWareki();
 * $date = $Wareki->format('KX年 - Y年');
 * echo $date;// 令和3年 - 2023年
 *
 */
class DateWareki
{
    /**@var DateTime|null $datetime 変換する日時 (デフォルトは現在時刻)*/
    public ?DateTime $datetime;

    /**@var array|null $era 元号情報*/
    private ?array $era;

    /**
     * DateWareki コンストラクタ
     *
     * @param DateTime|null $datetime 日時オブジェクト
     * @throws Exception 元号が存在しない場合
     */
    public function __construct(?DateTime $datetime = null)
    {
        // DateTimeオブジェクトを作成
        if (!$datetime) {
            $datetime = new DateTime();
        }

        // いつの元号か取得
        $era = $this->getEraFromDate($datetime);
        if (!$era) {
            throw new Exception('元号が存在しません');
        }

        // set
        $this->datetime = $datetime;
        $this->era = $era;
    }

    /**
     * 元号を取得する。
     *
     * @return array|null 元号名。元号が設定されていない場合は null を返す。
     */
    public function getEra(): ?array
    {
        return $this->era;
    }

    /**
     * 元号の日付表記に変更する
     *
     * @param string $format 日付フォーマット文字列
     * @return string 変換後の日付文字列
     */
    public function format(string $format): string
    {
        // フォーマットパラメータを作成
        $year_of_era = $this->datetime->format('Y') - $this->era['start_date']->format('Y') + 1;
        $params = [
            'K' => $this->era['jp'], // 元号名(漢字)
            'k' => $this->era['jp_abbr'], // 元号名(略字)
            'Q' => $this->era['en'], // 英語表記の元号名
            'q' => $this->era['en_abbr'], // 英語略表記の元号名
            'x' => $year_of_era, // 元号における年数
            'X' => sprintf('%02d', $year_of_era), // 元号における年数(2桁0詰め)
        ];

        // 指定したフォーマットで日付文字列を取得
        $result = DateTimeKit::formatDateWithText($this->datetime, $format, $params);

        return $result;
    }

    /**
     * 元号を判定して返す
     *
     * @param DateTimeInterface $date 判定する日付
     * @return array|null 元号の情報。該当する元号がない場合はnullを返す
     */
    private function getEraFromDate(DateTimeInterface $date): ?array
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

        // 日付を整形する
        $date = $date->format('Ymd');

        // 元号を判定する
        foreach ($era_list as $era) {
            $era_start_date = new DateTime($era['time']);
            if ($date >= $era_start_date->format('Ymd')) {
                return array_merge($era, ['start_date' => $era_start_date]);
            }
        }

        // 該当する元号がない場合はnullを返す
        return null;
    }
}

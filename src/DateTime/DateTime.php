<?php
namespace FrUtility\DateTime;

use \DateTime as BaseDateTime;
use FrUtility\DateTime\Wareki;
use FrUtility\DateTime\Youbi;

class DateTime extends BaseDateTime
{
    /**
     * 日付フォーマット変換メソッド.
     *
     * 【使用書式】
     * 元号と曜日のために、フォーマット予約語を変えている。
     * [
     *      "W", // 曜日(月 ~ 日)
     *      'K', // 元号名(漢字)
     *      'k', // 元号名(略字)
     *      'Q', // 英語表記の元号名
     *      'q', // 英語略表記の元号名
     *      'x', // 元号における年数
     *      'X', // 元号における年数(2桁0詰め)
     * ]
     *
     * @param string $format 日付フォーマット文字列
     *
     * @return string 変換後の日付文字列
     */
    public function format($format): string
    {
        $clone = $this->cloneBaseDateTime();
        $format = Wareki::format($format, $clone);
        $format = Youbi::format($format, $clone);
        return parent::format($format);
    }

    /**
     * 元号を取得する。
     *
     * @return array|null 元号名。元号が設定されていない場合は null を返す。
     */
    public function getEra(): ?array
    {
        return Wareki::getEra($this->cloneBaseDateTime());
    }

    /**
     * 現在の BaseDateTime オブジェクトのコピーを作成する
     *
     * @return BaseDateTime
     */
    public function cloneBaseDateTime(): BaseDateTime
    {
        // 親クラスのフォーマットから新しい BaseDateTime オブジェクトを作成
        $cloned = new BaseDateTime(parent::format('Y-m-d H:i:s'));
        return $cloned;
    }

    /**
     * その日の00時を返す。
     *
     * @return string 日時
     */
    public function getStartDateTime(): string
    {
        return $this->format('Y-m-d 00:00:00');
    }

    /**
     * その月の初日を取得する。
     *
     * @return DateTime その月の初日
     */
    public function getMonthFirstDate(): DateTime
    {
        $date = $this->format('Y-m-d H:i:s');
        return new DateTime(date('Y-m-d', strtotime('first day of ' . $date)));
    }

    /**
     * その月の末日を取得する。
     *
     * @return DateTime その月の末日
     */
    public function getMonthLastDate(): DateTime
    {
        $date = $this->format('Y-m-d H:i:s');
        return new DateTime(date('Y-m-d', strtotime('last day of ' . $date)));
    }

    /**
     * 次の日を取得する。
     *
     * @return DateTime 次の日
     */
    public function getNextDate(): DateTime
    {
        $clone = clone $this;
        $clone->modify('+1 days');
        return $clone;
    }
}

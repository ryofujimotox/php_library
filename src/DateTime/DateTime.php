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
        $format = Wareki::formatWareki($format, $clone);
        $format = Youbi::formatYoubi($format, $clone);
        return parent::format($format);
    }

    /**
     * 元号を取得する。
     *
     * @return array|null 元号名。元号が設定されていない場合は null を返す。
     */
    public function getEra(): ?array
    {
        return Wareki::getEraFromDatetime($this->cloneBaseDateTime());
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
}

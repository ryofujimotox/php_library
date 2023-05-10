<?php
namespace FrUtility\DateTime;

use \DateTime as BaseDateTime;
use \DateTimeZone;
use \Exception;
use FrUtility\DateTime\Wareki;

class DateTime extends BaseDateTime
{
    /**
     * 元号の日付表記に変更する
     *
     * @param string $format 日付フォーマット文字列
     * @return string 変換後の日付文字列
     */
    public function format($format): string
    {
        $format = Wareki::formatWareki($format, $this->cloneBaseDateTime());
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
    * タイムスタンプとタイムゾーンから DateTime オブジェクトを生成する
    *
    * @param int $timestamp Unix タイムスタンプ
    * @param string $timezone タイムゾーン識別子 (デフォルトは Asia/Tokyo)
    */
    public function setSafeTimestamp(int $timestamp, string $timezone = 'Asia/Tokyo')
    {
        try {
            // タイムスタンプを設定する
            $this->setTimestamp($timestamp);
        } catch(\Throwable $ex) {
            throw new Exception('timestampが不正です');
        }

        try {
            // タイムゾーンを設定する
            $this->setTimeZone(new DateTimeZone($timezone));
        } catch(\Throwable $ex) {
            throw new Exception('timezoneが不正です');
        }

        return $this;
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

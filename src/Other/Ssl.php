<?php
namespace FrUtility\Other;

/**
 *
 * SSL状態-有効期限等の取得
 *
 * @var string $detail SSL情報
 * @var string $status live | dead | error
 * @var DateTime|null $expire_date 有効期限
 * @var int $expire_left 残り有効日数
 *
 */
class Ssl
{
    public $detail;// SSL情報
    public $status;// live | dead | error
    public $expire_date;// 有効期限
    public $expire_left;// 残り有効日数

    private $timeout_seconds = 3;// SSLチェック時のタイムアウト秒数

    /**
     *
     * @param string $domain ドメイン
     * @param int $timeout_seconds SSLチェック時のタイムアウト秒数
     *
     */
    public function __construct(string $domain, int $timeout_seconds = 3)
    {
        $this->timeout_seconds = $timeout_seconds;

        //
        $url = 'https://' . $domain;
        $this->detail = $this->getSslDetail($url);
        $this->expire_date = $this->getExpireDate($this->detail);
        if ($this->expire_date) {
            $this->expire_left = $this->get_days_since_today($this->expire_date);
        }
        $this->status = $this->getStatus();
    }

    /**
     *
     * SSLステータスを取得する
     *
     * @return string status
     *
     */
    private function getStatus()
    {
        // 有効期限がある場合
        if ($this->expire_date) {
            if ($this->expire_left > 0) {
                return 'live';
            } else {
                return 'expired';
            }
        }

        // そもそもサイトが閉まっている場合
        if (preg_match('/Unknown error/', $this->detail)) {
            return 'dead';
        }

        // SSL未設定 | Timeout時
        return 'error';
        // // SSLが未設定の場合 ( 10秒でタイムアウトしたらerrorになる )
        // // httpは存在してるけどhttpsは存在していない場合
        // if (preg_match('/Failed connect to/', $this->detail)) {
        //     return 'bad';
        // }

        // // Timeout場合
        // // SSLが未設定の場合
        // return 'error';
    }

    /**
     *
     * URLからSSL情報を取得する
     *
     * @param string $ssl_url https:://〇〇.com
     * @return string SSL情報の文字列
     *
     */
    private function getSslDetail(string $url): string
    {
        // SSL状態の取得
        $fp = tmpfile();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout_seconds);// Timeout設定
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_STDERR, $fp);
        curl_setopt($ch, CURLOPT_CERTINFO, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_NOBODY, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  2);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //文字列に出力
        $result = curl_exec($ch);
        fseek($fp, 0);
        $output = fread($fp, 8192);
        fclose($fp);

        return $output;
    }

    /**
     *
     * SSL有効期限を取得する。
     *
     * @param string $detail SSL詳細情報
     * @return DateTime|null 有効期限
     *
     */
    private function getExpireDate(string $detail): ?\DateTime
    {
        // 有効期限を抜き出す
        preg_match("/expire date:(.*?)\n/", $detail, $matches);
        $date = $matches[1] ?? '';
        if (!$date) {
            return null;
        }

        //
        $date = new \DateTime($date);
        return $date;
    }

    /**
     *
     * 今日との日付の差分を取得する
     *
     * @param DateTime $date 日付
     * @return int 差分の日数
     *
     */
    private function get_days_since_today(\DateTime $date): int
    {
        $now = new \DateTime();
        $left = $now->diff($date);
        $left = (int) $left->format('%R%a');
        return $left;
    }
}

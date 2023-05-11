# 壊滅的なバージョンアップ

## v1.0 -> v2.0

### Sitemap の仕様が大幅に変更

```php
use FrUtility\Other\Sitemap;
$Sitemap = new Sitemap();
$Sitemap->create($url, $data, $root, $filepath);// ファイル生成してGoogle通知までしていた。
```

=>

```php
use FrUtility\Other\Sitemap;
$Sitemap = new Sitemap($siteURL, $rootDir, $filePath);// setupするだけ。
$sitemapPath = $Sitemap->create($data, false);// ファイル生成だけ。
$Sitemap->upload();// Googleに通知。
$Sitemap->remove();// ファイル削除。
```

### Url と Utility を分割

#### v1.0 と同じように使う場合

```php
use FrUtility\Other\Url;
$domain = Url::getDomain($url);
```

=>

```php
use FrUtility\Url\Utility as UrlUtil;
$domain = UrlUtil::getDomain($url);
```

#### Url のインスタンス化ができるように

```php
use FrUtility\Url\Url;
$Url = new Url('https://ryo1999.com');
$this->assertEquals($Url->getDomain(), 'ryo1999.com');
```

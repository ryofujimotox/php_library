# How to use

1. write composer.json

``` json
"require": {
    "php": ">=5.6",
    "cakephp/cakephp": "3.9.*",
    "cakephp/migrations": "^2.0.0",
    "cakephp/plugin-installer": "^1.0",
    "josegonzalez/dotenv": "3.*",
    "mobiledetect/mobiledetectlib": "2.*",
    "friendsofcake/cakephp-csvview": "~3.0",
    "phpoffice/phpspreadsheet": "*",
    "tecnick.com/tcpdf": "*",
    "setasign/fpdi": "^2.0",
    "setasign/fpdi-tcpdf": "^2.0",
    "ateliee/mecab": "dev-master",
    "ryofujimotox/php_library": "dev-main"
},
"repositories": [
    {
    "type": "git",
    "url": "https://github.com/ryofujimotox/php_library"
    }
],
```

2. install

```
composer install
```

3. use

``` php
use FrUtility\Table\Prefecturer;
use FrUtility\Extended\ArrayKit;

// インストールできたことを確認するだけ
function installTest(){
    $Prefs = new Prefecturer();
    pr($Prefs);

    $test = ArrayKit::slice_firla([1, 2, 3, 4, 5], 1, 2);
    pr($test);
}
installTest();
exit;
```

### update

```
composer update
```





# Tests

1. install

```
composer install
```

2. run

```
./vendor/bin/phpunit tests
```
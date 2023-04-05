# How to use

1. write composer.json

``` json
"require": {
    "ryofujimotox/php_library": "^1"
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
// todo FW使ってる場合はいらない
require_once 'vendor/autoload.php';

use FrUtility\Table\Prefecturer;
use FrUtility\Extended\ArrayKit;

// インストールできたことを確認するだけ
function installTest() {
    $Prefs = new Prefecturer();
    var_dump($Prefs);

    $test = ArrayKit::slice_firla([1, 2, 3, 4, 5], 1, 2);
    var_dump($test);
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
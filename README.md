YoPHP
===========

A simple PHP wrapper for YO (www.justyo.co)

## Usage

1. Get an API token on [yoapi.justyo.co](http://yoapi.justyo.co/)
2. Checkout this repo or upload Yo.php to your sever
3. Copy and paste the following code and replace the YO_TOKEN

```php
include_once 'Yo.php';
define('YO_TOKEN','xxxx-yyyy-zzzz');

try {
    $yo = new Yo(YO_TOKEN);
    $yo->all();    
} catch (Exception $ex) {
    print $ex->getMessage();
}
```

## Accepted methods

Send Yo to all subscribers:
```php
$yo = new Yo(YO_TOKEN);
$yo->all();
```

Send Yo to an individual username:
```php
$yo = new Yo(YO_TOKEN);
$yo->user('BASVD');
```

Count total subscribers:
```php
$yo = new Yo(YO_TOKEN);
$count = $yo->count();
print_r($count);
```

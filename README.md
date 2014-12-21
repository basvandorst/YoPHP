YoPHP
===========

A simple PHP wrapper for YO (www.justyo.co)

## Usage

1. Get an API token on [dev.justyo.co](http://dev.justyo.co/)
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
$yo->all(); // Yo only
$yo->all('http://example.org'); // Yo with link
```

Send Yo to an individual user:
```php
$yo = new Yo(YO_TOKEN);
$yo->user('BASVD'); // Yo only
$yo->user('BASVD','http://example.org'); // Yo with link
$yo->user('BASVD', null, '52.2129918,5.2793703'); // Yo with location
```

Create new Yo accounts
```php
$yo = new Yo(YO_TOKEN);
$result = $yo->create('FOOBAR', 12345);
$result = $yo->create('FOOBAR', 12345, 'http://callback.url', 'foo@bar.org', 'description', true);
```

Checks if a username exists:
```php
$yo = new Yo(YO_TOKEN);
$result = $yo->check('BASVD');
print_r($result);
```

Count total subscribers:
```php
$yo = new Yo(YO_TOKEN);
$result = $yo->count();
print_r($result);
```

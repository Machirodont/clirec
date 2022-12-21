CliRequest object represents command line parameters.

Call script in CLI with parameters like:

```shell
php index.php -ab --key1=value1 value2
```

```php
$req=CliRequest::getRequest();

$req->get('a');      //true
$req->get('b');      //true
$req->get('ab');     //null
$req->get('key1');   //'value1'
$req->get(0);        //'value2'
```

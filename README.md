# http
curl client

### install:
````
composer require yonghua4413/http
````

### use:

````
use yonghua4413/http;

$client = new Client();
$client->request($url, $method, $data, $header);
````

### advance:
````
$client->default_config['time_out'] = 1000;//ms
$client->default_option[XXX] = XXX;

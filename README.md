# nhs-api-php
A PHP Library for NHS API services 


## Installation

Require this package with composer. It is recommended to only require the package for development.

```shell
composer require brain-tumour-charity/nhs-api-php --dev
```

Add the ServiceProvider to the providers array and the facade to your facades in config/app.php

```php
NHSAPI\NHSAPIServiceProvider::class
```
```php
'NHSAPI'  => NHSAPI\Facades\NHSAPI::class
```

Copy the package config to your local config with the publish command:

```shell
php artisan vendor:publish --provider="Barryvdh\Debugbar\ServiceProvider"
```

Publish config file
```shell
php artisan vendor:publish --tag=nhs_api
```


Publish the list of condition endpoints csv
```shell
php artisan vendor:publish --tag=nhs_api_conditions
```

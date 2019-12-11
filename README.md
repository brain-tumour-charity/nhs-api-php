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

Copy the package config to your local config
```shell
php artisan vendor:publish --tag=nhs_api
```

Copy the list of condition endpoints to your storage folder
```shell
php artisan vendor:publish --tag=nhs_api_conditions
```

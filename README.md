# nhs-api-php
A PHP Library for NHS API services. Currently compatible with the Laravel framework.


## Installation for Laravel

Require this package with composer. For the most up to date version, it is recommended to only require the package for development.

```shell
composer require brain-tumour-charity/nhs-api-php --dev
```

Add the ServiceProvider to the providers array and the facade to your facades in `config/app.php`

```php
NHSAPI\NHSAPIServiceProvider::class
```
```php
'NHSAPI'  => NHSAPI\Facades\NHSAPI::class
```

Copy the package config to your local config and enter any api keys you have available into the file
```shell
php artisan vendor:publish --tag=nhs_api
```

### Optional 
This package comes with a list of the currently available endpoints for the conditions and medicines API. Both lists in csv format and are up to date as of **Dec 2019**.

Copy the list of condition endpoints to your storage folder
```shell
php artisan vendor:publish --tag=nhs_api_conditions
```

Copy the list of medicine endpoints to your storage folder
```shell
php artisan vendor:publish --tag=nhs_api_medicines
```

## Available endpoints

- Conditions
  - Example usage: `NHSAPI::conditions()->condition('chemotherapy');`
- Medicines
  - Example usage: `NHSAPI::medicines()->medicine('low-dose-aspirin');`

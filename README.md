#Security Router - Service

[Readme em Português](https://github.com/resultsystems/laravel-security-router/blob/master/README-pt_BR.md).

## Installation

### 1. Dependency

Using <a href="https://getcomposer.org/" target="_blank">composer</a>, execute the following command to automatically update your `composer.json`:

```shell
composer require resultsystems/laravel-security-router
```

or manually update your `composer.json` file

```json
{
    "require": {
        "resultsystems/laravel-security-router": "1.*"
    }
}
```

### 2. Provider

You need to update your application configuration in order to register the package, so it can be loaded by Laravel. Just update your `config/app.php` file adding the following code at the end of your `'providers'` section:

```php
// file START ommited
    'providers' => [
        // other providers ommited
        'ResultSystems\SecurityRouter\Providers\SecurityRouterServiceProvider',
    ],
// file END ommited
```

###Made use:

Create your config file config/PACOTE.php

####Exemple:

```php
    'security'     => [
        'protected'  => true,
        'middleware' => ['auth'],
        'defender'   =>   [
            'load'       =>  true,
            'middleware' =>  ['your-middleware'],
            'can'        =>  [
                        'product.create',
                        'product.store',
                        'product.search',
                        'product.show',
                        'product.update',
                        'product.destroy',
                    ],
            'any'        =>  true,
            'is'         =>  null,
        ],
        'create'     =>   [
            'protected'  =>  false,
            'middleware' =>  [],
            'defender'   =>   [
                'load'       =>  true,
                'middleware' =>  ['your-middleware'],
                'can'        =>  ['product.create','product.store'],
                'any'        =>  true,
                'is'         =>  null,
            ],
         ],
        'store'     =>   [
            'protected'  =>  false,
            'middleware' =>  [],
            'defender'   =>   [
                'load'       =>  true,
                'middleware' =>  ['your-middleware'],
                'can'        =>  ['product.store'],
                'any'        =>  false,
                'is'         =>  null,
            ],
         ],
        'update'     =>   [
            'protected'  =>  false,
            'middleware' =>  [],
            'defender'   =>   [
                'load'       =>  true,
                'middleware' =>  ['your-middleware'],
                'can'        =>  ['product.store'],
                'any'        =>  false,
                'is'         =>  null,
            ],
         ],
    ],
```

####Use:

```php
$security=$this->app['security.router'];

$security=$security
    ->setFixedSecurity(['as'=>'index'])
    ->getConfig('storehouse-product', 'create');

Router::get('/product/create', $security,function (){
    return "I'm protected";
});

$security=$security
    ->setFixedSecurity(['as'=>'store'])
    ->getConfig('storehouse-product', 'store');

Router::post('product', $security,function (){
    return "I'm protected";
});

$security=$security
    ->setFixedSecurity(['as'=>'update','Uses'=>'Controller@update'])
    ->getConfig('storehouse-product', 'update');

Router::put('product/{id}', $security)->where('id', '[0-9]+');
```

####Use : package


```php
$group = [];
$group['prefix'] = 'product'
$group['namespace'] = 'namespace';
$group['as'] = 'product.';

$group=$security
    ->setFixedSecurity($group)
    ->getConfigPackage('storehouse-product');

Router::group($group, function () use ($security) {
    return 'Group protected';
});

Router::group($group, function () use ($security) {
    Router::post('store', $security
            ->setFixedSecurity(['as'=>'store'])
            ->getConfig('storehouse-product', 'store'),function (){
        return "I'm protected";
    });
});
```

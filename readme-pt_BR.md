#Security Router - Service

[Readme on English](https://github.com/resultsystems/laravel-security-router/blob/master/readme.md).

## Instalação

[Vídeo tutorial](https://www.youtube.com/watch?v=mIkeYIXBrt8).

### 1. Dependência

Usando o <a href="https://getcomposer.org/" target="_blank">composer</a>, execute o comando a seguir para instalar automaticamente `composer.json`:

```shell
composer require resultsystems/laravel-security-router
```

ou manualmente no seu arquivo `composer.json`

```json
{
    "require": {
        "resultsystems/laravel-security-router": "1.*"
    }
}
```

### 2. Provider

Para usar o SecurityRouter em sua aplicação Laravel, é necessário registrar o package no seu arquivo `config/app.php`. Adicione o seguinte código no fim da seção `providers`

```php
// file START ommited
    'providers' => [
        // other providers ommited
        'ResultSystems\SecurityRouter\Providers\SecurityRouterServiceProvider',
    ],
// file END ommited
```
###Forma de uso:

Crie um arquivo de configurações em config/PACOTE.php

####Exemplo:

```php
    'security'     => [
        'protected'  => false,
        'middleware' => ['auth'],
        'defender'   =>   [
            'load'       =>  true,
            'middleware' =>  ['sua-middleware'],
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
                'middleware' =>  ['sua-middleware'],
                'can'        =>  ['storehouse.product.create','storehouse.product.store'],
                'any'        =>  false,
                'is'         =>  null,
            ],
         ],
        'store'     =>   [
            'protected'  =>  false,
            'middleware' =>  [],
            'defender'   =>   [
                'load'       =>  true,
                'middleware' =>  ['sua-middleware'],
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
                'middleware' =>  ['sua-middleware'],
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
    return 'Eu estou protegido';
});

$security=$security
    ->setFixedSecurity(['as'=>'store'])
    ->getConfig('storehouse-product', 'store');

Router::post('product', $security,function (){
    return 'Eu estou protegido';
});

$security=$security
    ->setFixedSecurity(['as'=>'update','Uses'=>'Controller@update'])
    ->getConfig('storehouse-product', 'update');

Router::put('product/{id}', $security)->where('id', '[0-9]+');
```

####Uso : pacote


```php
$group = [];
$group['prefix'] = 'product'
$group['namespace'] = 'namespace';
$group['as'] = 'product.';

$group=$security
    ->setFixedSecurity($group)
    ->getConfigPackage('storehouse-product');

Router::group($group, function () use ($security) {
    return 'Grupo protegido';
});

Router::group($group, function () use ($security) {
    Router::post('store', $security
            ->setFixedSecurity(['as'=>'store'])
            ->getConfig('storehouse-product', 'store'),function (){
        return 'Eu estou protegido';
    });
});
```
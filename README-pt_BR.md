#Security Router - Service

[Readme on English](https://github.com/resultsystems/laravel-security-router/blob/master/README.md).

## Instalação

### 1. Dependência

Using <a href="https://getcomposer.org/" target="_blank">composer</a>, execute the following command to automatically update your `composer.json`:

```shell
composer require resultsystems/laravel-security-router
```

ou manualmente pelo no seu arquivo `composer.json`

```json
{
    "require": {
        "resultsystems/laravel-security-router": "1.*"
    }
}
```

### 2. Provider

Para usar o Defender em sua aplicação Laravel, é necessário registrar o package no seu arquivo `config/app.php`. Adicione o seguinte código no fim da seção `providers`

```php
// file START ommited
    'providers' => [
        // other providers ommited
        'ResultSystems\SecurityRouter\Providers\SecurityRouterServiceProvider',
    ],
// file END ommited
```
#Forma de uso:

Crie um arquivo de configurações em config/PACOTE.php

Exemplo:

```php
    'security'     => [
        'create'     =>   [
            'protected'  =>  false,
            'middleware' =>  [],
            'defender'   =>   [
                'load'       =>  true,
                'middleware' =>  ['sua-middware'],
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
                'middleware' =>  ['sua-middware'],
                'can'        =>  ['product.store'],
                'any'        =>  false,
                'is'         =>  null,
            ],
         ],
    ],
```

Exemplo:

```php
$security=$this->app['security.router'];

$security=$security
    ->setFixedSecurity(['as'=>'index'])
    ->getConfig('storehouse-product', 'create');

Router::get('/product/create', $security,function (){
    retunr 'Eu estou protegido';
});

$security=$security
    ->setFixedSecurity(['as'=>'update'])
    ->getConfig('storehouse-product', 'store');

Router::post('product', $security,function (){
    retunr 'Eu estou protegido';
});
```
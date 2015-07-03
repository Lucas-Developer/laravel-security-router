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
        'edit'     =>   [
            'protected'  =>  false,
            'middleware' =>  [],
            'defender'   =>   [
                'load'       =>  true,
                'middleware' =>  ['sua-middware'],
                'can'        =>  ['storehouse.product.edit'],
                'any'        =>  false,
                'is'         =>  null,
            ],
         ],
    ],
```

Deverá funcionar assim:

```php
$security=SecurityRouter::getConfig('pacote','key');
Router::get('/path',$security, function() {
    return 'Eu estou seguro';
});
```

Ainda em desenvolvimento. Este é o projeto e a forma que deve funcionar.
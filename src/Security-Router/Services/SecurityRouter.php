<?php

namespace ResultSystems\SecurityRouter\Services;

/**
*
* return SecurityRouter::getConfig($package, $key);
*
*/

class SecurityRouter
{
    protected $package;
    protected $appConfig;
    protected $fixedSecurity=[];
    protected $key;
    protected $primaryKey='security';

    /**
     * Construi a class Security Router, dependente do $this->app['Config']
     * @param Illuminate\Contracts\Config\Repository $appConfig  objeto do tipo $this->app['config']
     */
    public function __construct($appConfig)
    {
        $this->appConfig=$appConfig;
    }

    /**
     * Recebe o valor da configuração pela key
     * @param  string                 $key     chave
     * @param  array|null|bool|string $default valor padrão caso não exista nas configurações
     * @return array|null|bool|string Retorna o valor encontrado nas configurações
     */
    public function getKey($key='', $default=null)
    {
        return $this->appConfig->get($this->package.'.'.$this->primaryKey.'.'.$key, $default);
    }

    /**
     * Verifica se é necessário proteger antes de usar o defender
     * ou mesmo sem necessidade de usá-lo
     * @return bool true/false
     */
    protected function isProtected()
    {
        return $this->getKey($this->key.'protected', true);
    }

    /**
     * Verifica se é para carregar o defender
     * @return bool true/false
     */
    protected function isDefenderLoad()
    {
        return $this->getKey($this->key.'defender.load', true);
    }

    /**
     * recebe o(s) nome(s) do(s) middleware que será usado
     * @return array array
     */
    protected function getMiddleware()
    {
        return $this->getKey($this->key.'middleware', []);
    }

    /**
     * recebe o(s) nome(s) do(s) middleware que será usado no defender
     * @return array array
     */
    protected function getDefenderMiddleware()
    {
        return $this->getKey($this->key.'defender.middleware', []);
    }

    /**
     * recebe as opções a serem usadas na opção "can" do defender
     * @return array|string
     */
    protected function getDefenderCan()
    {
        return $this->getKey($this->key.'defender.can', []);
    }

    /**
     * recebe as opções a serem usadas na opção "is" do defender
     * @return array|string
     */
    protected function getDefenderIs()
    {
        return $this->getKey($this->key.'defender.is', []);
    }

    /**
     * recebe o valor a ser usadas na opção "any" do defender
     * @return bool bool
     */
    protected function getDefenderAny()
    {
        return $this->getKey($this->key.'defender.any', false);
    }

    /**
     * seta o valor de key
     * @param string $key valor da chave
     */
    protected function setKey($key)
    {
        $this->key=$key;
        if ($this->key!='') {
            $this->key.='.';
        }
    }

    /**
     * seta as configurações a serem usadas
     * @param string $package nome do pacote que será usado.
     * @param string $key     chave a ser usada para consulta do pacote
     */
    protected function setConfig($package, $key, $controller='')
    {
        $this->setPackage($package);
        $this->setKey($key);
        $security=$this->fixedSecurity;
        if ($controller!='') {
            $security['as']=$key;
            $security['uses']=$controller.'@'.$key;
        }

        //verify is protected
        if ($this->isProtected()) {
            $middleware=$this->getMiddleware();
            if (isset($security['middleware'])) {
                $security['middleware']=array_merge($security['middleware'], $middleware);
            } else {
                $security['middleware']=$middleware;
            }
        }

        //verify is defender load
        if ($this->isDefenderLoad()) {
            $defenderMiddleware=$this->getDefenderMiddleware();
            if (isset($security['middleware'])) {
                $security['middleware']=array_merge($security['middleware'], $defenderMiddleware);
            } else {
                $security['middleware']=$defenderMiddleware;
            }

            $can=$this->getDefenderCan();
            if ($can!=null && !empty($can)) {
                $security['can']=$can;
            }

            $is=$this->getDefenderIs();
            if ($is!=null && !empty($is)) {
                $security['is']=$is;
            }
            if ($can || $is) {
                $security['any']=$this->getDefenderAny();
            }
        }
        $this->fixedSecurity=[];
        return $security;
    }

    /**
     * seta um valor padrão a ser retornado junto com as configurações
     * @param array $value array
     */
    public function setFixedSecurity($value=[])
    {
        $this->fixedSecurity=$value;
        return $this;
    }

    /**
     * seta o nome do pacote que está será feito as configuras
     * @param string $value
     */
    public function setPackage($value)
    {
        $this->package=$value;
        return $this;
    }

    /**
     * pega as configurações
     * @param  string $package nome do pacote a ser usado
     * @param  string $key     chave a ser consultada
     * @param  string $controller     chave a ser consultada
     * @return array          array com as configurações
     */
    public function getConfig($package, $key, $controller='')
    {
        return $this->setConfig($package, $key, $controller);
    }

    /**
     * pega as configurações do grupo
     * @param  string $package nome do pacote a ser usado
     * @param  string $key     chave a ser consultada
     * @return array          array com as configurações
     */
    public function getConfigPackage($package)
    {
        return $this->setConfig($package, '');
    }
}

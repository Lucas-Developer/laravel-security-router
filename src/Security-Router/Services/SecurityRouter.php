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
 * [__construct description]
 * @param [type] $appConfig [description]
 */
    public function __construct($appConfig)
    {
        $this->appConfig=$appConfig;
    }

/**
 * [getKey description]
 * @param  [type] $key     [description]
 * @param  [type] $default [description]
 * @return [type]          [description]
 */
    public function getKey($key, $default=null)
    {
        return $this->appConfig->get($this->package.'.'.$this->primaryKey.'.'.$key, $default);
    }

/**
 * [isProtected description]
 * @return boolean [description]
 */
    protected function isProtected()
    {
        return $this->getKey($this->key.'protected', true);
    }

/**
 * [isDefenderLoad description]
 * @return boolean [description]
 */
    protected function isDefenderLoad()
    {
        return $this->getKey($this->key.'defender.load', true);
    }

/**
 * [getMiddleware description]
 * @return [type] [description]
 */
    protected function getMiddleware()
    {
        return $this->getKey($this->key.'middleware', []);
    }

/**
 * [getDefenderMiddleware description]
 * @return [type] [description]
 */
    protected function getDefenderMiddleware()
    {
        return $this->getKey($this->key.'defender.middleware', []);
    }

/**
 * [getDefenderCan description]
 * @return [type] [description]
 */
    protected function getDefenderCan()
    {
        return $this->getKey($this->key.'defender.can', []);
    }

/**
 * [getDefenderIs description]
 * @return [type] [description]
 */
    protected function getDefenderIs()
    {
        return $this->getKey($this->key.'defender.is', []);
    }

/**
 * [getDefenderAny description]
 * @return [type] [description]
 */
    protected function getDefenderAny()
    {
        return $this->getKey($this->key.'defender.any', false);
    }

/**
 * [setKey description]
 * @param [type] $key [description]
 */
    public function setKey($key)
    {
        $this->key=$key.'.';
    }

/**
 * [setConfig description]
 * @param [type] $package [description]
 * @param [type] $key     [description]
 */
    protected function setConfig($package, $key)
    {
        $this->setPackage($package);
        $this->setKey($key);

        $security=$this->fixedSecurity;

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
            $security['any']=$this->getDefenderAny();
        }
        return $security;
    }

/**
 * [setFixedSecurity description]
 * @param array $value [description]
 */
    public function setFixedSecurity($value=[])
    {
        $this->fixedSecurity=$value;
        return $this;
    }

/**
 * [setPackage description]
 * @param [type] $value [description]
 */
    public function setPackage($value)
    {
        $this->package=$value;
        return $this;
    }

/**
 * [getConfig description]
 * @param  [type] $package [description]
 * @param  [type] $key     [description]
 * @return [type]          [description]
 */
    public function getConfig($package, $key)
    {
        return $this->setConfig($package, $key);
    }
}

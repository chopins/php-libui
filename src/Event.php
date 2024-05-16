<?php

/**
 * php-libui (http://toknot.com)
 *
 * @copyright  Copyright (c) 2019 Szopen Xiao (Toknot.com)
 * @license    http://toknot.com/LICENSE.txt New BSD License
 * @link       https://github.com/chopins/php-libui
 * @version    0.1
 */

namespace UI;

class Event
{
    protected $call = null;
    protected $data = null;
    protected $before = null;
    protected $after = null;
    protected $result = ['after' => null, 'before' => null, 'call' => null];
    protected $property = [];

    public function __construct(callable $callable, $data = null)
    {
        $this->call = $callable;
        $this->data = $data;
    }

    public function getCall()
    {
        return $this->call;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getBefore()
    {
        return $this->before;
    }

    public function getAfter()
    {
        return $this->after;
    }

    public function onEvent(callable $callable)
    {
        $this->call = $callable;
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function onBefore(callable $callable)
    {
        $this->before = $callable;
    }

    public function onAfter(callable $callable)
    {
        $this->after = $callable;
    }
    public function beforeInvoke($params)
    {
        $this->triggerEvent('before', $params);
    }
    public function afterInvoke($params)
    {
        $this->triggerEvent('after', $params);
    }
    public function invoke($params)
    {
        $this->triggerEvent('call', $params);
    }

    protected function triggerEvent($type, $params)
    {
        if ($this->$type) {
            $callable = $this->$type;
            $this->result[$type] = $callable(...$params);
        }
    }
    public function getBeforeResult()
    {
        return $this->result['before'];
    }
    public function getAfterResult()
    {
        return $this->result['after'];
    }

    public function __set($name, $value)
    {
        $this->property[$name] = $value;
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->property)) {
            return $this->property[$name];
        }
    }
}

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

use Closure;
use ReflectionFunction;

class Event
{
    protected $call = null;
    protected $bindParams = null;
    protected $before = null;
    protected $after = null;
    protected $target = null;
    protected $eventData = [];
    private $beforeDataKey = null;
    private $callDataKey = null;
    const EVENT_BEFORE = 'before';
    const EVENT_AFTER = 'after';
    const EVENT_CALL = 'call';

    public function __construct(callable $callable, $bindParams = null)
    {
        $this->call = $callable;
        $this->bindParams = $bindParams;
    }
    public function getTarget()
    {
        return $this->target;
    }
    public function ui()
    {
        return $this->target->getUI();
    }
    public function build()
    {
        return $this->target->getBuild();
    }
    public function getCall()
    {
        return $this->call;
    }

    public function getBindParams()
    {
        return $this->bindParams;
    }

    public function getBefore()
    {
        return $this->before;
    }

    public function getAfter()
    {
        return $this->after;
    }

    public function onEvent(callable $callable, $resKey = null)
    {
        $this->call = $callable;
        $this->callDataKey = $resKey;
    }

    public function setBindParams($bindParams)
    {
        $this->bindParams = $bindParams;
    }

    public function onBefore(callable $callable, $resKey = null)
    {
        $this->before = $callable;
        $this->beforeDataKey = $resKey;
    }

    public function onAfter(callable $callable)
    {
        $this->after = $callable;
    }
    protected function beforeInvoke()
    {
        $this->triggerEvent(self::EVENT_BEFORE);
        if ($this->beforeDataKey) {
            $this->eventData[$this->beforeDataKey] = &$this->eventData[self::EVENT_BEFORE];
        }
    }
    protected function afterInvoke()
    {
        $this->triggerEvent(self::EVENT_AFTER);
    }
    public function trigger($target, $params)
    {
        $this->target = $target;
        $this->beforeInvoke();
        $this->eventData = $params;
        $this->triggerEvent(self::EVENT_CALL);
        if ($this->callDataKey) {
            $this->eventData[$this->callDataKey] = &$this->eventData[self::EVENT_CALL];
        }
        $this->afterInvoke();
    }

    protected function triggerEvent($type)
    {
        if ($this->$type) {
            $f = Closure::fromCallable($this->$type);
            if(($b = @$f->bindTo($this->target))) {
                $f = $b;
            }
            $this->eventData[$type] = $f($this);
        }
    }
    public function __get($name)
    {
        if (array_key_exists($name, $this->eventData)) {
            return $this->eventData[$name];
        }
    }
}

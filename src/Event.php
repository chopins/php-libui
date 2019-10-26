<?php

namespace UI;

class Event
{
    protected $func;
    protected $data;
    protected $before;
    protected $after;

    public function __construct(callable $callable, $data = null)
    {
        $this->func = $callable;
        $this->data = $data;
    }

    public function getFunc()
    {
        return $this->func;
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
        $this->func = $callable;
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

}

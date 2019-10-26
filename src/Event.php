<?php

namespace UI;

class Event
{ 
    protected callable $func;
    protected $data;
    protected callable $before;
    protected callable $after;
    public function __construct(callable $callable, $data = null)
    {
        $this->func = $callable;
        $this->data = $data;
    }

    public function getFunc() {
        return $this->func;
    }
    public function getData() {
        return $this->data;
    }

    public function getBefore() {
        return $this->before;
    }

    public function getAfter() {
        return $this->after;
    }

    public function onBefore(callable  $callable) {
        $this->before = $callable;
    }

    public function onAfter(callable  $callable) {
        $this->after = $callable;
    }
}

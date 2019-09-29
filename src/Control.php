<?php

class Controll
{
    private $type = '';
    private $id = '';
    private $instance = null;
    private $attr = [];
    private $title = '';

    public function __construct(string $type, string $id, \FFI\CData $instance, array $attr)
    {
        $this->type = $type;
        $this->id = $id;
        $this->instance = $instance;
        $this->title = $attr['title'];
        $this->attr = $attr;
    }

    public function __get($name)
    {
        return $this->$name;
    }
}

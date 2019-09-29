<?php

class Control
{
    private $type = '';
    private $id = '';
    private $instance = null;
    private $attr = [];
    private $title = '';
    private static $ui;

    public function __construct($ui, \FFI\CData $instance, array $attr)
    {
        self::$ui = $ui;
        $this->instance = $instance;
        $this->title = $attr['title'];
        $this->attr = $attr;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getHandle() {
        return self::$ui->uiControlHandle($this->instance);
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function __get($name)
    {
        return $this->$name;
    }

    public function show()
    {
        self::$ui->controlShow($this->instance);
    }

    public function hide()
    {
        self::$ui->controlHide($this->instance);
    }

    public function enable()
    {
        self::$ui->controlEnable($this->instance);
    }

    public function disbale()
    {
        self::$ui->controlDisable($this->instance);
    }
    public function destroy()
    {
        self::$ui->controlDestroy($this->instance);
    }

    public function parent()
    {
        return self::$ui->controlParent($this->instance);
    }

    public function setParent($parent)
    {
        self::$ui->controlSetParent($this->instance, $parent);
    }

    public function isVisible()
    {
        return self::$ui->controlVisible($this->instance);
    }

    public function isEnabled()
    {
        return self::$ui->controlEnabled($this->instance);
    }

    public function getTopLevel()
    {
        return self::$ui->controlToplevel($this->instance);
    }
}

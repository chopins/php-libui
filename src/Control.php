<?php

/**
 * php-libui (http://toknot.com)
 *
 * @copyright  Copyright (c) 2019 Szopen Xiao (Toknot.com)
 * @license    http://toknot.com/LICENSE.txt New BSD License
 * @link       https://github.com/chopins/php-libui
 */

namespace UI;

use FFI;
use UI\UIBuild;
use UI\UI;
use FFI\CData;

/**
 * @property-read string $id
 * @property-read array $childs
 * @property-read string $name
 */
abstract class Control
{
    const CTL_NAME = '';
    const IS_CONTROL = true;
    const CHILD_TYPE = 1;

    protected ?CData $instance = null;
    protected array $attr = [];
    protected ?UIBuild $build = null;

    /**
     * @var \UI\UI
     */
    protected static ?UI $ui = null;
    protected $callPrefix = '';
    protected $callPrefixFuncList = [];
    protected $handle = 0;
    private $win;
    private $parent;
    protected $childs = [];

    public function __construct(UIBuild $build, array $attr, CData $instance = null)
    {
        $this->build = $build;
        if (is_null(self::$ui)) {
            self::$ui = $build->getUI();
        }
        $this->attr = $attr;
        if ($instance === null) {
            $this->instance = $this->newControl();
        } else {
            $this->instance = $instance;
        }
        if ($this::CTL_NAME == 'win') {
            $this->win = $this;
        }

        $this->build->appendControl($this);
        if ($this::CHILD_TYPE === 1) {
            $this->pushChilds();
        } else if ($this::CHILD_TYPE === -1) {
            $this->pushItemChilds();
        }
    }

    abstract protected function newControl(): CData;

    protected function pushItemChilds()
    {
    }

    protected function setRelationship($control)
    {
        $control->win = $this->win;
        $control->parent = $this;
        $this->childs[] = $control;
    }

    final public function pushChilds()
    {
        $this->attr['childs'] = $this->attr['childs'] ?? [];
        $this->prepareOption();
        foreach ($this->attr['childs'] as $i => $child) {
            $control = $this->build->createItem($child, $i, $this::class);
            $this->addChild($control, $child);
            $this->setRelationship($control);
        }
    }

    protected function prepareOption()
    {
    }

    protected function addChild(Control $child, $options = [])
    {
    }

    public function appendChild(\UI\Control $child, $options = null)
    {
        $this->updateChildsList($child);
        $this->append($child, $options);
    }
    protected function append(\UI\Control $child, $options = null)
    {
        $this->addChild($child, $options);
    }

    protected function updateChildsList(Control $child)
    {
        $attr = $child->getAttr();
        $attr['name'] = $child::CTL_NAME;
        $this->attr['childs'][] = $attr;
        $this->setRelationship($child);
    }

    public function getWin()
    {
        return $this->win;
    }
    public function parent()
    {
        return $this->parent;
    }

    public function getAttr($key = null)
    {
        if ($key !== null) {
            return $this->attr[$key] ?? null;
        }
        return $this->attr;
    }

    public function getBuild()
    {
        return $this->build;
    }

    public function getUI()
    {
        return self::$ui;
    }

    public function getTag()
    {
        return $this->tag;
    }

    public function getUIInstance()
    {
        return $this->instance;
    }

    public static function controlPtr($instance)
    {
        return FFI::cast('uintptr_t', $instance)->cdata;
    }

    public function getControlHandle()
    {
        if ($this::IS_CONTROL) {
            return $this->controlHandle();
        } else {
            return self::controlPtr($this->instance);
        }
    }

    public function __get($name)
    {
        return $this->getAttr($name);
    }

    public function __call($func, $args = [])
    {
        if (in_array($func, $this->callPrefixFuncList)) {
            $func = $this->callPrefix . ucfirst($func);
        }
        array_unshift($args, $this->instance);
        return self::$ui->$func(...$args);
    }

    public function show()
    {
        $this->controlShow();
    }

    public function hide()
    {
        $this->controlHide();
    }

    public function enable()
    {
        $this->controlEnable();
    }

    public function disbale()
    {
        $this->controlDisable();
    }

    public function destroy()
    {
        $this->controlDestroy();
    }

    public function parentPtr()
    {
        return $this->controlParent();
    }

    public function setParent($parent)
    {
        $this->controlSetParent($parent);
    }

    public function isVisible()
    {
        return $this->controlVisible();
    }

    public function isEnabled()
    {
        return $this->controlEnabled();
    }

    public function getTopLevel()
    {
        return $this->controlToplevel();
    }

    public function assertEnum(&$v, $type)
    {
        if (!$v instanceof $type) {
            $v = $type::from($v);
        }
    }

    public function bindEvent($event, Event $callable)
    {
        $this->$event(function (...$params) use ($event, $callable) {
            try {
                $callable->setTargetPtr($params[0]);
                $callable->trigger($event, $this, ['data' => $params[1]]);
            } catch (\Exception $e) {
                echo $e;
            } catch (\Error $e) {
                echo $e;
            }
        }, $callable->getBindParams());
    }
}

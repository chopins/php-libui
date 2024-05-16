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
        $this->handle = $this::CTL_NAME . spl_object_id($this->instance);
        $this->build->appendControl($this);
        $this->pushChilds();
    }

    public static function uiControl(UIBuild $build, CData $control)
    {
        $ins = new static($build, [], $control);
        return $ins;
    }

    abstract public function newControl(): CData;

    public function pushChilds()
    {
        $this->attr['childs'] = $this->attr['childs'] ?? [];
        foreach ($this->attr['childs'] as $child) {
            $control = $this->build->createItem($child);
            $this->addChild($control, $child);
        }
    }

    protected function addChild(Control $child, $options = [])
    {
    }

    public function appendChild(\UI\Control $child)
    {
        $this->updateChildsList($child);
        $this->addChild($child);
    }

    protected function updateChildsList(Control $child)
    {
        $attr = $child->getAttr();
        $attr['name'] = $child::CTL_NAME;
        $this->attr['childs'][] = $attr;
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

    public function getHandle()
    {
        if ($this::IS_CONTROL) {
            return $this->controlHandle();
        } else {
            return $this->handle;
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

    public function parent()
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

    public function bindEvent($event, Event $callable)
    {
        $this->$event(function (...$params) use ($callable) {
            try {
                $args = [$callable, $this, $params[1]];
                $callable->beforeInvoke($args);
                $callable->invoke($args);
                $callable->afterInvoke($args);
            } catch (\Exception $e) {
                echo $e;
            } catch (\Error $e) {
                echo $e;
            }
        }, $callable->getData());
    }
}

<?php

/**
 * php-libui (http://toknot.com)
 *
 * @copyright  Copyright (c) 2019 Szopen Xiao (Toknot.com)
 * @license    http://toknot.com/LICENSE.txt New BSD License
 * @link       https://github.com/chopins/php-libui
 * @version    0.1
 */

namespace UI\Control;

use FFI;
use UI\Control;
use FFI\CData;
use UI\Event;

/**
 * Create password,search,radio,select,checkbox,text
 * @property-read string $type
 * @property-read bool $wrap
 * @property-read array $option
 * @property-read bool $editable
 * @property-read string $title
 * @property-read int $mix
 * @property-read int $max
 * @property-read bool $readonly
 * @property-read \UI\Event $change
 * @property-read \UI\Event $click
 */
class Input extends Control
{
    const CTL_NAME = 'input';
    public $name = '';
    protected function newControl(): CData
    {
        $this->attr['type'] = $this->attr['type'] ?? null;
        $this->name = $this->attr['name'] ?? '';
        switch ($this->attr['type']) {
            case 'password':
                $this->instance = self::$ui->newPasswordEntry();
                break;
            case 'search':
                $this->instance = self::$ui->newSearchEntry();
                break;
            case 'textarea':
                $this->attr['wrap'] = $this->attr['wrap'] ?? 0;
                $this->instance = $this->attr['wrap'] ? self::$ui->newMultilineEntry() : self::$ui->newNonWrappingMultilineEntry();
                break;
            case 'radio':
                $this->instance = self::$ui->newRadioButtons();
                if (isset($this->attr['option'])) {
                    foreach ($this->attr['option'] as $label) {
                        $this->radioButtonsAppend($label);
                    }
                }
                break;
            case 'select':
                $this->attr['editable'] = $this->attr['editable'] ?? 0;
                $this->instance = $this->attr['editable'] ? self::$ui->newEditableCombobox() : self::$ui->newCombobox();
                if (isset($this->attr['option'])) {
                    $selectFunc = $this->attr['editable'] ? 'editableComboboxAppend' : 'comboboxAppend';
                    foreach ($this->attr['option'] as $label) {
                        $this->$selectFunc($label);
                    }
                }
                break;
            case 'checkbox':
                $this->instance = self::$ui->newCheckbox($this->attr['title']);
                break;
            case 'number':
                $this->instance = self::$ui->newSpinbox($this->attr['min'], $this->attr['max']);
                break;
            case 'slider':
                $this->attr['min'] = $this->attr['min'] ?? 0;
                $this->attr['max'] = $this->attr['max'] ?? 100;
                $this->instance = self::$ui->newSlider($this->attr['min'], $this->attr['max']);
                break;
            case 'text':
            default:
                $this->instance = self::$ui->newEntry();
        }
        if (isset($this->attr['readonly'])) {
            $this->entrySetReadOnly($this->attr['readonly']);
        }
        if (isset($this->attr['change'])) {
            $this->onChange($this->attr['change']);
        }
        if (isset($this->attr['click'])) {
            $this->onClick($this->attr['click']);
        }
        return $this->instance;
    }

    public function addOption($value)
    {
        if ($this->attr['type'] === 'radio') {
            $this->radioButtonsAppend($value);
        } elseif ($this->attr['type'] === 'select') {
            $this->comboboxAppend($value);
        }
    }

    public function getValue()
    {
        $isInt = false;
        switch ($this->attr['type']) {
            case 'checkbox':
                $v = $this->checkboxText();
                break;
            case 'radio':
                return $this->radioButtonsSelected();
            case 'textarea':
                $v = $this->multilineEntryText();
                break;
            case 'select':
                return $this->attr['editable'] ? $this->editableComboboxText() : $this->comboboxSelected();
            case 'number':
                return $this->spinboxValue();
            case 'slider':
                return $this->sliderValue();
            default:
                $v = $this->entryText();
        }
        return FFI::string($v);
    }

    public function setValue($v)
    {
        switch ($this->attr['type']) {
            case 'checkbox':
                return $this->checkboxSetText($v);
            case 'radio':
                return $this->radioButtonsSetSelected($v);
            case 'select':
                return $this->attr['editable'] ? $this->editableComboboxSetText($v) : $this->comboboxSetSelected($v);
            case 'number':
                return $this->spinboxSetValue($v);
            case 'slider':
                return $this->sliderSetValue($v);
            case 'textarea':
                if (is_scalar($v)) {
                    $this->multilineEntrySetText($v);
                } else {
                    foreach ($v as $c) {
                        $this->multilineEntryAppend($c);
                    }
                }
                return;
            case 'text':
            default:
                return $this->entrySetText($v);
        }
    }

    public function readonly($set = true)
    {
        switch ($this->attr['type']) {
            case 'textarea':
                $this->multilineEntrySetReadOnly($set);
                break;
            case 'radio' || 'checkbox':
                break;
            default:
                $this->entrySetReadOnly($set);
        }
    }

    public function isReadonly()
    {
        switch ($this->attr['type']) {
            case 'textarea':
                return $this->multilineEntryReadOnly();
            case 'radio' || 'checkbox':
                return null;
            default:
                return $this->entryReadOnly();
        }
    }

    public function setCheck($checked = 1)
    {
        switch ($this->attr['type']) {
            case 'radio':
                $this->radioButtonsSetSelected($checked);
                break;
            case 'checkbox':
                $this->checkboxSetChecked($checked);
                break;
            default:
                break;
        }
    }

    public function isCheck()
    {
        switch ($this->attr['type']) {
            case 'radio':
                return $this->radioButtonsSelected();
            case 'checkbox':
                return $this->checkboxChecked();
            default:
                break;
        }
    }

    public function onChange(Event $callable)
    {
        switch ($this->attr['type']) {
            case 'textarea':
                return $this->bindEvent('multilineEntryOnChanged', $callable);
            case 'radio':
            case 'checkbox':
                return;
            case 'slider':
                return $this->bindEvent('sliderOnChanged', $callable);
            case 'select':
                $event = $this->attr['editable'] ? 'editableComboboxOnChanged' : 'comboboxOnSelected';
                $this->bindEvent($event, $callable);
                return;
            case 'number':
                return $this->bindEvent('spinboxOnChanged', $callable);
            default:
                return $this->bindEvent('entryOnChanged', $callable);
        }
    }

    public function onClick(Event $callable)
    {
        switch ($this->attr['type']) {
            case 'radio':
                return $this->bindEvent('radioButtonsOnSelected', $callable);
            case 'checkbox':
                return $this->bindEvent('checkboxOnToggled', $callable);
            default:
                break;
        }
    }

}

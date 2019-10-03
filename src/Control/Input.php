<?php

namespace UI\Control;

use UI\Control;
use FFI\CData;

/**
 * Create password,search,radio,select,checkbox,text
 */
class Input extends Control
{
    public function newControl(): CData
    {
        $$this->attr['type'] = $this->attr['type'] ?? null;
        switch ($this->attr['type']) {
            case 'password':
                $this->instance = self::$ui->newPasswordEntry();
                break;
            case 'search':
                $this->instance = self::$ui->newSearchEntry();
                break;
            case 'textarea':
                $this->instance = $this->attr['wrap'] ? self::$ui->newMultilineEntry() : self::$ui->newNonWrappingMultilineEntry();
                break;
            case 'radio':
                $this->instance = self::$ui->newRadioButtons();
                if (isset($this->attr['option'])) {
                    foreach ($this->attr['option'] as $label) {
                        $this->radioButtonsAppend($label);
                    }
                }
                if (isset($this->attr['click'])) {
                    $this->onClick($this->attr['click']);
                }
                break;
            case 'select':
                $this->instance = self::$ui->newCombobox();
                if (isset($this->attr['option'])) {
                    foreach ($this->attr['option'] as $label) {
                        $this->comboboxAppend($label);
                    }
                }
                if (isset($this->attr['change'])) {
                    $this->onChage($this->attr['change']);
                }
                break;
            case 'checkbox':
                $this->instance = self::$ui->newCheckbox($this->attr['title']);
                if (isset($this->attr['click'])) {
                    $this->onClick($this->attr['click']);
                }
                break;
            case 'text':
            default:
                $this->instance = self::$ui->newEntry();
        }
        if ($this->attr['readonly']) {
            $this->entrySetReadOnly($this->attr['readonly']);
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
        if ($this->attr['type'] === 'checkbox') {
            $v = $this->checkboxText();
        } elseif ($this->attr['type'] === 'radio') {
            $v = $this->radioButtonsSelected();
        } elseif ($this->attr['type'] === 'textarea') {
            $v = $this->multilineEntryText();
        } elseif ($this->attr['type'] === 'select') {
            $v = $this->comboboxSelected();
        } else {
            $v = $this->entryText();
        }
        return self::$ui->string($v);
    }

    public function setValue($v)
    {
        if ($this->attr['type'] === 'checkbox') {
            $this->checkboxSetText($v);
        } elseif ($this->attr['type'] === 'radio') {
            $this->radioButtonsSetSelected($v);
        } elseif ($this->attr['type'] === 'select') {
            $v = $this->comboboxSetSelected();
        } elseif ($this->attr['type'] === 'textarea') {
            if (is_scalar($v)) {
                $this->multilineEntrySetText($v);
            } else {
                foreach ($v as $c) {
                    $this->multilineEntryAppend($c);
                }
            }
        } else {
            $this->entrySetText($v);
        }
    }

    public function readonly($set = true)
    {
        switch ($this->attr['type']) {
            case 'textarea':
                $this->multilineEntrySetReadOnly($set);
                break;
            case 'radio' ||  'checkbox':
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
            case 'radio' ||  'checkbox':
                return null;
            default:
                return $this->entryReadOnly();
        }
    }

    public function check($checked = 1)
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

    public function onChange(array $callable)
    {
        switch ($this->attr['type']) {
            case 'textarea':
                $this->bindEvent('multilineEntryOnChanged', $callable);
                break;
            case 'radio' || 'checkbox':
                break;
            case 'select':
                $this->bindEvent('comboboxOnSelected', $callable);
                break;
            default:
                $this->bindEvent('uiEntryOnChanged', $callable);
        }
    }


    public function onClick(array $callable)
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

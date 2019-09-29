<?php

class UIBuild
{
    public static $ui;
    protected $nodes = [];
    protected $win = null;
    public function __construct($ui)
    {
        self::$ui = $ui;
    }

    public function getWin()
    {
        return $this->win;
    }

    public function window($title, $width, $height, $hasMenu)
    {
        $err = self::$ui->init();
        if ($err) {
            throw new ErrorException($err);
        }
        $this->win = self::$ui->newWindow($title, $width, $height, $hasMenu);
        return $this->win;
    }

    protected function recordNodes($node, $config)
    {
        $id = $config['id'] ?? null;
        if ($id !== null) {
            if (isset($this->nodes[$id])) {
                throw new UiIDExistException("ID $id is exists of UI");
            }
            $this->nodes[$id] = $node;
        } else {
            $this->nodes[] = $node;
        }
    }

    protected function buildSubMenu($parent, $menus)
    {
        foreach ($menus as $child) {
            if (is_array($child)) {
                if ($child['type'] == 'checkbox') {
                    $nm = self::$ui->menuAppendCheckItem($parent, $child['title']);
                } else {
                    $nm = self::$ui->menuAppendItem($parent, $child['title']);
                }
                $this->recordNodes($nm, $child);
                if (isset($child['childs'])) {
                    $this->buildSubMenu($nm, $child['childs']);
                }
                if (isset($child['click'])) {
                    self::$ui->menuItemOnClicked($nm, function ($menu, $win, $data) use ($child) {
                        $call = $child['click'];
                        $call($menu, $win, $child['callback_data']);
                    }, $this->win, 0);
                }
            } else if ($child == 'sep') {
                self::$ui->menuAppendSeparator($parent);
            }
        }
    }

    public function buttonClickCall($button, $eventFunc, $backfunc, $config)
    {
        self::$ui->$eventFunc($button, function ($button, $data) use ($backfunc, $config) {
            try {
                $call = $config['click'];
                $filename = null;
                if ($backfunc) {
                    $filename = self::$ui->$backfunc($this->win);
                }
                if ($call) {
                    $call($button, $config['callback_data'], $filename);
                }
            } catch (\Exception $e) {
                echo $e;
            } catch (\Error $e) {
                echo $e;
            }
        }, 0);
    }
    public function menu($menus)
    {
        foreach ($menus as  $item) {
            $nm = $this->newMenu($item['label']);
            $this->recordNodes($nm, $item);
            if (isset($item['childs'])) {
                $this->buildSubMenu($nm, $item['childs']);
            }
        }
    }

    public function input($config)
    {
        $type = $config['type'] ?? null;
        switch ($config['type']) {
            case 'password':
                $entry = self::$ui->newPasswordEntry();
                break;
            case 'search':
                $entry = self::$ui->newSearchEntry();
                break;
            case 'textarea':
                $entry = $config['wrap'] ? self::$ui->newMultilineEntry() : self::$ui->newNonWrappingMultilineEntry();
                break;
            case 'radio':
                $entry = self::$ui->newRadioButtons();
                if (isset($config['value'])) {
                    foreach ($config['value'] as $label) {
                        self::$ui->radioButtonsAppend($entry, $label);
                    }
                }
                if(isset($config['click'])) {
                    $this->buttonClickCall($entry, 'radioButtonsOnSelected', null, $config);
                }
            case 'text':
            default:
                $entry = self::$ui->newEntry();
        }
        $this->recordNodes($entry, $config);
        if ($config['readonly']) {
            self::$ui->entrySetReadOnly($entry, $config['readonly']);
        }
    }

    public function buttonText($button, $text = null)
    {
        if ($text === null) {
            return self::$ui->buttonText($button);
        }
        return self::$ui->buttonSetText($button, $text);
    }

    public function radioSelect($radio, int $selected = -1) {
        if($selected < 0) {
            return self::$ui->radioButtonsSelected($radio);
        }
        return self::$ui->radioButtonsSetSelected($radio, $selected);
    }

    public function button($config)
    {

        $type = $config['type'] ?? null;
        $config['click'] = $config['click'] ?? null;
        $config['callback_data'] = $config['callback_data'] ?? null;

        switch ($type) {
            case 'file':
                $button = self::$ui->newButton($config['title']);
                $this->buttonClickCall($button, 'buttonOnClicked', 'openFile', $config);
                break;
            case 'save':
                $button = self::$ui->newButton($config['title']);
                $this->buttonClickCall($button, 'buttonOnClicked', 'saveFile', $config);
                break;
            case 'font':
                $button = self::$ui->newFontButton();
                if ($config['click']) {
                    $this->buttonClickCall($button, 'fontButtonOnChanged', null, $config);
                }
                break;
            case 'color':
                $button = self::$ui->newColorButton();
                if ($config['click']) {
                    $this->buttonClickCall($button, 'fontButtonOnChanged', null, $config);
                }
            case 'button':
            default:
                $button = self::$ui->newButton($config['title']);
                if ($config['click']) {
                    $this->buttonClickCall($button, 'buttonOnClicked', null, $config);
                }
        }

        $this->recordNodes($button, $config);
        return $button;
    }

    protected function nodeAppend($parent, $funcName, $config, $hasOption = true)
    {
        if (empty($config['childs'])) {
            return;
        }
        foreach ($config['childs'] as $tag => $sub) {
            $subNode = $this->createItem($tag, $sub);
            if ($hasOption) {
                $stretchy = empty($sub['fit']) ? 0 : 1;
                self::$ui->$funcName($parent, $subNode, $stretchy);
            } else {
                self::$ui->$funcName($parent, $subNode);
            }
        }
    }

    protected function boxAppend($parent, $config)
    {
        $this->nodeAppend($parent, 'boxAppend', $config);
    }

    protected function formAppend($parent, $config)
    {
        $this->nodeAppend($parent, 'formAppend', $config);
    }

    protected function groupAppend($parent, $config)
    {
        $this->nodeAppend($parent, 'groupSetChild', $config, false);
    }

    protected function gridAppend($parent, $config)
    {
        if (empty($config['childs'])) {
            return;
        }
        foreach ($config['childs'] as $tag => $sub) {
            $subNode = $this->createItem($tag, $sub);
            self::$ui->gridAppend(
                $parent,
                $subNode,
                $sub['left'],
                $sub['top'],
                $sub['width'],
                $sub['height'],
                $sub['hexpand'] ?? 0,
                $sub['halgin'],
                $sub['vexpand'] ?? 0,
                $sub['valign']
            );
        }
    }

    public function getUINode($id)
    {
        return $this->recordNodes[$id];
    }

    public function transformEventData($data)
    {
        if ($data['type'] === 'node') {
            return $this->getUINode($data['value']);
        }
        return $data['value'];
    }

    public function createItem($name, $config)
    {
        switch ($name) {
            case 'button':
                return $this->button($config);
            case 'vbox':
                $vbox =  self::$ui->newVerticalBox();
                $this->recordNodes($vbox, $config);
                self::$ui->boxSetPadded($vbox, $config['padded']);
                $this->boxAppend($vbox, $config);
                return $vbox;
            case 'hbox':
                $hbox = self::$ui->newHorizontalBox();
                $this->recordNodes($hbox, $config);
                self::$ui->boxSetPadded($hbox, $config['padded']);
                $this->boxAppend($hbox, $config);
                return $hbox;
            case 'group':
                $group = self::$ui->newGroup($config['title']);
                $this->recordNodes($group, $config);
                self::$ui->groupSetMargined($group, $config['margin']);
                $this->groupAppend($group, $config);
                return $group;
            case 'checkbox':
                return self::$ui->newCheckbox($config['title']);
            case 'label':
                return self::$ui->newLabel($config['title']);
            case 'hr':
                return self::$ui->newHorizontalSeparator();
            case 'vr':
                return self::$ui->newVerticalSeparator();
            case 'input':
                return $this->input($config);
            case 'form':
                $form = $this->newForm();
                $this->recordNodes($form, $config);
                self::$ui->formSetPadded($form, $config['padded']);
                $this->formAppend($form, $config);
                return $form;
            case 'grid':
                $grid = self::$ui->newGrid();
                $this->recordNodes($grid, $config);
                self::$ui->gridSetPadded($grid, $config['padded']);
                $this->gridAppend($grid, $config);
                return $grid;
            default:
                return null;
        }
    }
}

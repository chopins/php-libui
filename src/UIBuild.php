<?php

class UIBuild
{
    public static $ui;
    protected $nodes = [];
    protected $win = null;
    public static $defWinWidth = 800;
    public static $defWinHeight = 640;
    public static $defWinTitle = 'No Win Title';
    public function __construct(UI $ui, array $config)
    {
        if (!isset($config['body']) || !is_array($config['body'])) {
            throw new \Exception('UI config must has \'body\' key and it is array');
        }
        self::$ui = $ui;
        $hasMenu = 0;
        if (isset($config['menu'])) {
            $hasMenu = 1;
            $this->menu($config['menu']);
        }
        $this->window($config, $hasMenu);
        foreach ($config['body'] as $tagName => $item) {
            $this->createItem($tagName, $item);
        }
    }

    public function getBodyTags()
    {
        return ['button', 'tab', 'text', 'checkbox', 'label', 'select', 'file', 'radio'];
    }

    public function getWin()
    {
        return $this->win;
    }

    public function window($config, $hasMenu)
    {
        $err = self::$ui->init();
        if ($err) {
            throw new ErrorException($err);
        }
        $config['title'] = $config['title'] ?? self::$defWinTitle;
        $config['width'] = $config['width'] ?? self::$defWinWidth;
        $config['height'] = $config['height'] ?? self::$defWinHeight;
        $this->win = self::$ui->newWindow($config['title'], $config['width'], $config['height'], $hasMenu);
        if (isset($config['border'])) {
            self::$ui->windowSetBorderless($this->win, $config['border']);
        }
        if (isset($config['margin'])) {
            self::$ui->windowSetMargined($config['margin']);
        }
        if (isset($config['quit'])) {
            $this->bindEvent('quit', 'onShouldQuit', $config);
        }
        if (isset($config['close'])) {
            $this->bindEvent('close', 'windowOnClosing', $config);
        }
        if(isset($config['resize'])) {
            $this->bindEvent('resize', 'windowOnContentSizeChanged', $config);
        }
        return $this->win;
    }

    public function bindEvent($event, $func, $config)
    {
        self::$ui->$func(function (...$params) use ($config, $event) {
            try {
                $call = $config[$event];
                $config['callback_data'] = $config['callback_data'] ?? null;
                array_pop($params);
                $params[] = $config['callback_data'];
                call_user_func_array($call, $params);
            } catch (\Exception $e) {
                echo $e;
            } catch (\Error  $e) {
                echo $e;
            }
        }, 0);
    }

    public function winTitle($title = null)
    {
        if ($title === null) {
            return self::$ui->windowTitle($this->win);
        }
        self::$ui->windowSetTitle($title);
    }

    public function winBorder($border = null)
    {
        if ($border === null) {
            return self::$ui->windowBorderless($this->win);
        }
        self::$ui->windowSetBorderless($this->win, $border);
    }

    public function winMargin($margin = null)
    {
        if ($margin === null) {
            return self::$ui->windowMargined();
        }
        self::$ui->windowSetMargined($margin);
    }

    public function setChild($child)
    {
        self::$ui->windowSetChild($this->win, $child);
    }

    public function winSize($size = null)
    {
        if ($size === null) {
            $w = self::$ui->ffi()->new('int*');
            $h = self::$ui->ffi()->new('int*');
            self::$ui->uiWindowContentSize($this->win, $w, $h);
            return ['w' => $w->cdata, 'h' => $h->cdata];
        }
        self::$ui->windowSetContentSize($this->win, $size['w'], $size['h']);
    }
    public function fullscreen($isFull = null)
    {
        if ($isFull === null) {
            return self::$ui->windowFullscreen($this->win);
        }
        return self::$ui->windowSetFullscreen($this->win, $isFull);
    }

    protected function newControl($node, $tag, $config)
    {
        $control =  new Control(self::$ui, $node, $config);
        $control->setType($tag);
        $id = $config['id'] ?? $control->getHandle();

        if (isset($this->nodes[$id])) {
            throw new UiIDExistException("ID $id is exists of UI");
        }
        $control->setId($id);
        $this->nodes[$id] = $control;
        return $control;
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
                $this->newControl($nm, 'menu_item', $child);
                if (isset($child['childs'])) {
                    $this->buildSubMenu($nm, $child['childs']);
                }
                if (isset($child['click'])) {
                    $this->bindEvent('click', 'menuItemOnClicked', $child);
                }
            } else if ($child == 'hr') {
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
            $this->newControl($nm, 'menu', $item);
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
                if (isset($config['click'])) {
                    $this->buttonClickCall($entry, 'radioButtonsOnSelected', null, $config);
                }
            case 'text':
            default:
                $entry = self::$ui->newEntry();
        }
        $this->newControl($entry, 'input', $config);
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

    public function radioSelect($radio, int $selected = -1)
    {
        if ($selected < 0) {
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

        $this->newControl($button, 'button', $config);
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
        return $this->nodes[$id];
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
                $node =  self::$ui->newVerticalBox();
                self::$ui->boxSetPadded($node, $config['padded']);
                $this->boxAppend($node, $config);
                break;
            case 'hbox':
                $node = self::$ui->newHorizontalBox();
                self::$ui->boxSetPadded($node, $config['padded']);
                $this->boxAppend($node, $config);
                break;
            case 'group':
                $node = self::$ui->newGroup($config['title']);
                self::$ui->groupSetMargined($node, $config['margin']);
                $this->groupAppend($node, $config);
                break;
            case 'checkbox':
                $node = self::$ui->newCheckbox($config['title']);
                break;
            case 'label':
                $node = self::$ui->newLabel($config['title']);
                break;
            case 'hr':
                $node = self::$ui->newHorizontalSeparator();
                break;
            case 'vr':
                $node = self::$ui->newVerticalSeparator();
                break;
            case 'input':
                $node = $this->input($config);
                break;
            case 'form':
                $node = $this->newForm();
                self::$ui->formSetPadded($node, $config['padded']);
                $this->formAppend($node, $config);
                break;
            case 'grid':
                $node = self::$ui->newGrid();
                self::$ui->gridSetPadded($node, $config['padded']);
                $this->gridAppend($node, $config);
                break;
            case 'table':
                $node = self::$ui->newTable();
                break;
            case 'select':
                $node = self::$ui->newCombobox();
                break;
            default:
                throw new Exception("UI Control $name is invaild");
        }
        return $this->newControl($node, $name, $config);
    }
}

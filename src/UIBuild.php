<?php

namespace UI;

use UI\Control\Button;
use UI\Control\Input;
use UI\Control\Menu;
use UI\Control\Window;
use UI\UI;

class UIBuild
{
    /**
     * @var UI\UI
     */
    protected static $ui = null;
    protected $nodes = [];
    protected $win = null;

    public function __construct(UI $ui, array $config)
    {
        if (!isset($config['body']) || !is_array($config['body'])) {
            throw new \Exception('UI config must has \'body\' key and it is array');
        }
        if (is_null(self::$ui)) {
            self::$ui = $ui;
        }
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

    public function getUI()
    {
        return self::$ui;
    }

    public function appendNodes($node, $id)
    {
        $this->nodes[$id] = $node;
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
        $config['hasMenu'] = $hasMenu;
        $w = new Window($this, $config);
        $this->win = $w->getUIInstance();
        return $this->win;
    }


    public function menu($menus)
    {
        return new Menu($this, $menus);
    }

    public function input($config)
    {
        return new Input($this, $config);
    }


    public function button($config)
    {
        return new Button($this, $config);
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

    public function createItem($name, $config = [])
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
            default:
                throw new Exception("UI Control $name is invaild");
        }
    }
}

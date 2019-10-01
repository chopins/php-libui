<?php

namespace UI;

use UI\Control\Box;
use UI\Control\Button;
use UI\Control\Form;
use UI\Control\Grid;
use UI\Control\Group;
use UI\Control\Input;
use UI\Control\Menu;
use UI\Control\Table;
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
            $item['parent'] = $this->win;
            $control = $this->createItem($tagName, $item);
            $this->win->setChild($control);
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
        $this->win = new Window($this, $config);
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
                return new Button($this, $config);
            case 'box':
                return new Box($this, $config);
            case 'group':
                return new Group($this, $config);
            case 'label':
                return new Label($this, $config);
            case 'hr':
                return self::$ui->newHorizontalSeparator();
            case 'vr':
                return self::$ui->newVerticalSeparator();
            case 'input':
                return new Input($this, $config);
            case 'form':
                return new Form($this, $config);
            case 'grid':
                return new Grid($this, $config);
            case 'table':
                return new Table($this, $config);
            case 'tab':
                $node = self::$ui -> newTab();
                break;
            case 'img':
                break;
            default:
                throw new Exception("UI Control name $name is invaild");
        }
    }
}

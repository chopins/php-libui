<?php

namespace UI;

use UI\Control\Box;
use UI\Control\Button;
use UI\Control\Form;
use UI\Control\Grid;
use UI\Control\Group;
use UI\Control\Input;
use UI\Control\Menu;
use UI\Control\Separator;
use UI\Control\Table;
use UI\Control\Tab;
use UI\Control\Window;
use UI\UI;

class UIBuild
{
    /**
     * @var \UI\UI
     */
    protected static $ui = null;
    protected $nodes = [];

    /**
     * @var \UI\Control\Window
     */
    protected $win = null;

    public function __construct(UI $ui, array $config)
    {
        if (!isset($config['body']) || !is_array($config['body'])) {
            throw new \Exception('UI config must has \'body\' key and it is array');
        }
        if (is_null(self::$ui)) {
            self::$ui = $ui;
        }
        $err = self::$ui->init();
        if ($err) {
            throw new ErrorException($err);
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

    public function show()
    {
        $this->win->show();
        self::$ui->main();
    }
    public function menu($config)
    {
        foreach ($config as $menu) {
            new Menu($this, $menu);
        }
    }

    public function getUI()
    {
        return self::$ui;
    }

    public function appendNodes(Control $control)
    {
        $id = $control->getAttr('id') ?? $control->getHandle();
        $this->nodes[$id] = $control;
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


    public function getUINode($id): Control
    {
        return $this->nodes[$id];
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
                return new Separator($this, ['type' => 'hr']);
            case 'vr':
                return new Separator($this, ['type' => 'vr']);
            case 'input':
                return new Input($this, $config);
            case 'form':
                return new Form($this, $config);
            case 'grid':
                return new Grid($this, $config);
            case 'table':
                return new Table($this, $config);
            case 'tab':
                return new Tab($this, $config);
            case 'img':
                return new Img($this, $config);
            default:
                throw new Exception("UI Control name $name is invaild");
        }
    }
}

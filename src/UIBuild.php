<?php

namespace UI;

use UI\Control\Area;
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
use UI\Control\Datetime;
use UI\Control\Progress;
use UI\UI;
use ErrorException;

class UIBuild
{
    /**
     * @var \UI\UI
     */
    protected static UI $ui = null;
    protected array $controls = [];
    protected array $controlsName = [];
    protected array $handles = [];

    /**
     * @var \UI\Control\Window
     */
    protected Window $win = null;

    public function __construct(UI $ui, array $config = [])
    {
        if (is_null(self::$ui)) {
            self::$ui = $ui;
        }
        if ($config) {
            $this->createMainWin($config);
        }
    }

    public function createMainWin(array $config)
    {
        if (!isset($config['body']) || !is_array($config['body'])) {
            throw new \Exception('UI config must has \'body\' key and it is array');
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
        foreach ($config['body'] as $item) {
            $control = $this->createItem($item['name'], $item['attr']);
            $this->win->addChild($control);
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

    public function getUI(): UI
    {
        return self::$ui;
    }

    public function appendControl(Control $control)
    {
        $handle = $control->getHandle();
        $this->handles[$handle] = $control;
        $id = $control->getAttr('id') ?? $handle;
        $this->controls[$id] = $control;
        $name = $control::CTL_NAME;
        if ($name == 'sep') {
            $name = $control->getAttr('type');
        }
        if (isset($this->controlsName[$name])) {
            $this->controlsName[$name][] = $control;
        } else {
            $this->controlsName[$name] = [$control];
        }
    }

    public function getBodyTags(): array
    {
        return ['button', 'tab', 'text', 'checkbox', 'label', 'select', 'file', 'radio'];
    }

    /**
     * @return \UI\Control\Window
     */
    public function getWin(): Window
    {
        return $this->win;
    }

    /**
     *
     * @param array $config
     * @param bool  $hasMenu
     * @return \UI\Control\Window
     */
    public function window($config, $hasMenu): Window
    {
        $config['hasMenu'] = $hasMenu;
        $this->win = new Window($this, $config);
        return $this->win;
    }

    public function getControlById($id): Control
    {
        return $this->controls[$id];
    }

    public function getControlByName($name): array
    {
        return $this->controlsName[$name] ?? [];
    }

    public function getControlByHandle($handle): Control
    {
        return $this->handles[$handle];
    }

    public function createItem($name, $config = []): Control
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
            case 'progress':
                return new Progress($this, $config);
            case 'datetime':
                return new Datetime($this, $config);
            case 'canvas':
                return new Area($this, $config);
            default:
                throw new Exception("UI Control name $name is invaild");
        }
    }

}

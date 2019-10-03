<?php

namespace UI\Control;

use UI\Control;

class Table extends Control
{
    public function newControl()
    {
        $this->instance = self::$ui->newTab();
    }

    public function pushChilds()
    {
        $this->attr['page'] = $this->attr['page'] ?? [];
        foreach ($this->attr['page'] as $pageName => $childs) {
            foreach ($childs as $cname => $config) {
                $control = $this->build->createItem($cname, $config);
                $this->appendPage($pageName, $control);
            }
        }
    }
    public function appendPage($pageName, Control $childs)
    {
        $ui = $childs->getUIInstance();
        $this->tabAppend($pageName, $ui);
    }

    public  function insertPage($name, Control $childs)
    {
        $ui = $childs->getUIInstance();
        $this->tabInsertAt($name, $ui);
    }

    public  function deletePage($page)
    {
        $this->tabDelete($page);
    }

    public function getPageMargin($page)
    {
        return $this->tabMargined($page);
    }

    public function numPages()
    {
        return $this->tabNumPages();
    }

    public function setPageMargin($page, $margin)
    {
        $this->tabSetMargined($page, $margin);
    }
}

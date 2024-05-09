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

use UI\Control;
use FFI\CData;

/**
 * @property-read array $page
 */
class Tab extends Control
{
    const CTL_NAME = 'tab';

    public function newControl(): CData
    {
        return self::$ui->newTab();
    }

    public function pushChilds()
    {
        $this->attr['page'] = $this->attr['page'] ?? [];
        foreach ($this->attr['page'] as $pageName => $childs) {
            foreach ($childs as $config) {
                $control = $this->build->createItem($config);
                $this->appendPage($pageName, $control);
            }
        }
    }

    public function appendPage($pageName, Control $childs)
    {
        $ui = $childs->getUIInstance();
        $this->tabAppend($pageName, $ui);
    }

    public function insertPage($name, Control $childs)
    {
        $ui = $childs->getUIInstance();
        $this->tabInsertAt($name, $ui);
    }

    public function deletePage($page)
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

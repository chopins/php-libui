<?php

/**
 * php-libui (http://toknot.com)
 *
 * @copyright  Copyright (c) 2019 Szopen Xiao (Toknot.com)
 * @license    http://toknot.com/LICENSE.txt New BSD License
 * @link       https://github.com/chopins/php-libui
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

    protected function newControl(): CData
    {
        return self::$ui->newTab();
    }

    protected function prepareOption()
    {
        $childs = $this->attr['childs'] ?? [];
        $this->attr['childs'] = [];
        foreach ($childs as $page) {
            foreach ($page['childs'] as $config) {
                $config['pagename'] = $page['title'];
                $this->attr['childs'][] = $config;
            }
        }
    }

    protected function addChild(Control $child, $options = [])
    {
        $this->appendPage($options['pagename'], $child);
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

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
 * @property-read array $th
 * @property-read array $tbody
 * @property-read int $rowBgcolor
 * @property-read array $change
 */
class Table extends Control
{
    const CTL_NAME = 'table';
    const DEF_FCOLOR = -1;

    protected $model = null;
    protected $modelHandle = null;
    protected $rowNum = 0;
    protected $columnNum = 0;
    protected $columnTypeList = [];
    protected $imgsList = [];

    public function newControl(): CData
    {
        $this->columnNum = count($this->attr['th']);
        $this->rowNum = count($this->attr['tbody']);
        $this->initModelHandler();
        $this->model = self::$ui->newTableModel(self::$ui->addr($this->modelHandle));
        $param = self::$ui->new('uiTableParams');
        $param->Model = $this->model;
        $param->RowBackgroundColorModelColumn = $this->attr['rowBgcolor'] ?? self::DEF_FCOLOR;
        $this->initColumnType();
        return self::$ui->newTable(self::$ui->addr($param));
    }

    public function pushChilds()
    {
        foreach ($this->attr['th'] as $index => $config) {
            $idx = $config['idx'] ?? $index;
            $editable = $config['editable'] ?? false;
            $textColor = $config['textColor'] ?? self::DEF_FCOLOR;
            $this->addColumn($config['title'], $idx, $config['type'], $editable, $textColor);
        }
    }

    public function initColumnType()
    {
        foreach ($this->attr['th'] as $index => $config) {
            $idx = $config['idx'] ?? $index;
            switch ($config['type']) {
                case 'button':
                    $this->columnTypeList[$idx] = self::$ui::TABLE_VALUE_TYPE_STRING;
                    break;
                case 'image':
                    $this->columnTypeList[$idx] = self::$ui::TABLE_VALUE_TYPE_IMAGE;
                    break;
                case 'imgtext':
                    $this->columnTypeList[$idx[0]] = self::$ui::TABLE_VALUE_TYPE_IMAGE;
                    $this->columnTypeList[$idx[1]] = self::$ui::TABLE_VALUE_TYPE_STRING;
                    break;
                case 'progress':
                    $this->columnTypeList[$idx] = self::$ui::TABLE_VALUE_TYPE_INT;
                    break;
                case 'checkbox':
                    $this->columnTypeList[$idx] = self::$ui::TABLE_VALUE_TYPE_INT;
                    break;
                case 'checkboxtext':
                    if (is_array($idx)) {
                        $this->columnTypeList[$idx[0]] = self::$ui::TABLE_VALUE_TYPE_INT;
                        $this->columnTypeList[$idx[1]] = self::$ui::TABLE_VALUE_TYPE_STRING;
                    } else {
                        $this->columnTypeList[$idx] = self::$ui::TABLE_VALUE_TYPE_INT;
                    }
                    break;
                case 'color':
                    $this->columnTypeList[$idx] = self::$ui::TABLE_VALUE_TYPE_COLOR;
                    break;
                case 'text':
                default:
                    $this->columnTypeList[$idx] = self::$ui::TABLE_VALUE_TYPE_STRING;
            }
        }
    }

    public function initModelHandler()
    {
        $this->modelHandle = self::$ui->new('uiTableModelHandler');
        $this->modelHandle->NumColumns = [$this, 'columnNumCall'];
        $this->modelHandle->ColumnType = [$this, 'columnTypeCall'];
        $this->modelHandle->NumRows = [$this, 'rowNumCall'];
        $this->modelHandle->CellValue = [$this, 'onGetCellValue'];
        $this->modelHandle->SetCellValue = [$this, 'onSetCellValue'];
    }

    public function addRow($rowData)
    {
        $this->attr['tbody'][] = $rowData;
        $this->rowChanged($this->rowNum);
        $this->rowInserted();
    }
    public function updateRowColumValue($row, $col, $value)
    {
        $this->attr['tbody'][$row][$col] = $value;
    }

    public function setColumAllValue($col, $value)
    {
        foreach ($this->attr['tbody'] as &$row) {
            $row[$col] = $value;
        }
    }
    public function setRowAllValue($row, array $values)
    {
        $this->attr['tbody'][$row] = $values;
    }

    public function rowChanged($row)
    {
        self::$ui->tableModelRowChanged($this->model, $row);
    }

    public function rowInserted()
    {
        self::$ui->tableModelRowInserted($this->model, $this->rowNum);
        $this->rowNum++;
    }

    public function rowDeleted($row)
    {
        self::$ui->tableModelRowDeleted($this->model, $this->rowNum);
        $this->rowNum--;
    }

    public function rowNumCall($mh, $tm)
    {
        return $this->rowNum;
    }

    public function columnNumCall($mh, $tm)
    {
        return $this->columnNum;
    }

    public function columnTypeCall($mh, $tm, $col)
    {
        return $this->columnTypeList[$col];
    }

    public function onGetCellValue($tableModelHandler, $tableModel, $row, $col)
    {
        $rowColData = $this->attr['tbody'][$row][$col];
        switch ($this->columnTypeList[$col]) {
            case self::$ui::TABLE_VALUE_TYPE_IMAGE:
                $imgsrc = $rowColData['src'];
                $key = $imgsrc;
                $imgConfig = ['src' => $imgsrc,];
                if (isset($rowColData['width'])) {
                    $imgConfig['width'] = $rowColData['width'];
                    $key .= $imgConfig['width'] . 'x';
                }
                if (isset($rowColData['height'])) {
                    $imgConfig['height'] = $rowColData['height'];
                    $key .= $imgConfig['height'];
                }

                $imgId = \md5($key);
                if (isset($this->imgsList[$imgId])) {
                    $img = $this->imgsList[$imgId];
                } else {
                    $img = new Img($this->build, $imgConfig);
                    $this->imgsList[$imgId] = $img;
                }
                $val = self::$ui->newTableValueImage($img->getUIInstance());
                break;
            case self::$ui::TABLE_VALUE_TYPE_INT:
                $rowColData = (int) $rowColData;
                $val = self::$ui->newTableValueInt($rowColData);
                break;
            case self::$ui::TABLE_VALUE_TYPE_COLOR:
                $val = self::$ui->newTableValueColor($rowColData['r'], $rowColData['g'], $rowColData['b'], $rowColData['a']);
                break;
            case self::$ui::TABLE_VALUE_TYPE_STRING:
            default:
                if (!is_string($rowColData)) {
                    $rowColData = (string) $rowColData;
                }
                $val = self::$ui->newTableValueString($rowColData);
                break;
        }
        return $val;
    }

    public function onSetCellValue($tableModelHandler, $tableModel, $row, $col, $val)
    {
        $value = null;
        switch ($this->columnTypeList[$col]) {
            case self::$ui::TABLE_VALUE_TYPE_INT:
                $value = self::$ui->tableValueInt($val);
                break;
            case self::$ui::TABLE_VALUE_TYPE_IMAGE:
                $value = self::$ui->tableValueImage($val);
                break;
            case self::$ui::TABLE_VALUE_TYPE_COLOR:
                $r = self::$ui->new('double*');
                $g = self::$ui->new('double*');
                $b = self::$ui->new('double*');
                $a = self::$ui->new('double*');
                self::$ui->tableValueColor($val, $r, $g, $b, $a);
                $value = ['red' => $r[0], 'green' => $g[0], 'blue' => $b[0], 'alpha' => $a[0]];
                break;
            case self::$ui::TABLE_VALUE_TYPE_STRING:
                if ($val) {
                    $value = self::$ui->tableValueString($val);
                }
                break;
        }
        if (isset($this->attr['change'][$col])) {
            $callable = $this->attr['change'][$col];
            $callable($this, $row, $col, $value);
        }
    }

    public function addColumn(string $title, int $colIdx, string $type = 'text', bool $editable = false, $textColor = self::DEF_FCOLOR)
    {
        $editable = $editable ? self::$ui::TABLE_MODEL_COLUMN_ALWAYS_EDITABLE : self::$ui::TABLE_MODEL_COLUMN_NEVER_EDITABLE;
        $tp = $tpPtr = null;
        if ($textColor >= 0) {
            $tp = self::$ui->new('uiTableTextColumnOptionalParams');
            $tp->ColorModelColumn = $textColor;
            $tpPtr = self::$ui->addr($tp);
        }
        switch ($type) {
            case 'button':
                $this->tableAppendButtonColumn($title, $colIdx, $editable);
                break;
            case 'image':
                $this->tableAppendImageColumn($title, $colIdx);
                break;
            case 'imgtext':
                $this->tableAppendImageTextColumn($title, $colIdx[0], $colIdx[1], $editable, $tpPtr);
                break;
            case 'progress':
                $this->tableAppendProgressBarColumn($title, $colIdx);
                break;
            case 'checkbox':
                $this->tableAppendCheckboxColumn($title, $colIdx, $editable);
                break;
            case 'checkboxtext':
                if (is_array($colIdx)) {
                    $this->tableAppendCheckboxTextColumn(
                        $title,
                        $colIdx[0],
                        $editable[0],
                        $colIdx[1],
                        $editable[1],
                        $tpPtr
                    );
                } else {
                    $this->tableAppendCheckboxTextColumn(
                        $title,
                        $colIdx,
                        $editable,
                    );
                }
                break;
            case 'color':
                break;
            case 'text':
            default:
                $this->tableAppendTextColumn($title, $colIdx, $editable, $tpPtr);
        }
    }
}

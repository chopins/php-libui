<?php

namespace UI\Control;

use UI\Control;
use FFI\CData;

class Table extends Control
{
    protected $model = null;
    protected $modelHandle = null;
    protected $rowNum = 0;
    protected $columnNum = 0;
    protected $columnTypeList = [];
    protected $imgsList = [];
    const DEF_FCOLOR = -1;
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
            $id = $config['idx'] ?? $index;
            switch ($config['type']) {
                case 'button':
                    $this->columnTypeList[$id] = self::$ui::TABLE_VALUE_TYPE_STRING;
                    break;
                case 'image':
                    $this->columnTypeList[$id] = self::$ui::TABLE_VALUE_TYPE_IMAGE;
                    break;
                case 'imgtext':
                    $this->columnTypeList[$id[0]] = self::$ui::TABLE_VALUE_TYPE_IMAGE;
                    $this->columnTypeList[$id[1]] = self::$ui::TABLE_VALUE_TYPE_STRING;
                    break;
                case 'progress':
                    $this->columnTypeList[$id] = self::$ui::TABLE_VALUE_TYPE_INT;
                    break;
                case 'checkbox':
                    $this->columnTypeList[$id] = self::$ui::TABLE_VALUE_TYPE_INT;
                    break;
                case 'checkboxtext':
                    if (is_array($id)) {
                        $this->columnTypeList[$id[0]] = self::$ui::TABLE_VALUE_TYPE_INT;
                        $this->columnTypeList[$id[1]] = self::$ui::TABLE_VALUE_TYPE_STRING;
                    } else {
                        $this->columnTypeList[$id] = self::$ui::TABLE_VALUE_TYPE_INT;
                    }
                    break;
                case 'color':
                    $this->columnTypeList[$id] = self::$ui::TABLE_VALUE_TYPE_COLOR;
                    break;
                case 'text':
                default:
                    $this->columnTypeList[$id] = self::$ui::TABLE_VALUE_TYPE_STRING;
            }
        }
    }

    public function initModelHandler()
    {
        $this->modelHandle = self::$ui->new('uiTableModelHandler');
        $this->modelHandle->NumColumns = [$this, 'columnNumCall'];
        $this->modelHandle->ColumnType = [$this, 'columnTypeCall'];
        $this->modelHandle->NumRows = [$this, 'rowNumCall'];
        $this->modelHandle->CellValue = [$this, 'cellValueCall'];
        $this->modelHandle->SetCellValue = [$this, 'setCellValueCall'];
    }

    public function changeRow($row)
    {
        self::$ui->tableModelRowChanged($this->model, $row);
    }

    public function insertRow($new)
    {
        self::$ui->tableModelRowInserted($this->model, $new);
    }

    public function deleteRow($row)
    {
        self::$ui->tableModelRowDeleted($this->model, $row);
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
    public function cellValueCall($mh, $tm, $row, $col)
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
                $val =  self::$ui->newTableValueString($rowColData);
                break;
        }
        return $val;
    }
    public function setCellValueCall($mh, $tm, $row, $col, $val)
    {
        $callback = $this->attr['change'][$row][$col];
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
            default:
                $value = self::$ui->tableValueString($val);
                break;
        }
        $callback($mh, $tm, $row, $col, $value);
    }

    public function setModelHandle($key, $callable)
    {
        $key = ucfirst($key);
        $this->modelHandle->$key = $callable;
    }

    public function addTh($title, $id, $type = 'text', $editable = false, $textColor = self::DEF_FCOLOR)
    {
        $this->addColumn($title, $id, $type, $editable, $textColor);
    }

    public function addColumn(string $title, int $id, string $type = 'text', bool $editable = false, $textColor = self::DEF_FCOLOR)
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
                $this->tableAppendButtonColumn($title, $id, $editable);
                break;
            case 'image':
                $this->tableAppendImageColumn($title, $id);
                break;
            case 'imgtext':
                $this->tableAppendImageTextColumn($title, $id[0], $id[1], $editable, $tpPtr);
                break;
            case 'progress':
                $this->tableAppendProgressBarColumn($title, $id);
                break;
            case 'checkbox':
                $this->tableAppendCheckboxColumn($title, $id, $editable);
                break;
            case 'checkboxtext':
                if (is_array($id)) {
                    $this->uiTableAppendCheckboxTextColumn(
                        $title,
                        $id[0],
                        $editable[0],
                        $id[1],
                        $editable[1],
                        $tpPtr
                    );
                } else {
                    $this->uiTableAppendCheckboxTextColumn(
                        $title,
                        $id,
                        $editable,
                    );
                }
                break;
            case 'color':
                break;
            case 'text':
            default:
                $this->tableAppendTextColumn($title, $id, $editable, $tpPtr);
        }
    }
}

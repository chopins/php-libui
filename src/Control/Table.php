<?php

namespace UI\Control;

use UI\Control;

class Table extends Control
{
    protected $model = null;
    protected $modelHandle = null;
    protected $rowNum = 0;
    protected $columnNum = 0;
    protected $columnTypeList = [];
    protected $imgsList = [];
    public function newControl()
    {
        $this->columnNum = count($this->attr['th']);
        $this->rowNum = count($this->attr['tbody']);
        $this->initModelHandler();
        $this->model = self::$ui->newTableModel(self::$ui->addr($this->modelHandle));
        $param = self::$ui->new('uiTableParams');
        $param->Model = $this->model;
        $param->RowBackgroundColorModelColumn = $this->attr['rowBgcolor'];
        $this->instance = self::$ui->newTable(self::$ui->addr($param));
    }

    public function initModelHandler()
    {
        $this->modelHandle = self::$ui->new('uiTableModelHandler');
        $this->modelHandle->NumColumns = [$this, 'returnColumnNum'];
        $this->modelHandle->ColumnType = [$this, 'returnColumnType'];
        $this->modelHandle->NumRows = [$this, 'returnRowNum'];
        $this->modelHandle->CellValue = [$this, 'returnCellValue'];
        $this->modelHandle->SetCellValue = [$this, 'returnSetCellValue'];
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

    public function returnRowNum($mh, $tm)
    {
        return $this->rowNum;
    }
    public function returnColumnNum($mh, $tm)
    {
        return $this->columnNum;
    }

    public function returnColumnType($mh, $tm, $col)
    {
        return $this->columnTypeList[$col];
    }
    public function returnCellValue($mh, $tm, $row, $col)
    {
        $rowColData = $this->attr['tbody'][$row][$col];
        switch ($this->columnTypeList[$col]) {
            case self::$ui::TABLE_VALUE_TYPE_STRING:
                return self::$ui->newTableValueString($rowColData);
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
                $img = new Img($this->build, $imgConfig);
                $imgId = \md5($key);
                if (isset($this->imgsList[$imgId])) {
                    return $this->imgsList[$imgId];
                }
                $this->imgsList[$imgId] = $img;
                return self::$ui->newTableValueImage($img->getUIInstance());
            case self::$ui::TABLE_VALUE_TYPE_INT:
                return self::$ui->newTableValueInt($rowColData);
            case self::$ui::TABLE_VALUE_TYPE_COLOR:
                return self::$ui->newTableValueColor($rowColData['r'], $rowColData['g'], $rowColData['b'], $rowColData['a']);
        }
    }
    public function returnSetCellValue($mh, $tm, $row, $col, $val)
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

    public function addTh($title, $id, $type = 'text', $editable = false, $textColor = -1)
    {
        $this->addColumn($title, $id, $type = 'text', $editable = false, $textColor);
    }

    public function addColumn($title, $id, $type = 'text', $editable = false, $textColor = -1)
    {
        $editable = $editable ? self::$ui::TABLE_MODEL_COLUMN_ALWAYS_EDITABLE : self::$ui::TABLE_MODEL_COLUMN_NEVER_EDITABLE;
        $tp = null;
        if ($textColor >= 0) {
            $tp = self::$ui->new('uiTableTextColumnOptionalParams');
            $tp->ColorModelColumn = $textColor;
            $tpPtr = self::$ui->addr($tp);
        }
        switch ($type) {
            case 'button':
                $this->columnTypeList[$id] = self::$ui::TABLE_VALUE_TYPE_STRING;
                $this->tableAppendButtonColumn($title, $id, $editable);
                break;
            case 'image':
                $this->columnTypeList[$id] = self::$ui::TABLE_VALUE_TYPE_IMAGE;
                $this->tableAppendImageColumn($title, $id);
                break;
            case 'imgtext':
                $this->columnTypeList[$id[0]] = self::$ui::TABLE_VALUE_TYPE_IMAGE;
                $this->columnTypeList[$id[1]] = self::$ui::TABLE_VALUE_TYPE_STRING;
                $this->tableAppendImageTextColumn($title, $id[0], $id[1], $editable, $tpPtr);
                break;
            case 'progress':
                $this->columnTypeList[$id] = self::$ui::TABLE_VALUE_TYPE_INT;
                $this->tableAppendProgressBarColumn($title, $id);
                break;
            case 'checkbox':
                $this->columnTypeList[$id] = self::$ui::TABLE_VALUE_TYPE_INT;
                $this->tableAppendCheckboxColumn($title, $id, $editable);
                break;
            case 'checkboxtext':
                if (is_array($id)) {
                    $this->columnTypeList[$id[0]] = self::$ui::TABLE_VALUE_TYPE_INT;
                    $this->columnTypeList[$id[1]] = self::$ui::TABLE_VALUE_TYPE_STRING;
                    $this->uiTableAppendCheckboxTextColumn(
                        $title,
                        $id[0],
                        $editable[0],
                        $id[1],
                        $editable[1],
                        $tpPtr
                    );
                } else {
                    $this->columnTypeList[$id] = self::$ui::TABLE_VALUE_TYPE_INT;
                    $this->uiTableAppendCheckboxTextColumn(
                        $title,
                        $id,
                        $editable,
                    );
                }
                break;
            case 'color':
                $this->columnTypeList[$id] = self::$ui::TABLE_VALUE_TYPE_COLOR;
                break;
            case 'text':
            default:
                $this->columnTypeList[$id] = self::$ui::TABLE_VALUE_TYPE_STRING;
                $this->tableAppendTextColumn($title, $id, $editable, $tpPtr);
        }
    }
}

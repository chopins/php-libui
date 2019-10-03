<?php
include __DIR__ . '/loadui.php';

main();
function main()
{
    global $ui;
    $config = [
        'title' => 'test',
        'width' => 600,
        'height' => 450,
        'menu' => [
            [
                'title' => 'File',
                'childs' => [
                    ['title' => 'New File'],
                    ['title' => 'Open File'],
                ]
            ],
            [
                'title' => 'Edit',
                'childs' => [
                    ['title' => 'Find']
                ]
            ]
        ],
        'body' => [
            'box' => [
                'dir' => 'v',
                'child_fit' => 1,
                'childs' => [
                    'table' => [
                        'th' => [
                            ['title' => 'Colum1', 'idx' => 0, 'type' => 'text'],
                            ['title' => 'Colum2', 'idx' => 1, 'type' => 'button'],
                            ['title' => 'Colum3', 'idx' => 2, 'type' => 'text'],
                        ],
                        'tbody' => [
                            [1, 'button0', 3],
                            [1, 'button1', 3],
                            [1, 'button2', 3]
                        ],
                    ]
                ]
            ]
        ]
    ];


    return $ui->build($config)->show();

    $ui->init();
    $mainwin = $ui->newWindow("libui Table", 640, 480, 1);

    $v1 = $ui->newTableValueString('test');
    $v2 = $ui->newTableValueInt(1);

    $handle = FFI::addr($ui->new('uiTableModelHandler'));

    $handle[0]->NumColumns = function ($uiTableModelHandler, $uiTableModel) {
        return 9;
    };
    $handle[0]->ColumnType = function ($uiTableModelHandler, $uiTableModel, $column) use ($ui) {
        if ($column == 3 || $column == 4)
            return $ui::TABLE_VALUE_TYPE_COLOR;
        if ($column == 5)
            return $ui::TABLE_VALUE_TYPE_IMAGE;
        if ($column == 7 || $column == 8)
            return $ui::TABLE_VALUE_TYPE_INT;
        return $ui::TABLE_VALUE_TYPE_STRING;
    };
    $handle[0]->NumRows = function ($uiTableModelHandler, $uiTableModel) {
        return 15;
    };

    $handle[0]->CellValue = function ($mh, $m, $row, $col) use ($ui) {

        if ($col == 3) {
            if ($row == -1)
                return $ui->newTableValueColor(1, 1, 0, 1);
            if ($row == 3)
                return $ui->newTableValueColor(1, 0, 0, 1);
            if ($row == 11)
                return $ui->newTableValueColor(0, 0.5, 1, 0.5);
            return NULL;
        }
        if ($col == 4) {
            if (($row % 2) == 1)
                return $ui->newTableValueColor(0.5, 0, 0.75, 1);
            return NULL;
        }
        if ($col == 5) {
            if ($row < 8)
                return $ui->newTableValueImage(img[0]);
            return $ui->newTableValueImage(img[1]);
        }
        if ($col == 7)
            return $ui->newTableValueInt(checkStates[$row]);
        if ($col == 8) {
            if ($row == 0)
                return $ui->newTableValueInt(0);
            if ($row == 13)
                return $ui->newTableValueInt(100);
            if ($row == 14)
                return $ui->newTableValueInt(-1);
            return $ui->newTableValueInt(50);
        }
        switch ($col) {
            case 0:
                sprintf(buf, "Row %d", $row);
                break;
            case 2:
                if ($row == 9)
                    return uiNewTableValueString(row9text);
                // fall through
            case 1:
                strcpy(buf, "Part");
                break;
            case 6:
                strcpy(buf, "Make Yellow");
                break;
        }
        return uiNewTableValueString(buf);
    };
    $handle[0]->SetCellValue = function ($uiTableModelHandler, $uiTableModel, $i, $j, $uiTableValue) { };

    $model =  $ui->newTableModel($handle);
    $ui->tableModelRowInserted($model,  1);

    $tableParam = FFI::addr($ui->new('uiTableParams'));

    $tableParam[0]->Model = $model;
    $tableParam[0]->RowBackgroundColorModelColumn = 0;

    $opParam = $ui->new('uiTableTextColumnOptionalParams');
    $opParam->ColorModelColumn = 4;

    $table = $ui->newTable($tableParam);

    $ui->tableAppendTextColumn($table, 'Column 1', 0, $ui::TABLE_MODEL_COLUMN_NEVER_EDITABLE, NULL);
    $ui->tableAppendTextColumn($table, 'Column 2', 2, $ui::TABLE_MODEL_COLUMN_ALWAYS_EDITABLE, NULL);
    $model1 =  $ui->newTableModel($handle);
    $ui->tableModelRowInserted($model1,  2);
    $ui->tableAppendTextColumn($table, 'Column 1', 0, 0, NULL);
    $ui->tableAppendButtonColumn($table, 'Button', 2, $ui::TABLE_MODEL_COLUMN_ALWAYS_EDITABLE);
    $vbox = $ui->newVerticalBox();
    $ui->boxAppend($vbox, $table, 1);
    $ui->windowSetChild($mainwin, $vbox);
    $ui->controlShow($mainwin);
    $ui->main();
}

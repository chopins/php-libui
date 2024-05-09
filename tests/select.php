<?php

include_once __DIR__.'/win.php';

function testControl(UI\UI $ui, UI\UIBuild $build) {
    $select = $build->createItem(['widget' => 'input', 'type' => 'select', 'option' => [
        'item1', 'item2'
    ]]);

    $select2 = $build->createItem(['widget'=>'input', 'type' => 'select', 'option' => [
        'item1', 'item2'
    ], 'editable' => 1]);
    return [$select, $select2];
}
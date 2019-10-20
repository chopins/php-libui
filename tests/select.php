<?php

include_once __DIR__.'/win.php';

function main(UI\UI $ui, UI\UIBuild $build) {
    $win = $build->getWin();
    $select = $build->createItem('input', ['type' => 'select', 'option' => [
        'item1', 'item2'
    ]]);
    $win->addChild($select);

    $select2 = $build->createItem('input', ['type' => 'select', 'option' => [
        'item1', 'item2'
    ], 'editable' => 1]);
    $win->addChild($select2);
}
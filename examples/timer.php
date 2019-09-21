<?php
include __DIR__ . '/loadui.php';

$e = $ui->new('uiMultilineEntry');
main();
function sayTime($data): int
{
    global $ui, $e;
    $s = date('D M d H:i:s Y');
    $ui->multilineEntryAppend($e, $s);
    return 1;
}

function onClosing($w, $data): int
{
    global $ui;
    $ui->quit();
    return 1;
}

function saySomething($b, $data)
{
    global $ui, $e;
    $ui->multilineEntryAppend($e, "Saying something\n");
}

function main(): int
{
    global $ui, $e;

    if ($ui->init() != NULL) {
        exit(6);
    }

    $w = $ui->newWindow("Hello", 320, 240, 0);
    $ui->windowSetMargined($w, 1);

    $b = $ui->newVerticalBox();
    $ui->boxSetPadded($b, 1);
    $ui->windowSetChild($w, $b);

    $e = $ui->newMultilineEntry();
    $ui->multilineEntrySetReadOnly($e, 1);

    $btn = $ui->newButton("Say Something");
    $ui->buttonOnClicked($btn, 'saySomething', NULL);
    $ui->boxAppend($b, $btn, 0);

    $ui->boxAppend($b, $e, 1);

    $ui->timer(1000, 'sayTime', NULL);

    $ui->windowOnClosing($w, 'onClosing', NULL);
    $ui->controlShow($w);
    $ui->main();
    return 0;
}

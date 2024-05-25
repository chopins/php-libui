<?php

use UI\Struct\UIAlign;

include __DIR__ .'/loadui.php';

$dtboth = $dtdate = $dttime = null;
$time = $ui->newTm(true);
main();
function timeFormat($d)
{
    global $dtboth,$dtdate, $dttime;
    if ($d == $dtboth)
        $fmt = "%c";
    else if ($d == $dtdate)
        $fmt = "%x";
    else if ($d == $dttime)
        $fmt = "%X";
    else
        $fmt = "";
    return $fmt;
}

function onChanged($d, $data)
{
    global $ui, $dtboth,$dtdate, $dttime;

    $time = $ui->newTm(true);

    $ui->dateTimePickerTime($d, $time);
    $fmt = timeFormat($d);
    $time = $time[0];
    $time = mktime($time->tm_hour, $time->tm_min, $time->tm_sec,$time->tm_mon, $time->tm_mday, $time->tm_year + 1900);
    $buf = strftime($fmt, $time);
    $ui->labelSetText($data, $buf);
}

function onClicked($b, $data)
{
	global $ui, $dtboth,$dtdate, $dttime;
    $tmbuf = $ui->newTm();
	$t = 0;

    $now = $data !== null && !FFI::isNull($data) ;
    if ($now) {
        $t = time();
    }
    $lt = localtime($t, true);
    foreach($lt as $key => $v) {
        $tmbuf->$key = $v;
    }

    if ($now) {
        $ui->dateTimePickerSetTime($dtdate, FFI::addr($tmbuf));
        $ui->dateTimePickerSetTime($dttime, FFI::addr($tmbuf));
    } else {
        $ui->dateTimePickerSetTime($dtboth, FFI::addr($tmbuf));
	}
}

function onClosing($w, $data) : int
{
    global $ui, $dtboth,$dtdate, $dttime;
    $ui->quit();
    return 1;
}

function main() : int
{
    global $ui, $dtboth,$dtdate, $dttime;

    $err = $ui->init();
    if ($err != NULL) {
        fprintf("error initializing ui: %s\n", $err);
        $ui->freeInitError($err);
        return 1;
    }

    $w = $ui->newWindow("Date / Time", 320, 240, 0);
    $ui->windowSetMargined($w, 1);

    $g = $ui->newGrid();
    $ui->gridSetPadded($g, 1);
    $ui->windowSetChild($w, $g);

    $dtboth =$ui->newDateTimePicker();
    $dtdate =$ui->newDatePicker();
    $dttime =$ui->newTimePicker();

    $time = $ui->new('struct tm*');

    $ui->gridAppend($g, $dtboth,
        0, 0, 2, 1,
        1, UIAlign::ALIGN_FILL->value, 0, UIAlign::ALIGN_FILL->value);
    $ui->gridAppend($g, $dtdate,
        0, 1, 1, 1,
        1, UIAlign::ALIGN_FILL->value, 0, UIAlign::ALIGN_FILL->value);
    $ui->gridAppend($g, $dttime,
        1, 1, 1, 1,
        1, UIAlign::ALIGN_FILL->value, 0, UIAlign::ALIGN_FILL->value);

    $l =$ui->newLabel("");
    $ui->gridAppend($g, $l,
        0, 2, 2, 1,
        1, UIAlign::ALIGN_CENTER->value, 0, UIAlign::ALIGN_FILL->value);
    $ui->dateTimePickerOnChanged($dtboth, 'onChanged', $l);
    $l =$ui->newLabel("");
    $ui->gridAppend($g, $l,
        0, 3, 1, 1,
        1, UIAlign::ALIGN_CENTER->value, 0, UIAlign::ALIGN_FILL->value);
    $ui->dateTimePickerOnChanged($dtdate, 'onChanged', $l);
    $l =$ui->newLabel("");
    $ui->gridAppend($g, $l,
        1, 3, 1, 1,
        1, UIAlign::ALIGN_CENTER->value, 0, UIAlign::ALIGN_FILL->value);
    $ui->dateTimePickerOnChanged($dttime, 'onChanged', $l);

    $b =$ui->newButton("Now");

    $ui->buttonOnClicked($b, 'onClicked',  FFI::cast('void *', 1));
    $ui->gridAppend($g, $b,
        0, 4, 1, 1,
        1, UIAlign::ALIGN_FILL->value, 1, UIAlign::ALIGN_END->value);

	$b =$ui->newButton("Unix epoch");
    $ui->buttonOnClicked($b, 'onClicked', FFI::cast('void*', 0));
    $ui->gridAppend($g, $b,
        1, 4, 1, 1,
        1, UIAlign::ALIGN_FILL->value, 1, UIAlign::ALIGN_END->value);

    $ui->windowOnClosing($w, 'onClosing', NULL);
    $ui->controlShow($w);
    $ui->main();
    return 0;
}



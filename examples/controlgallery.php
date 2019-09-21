<?php

include __DIR__ . '/loadui.php';
$spinbox = $pbar = $spinbox = null;

$err = $ui->init();

$mainwin = $ui->newWindow("libui Control Gallery", 640, 480, 1);

$ui->windowOnClosing($mainwin, 'onClosing', NULL);
$ui->onShouldQuit(function ($data) use ($ui) {
    $mainwin = $ui->window($data);
    $ui->controlDestroy($mainwin);
    return 1;
}, $mainwin);

$tab = $ui->newTab();

$ui->windowSetChild($mainwin, $tab);
$ui->windowSetMargined($mainwin, 1);

$ui->tabAppend($tab, "Basic Controls", makeBasicControlsPage());
$ui->tabSetMargined($tab, 0, 1);

$ui->tabAppend($tab, "Numbers and Lists", makeNumbersPage());
$ui->tabSetMargined($tab, 1, 1);

$ui->tabAppend($tab, "Data Choosers", makeDataChoosersPage());
$ui->tabSetMargined($tab, 2, 1);

$ui->controlShow($mainwin);
$ui->main();
function onClosing()
{
    global $ui;
    $ui->quit();
    return 1;
}

function onSpinboxChanged($s)
{
    global $ui, $slider, $pbar;
    $ui->sliderSetValue($slider, $ui->spinboxValue($s));
    $ui->progressBarSetValue($pbar, $ui->spinboxValue($s));
}

function onSliderChanged($s)
{
    global $ui, $spinbox, $pbar;
    $ui->spinboxSetValue($spinbox, $ui->sliderValue($s));
    $ui->progressBarSetValue($pbar, $ui->sliderValue($s));
}
function makeNumbersPage()
{
    global $ui, $spinbox, $pbar, $spinbox, $slider;
    $hbox = $ui->newHorizontalBox();
    $ui->boxSetPadded($hbox, 1);

    $group = $ui->newGroup("Numbers");
    $ui->groupSetMargined($group, 1);
    $ui->boxAppend($hbox, $group, 1);

    $vbox = $ui->newVerticalBox();
    $ui->boxSetPadded($vbox, 1);
    $ui->groupSetChild($group, $vbox);

    $spinbox = $ui->newSpinbox(3, 100);
    $slider = $ui->newSlider(5, 100);
    $pbar = $ui->newProgressBar();
    $ui->spinboxOnChanged($spinbox, 'onSpinboxChanged', NULL);
    $ui->sliderOnChanged($slider, 'onSliderChanged', NULL);
    $ui->boxAppend($vbox, $spinbox, 0);
    $ui->boxAppend($vbox, $slider, 0);
    $ui->boxAppend($vbox, $pbar, 0);

    $ip = $ui->newProgressBar();
    $ui->progressBarSetValue($ip, -1);
    $ui->boxAppend($vbox, $ip, 0);

    $group = $ui->newGroup("Lists");
    $ui->groupSetMargined($group, 1);
    $ui->boxAppend($hbox, $group, 1);

    $vbox = $ui->newVerticalBox();
    $ui->boxSetPadded($vbox, 1);
    $ui->groupSetChild($group, $vbox);

    $cbox = $ui->newCombobox();
    $ui->comboboxAppend($cbox, "Combobox Item 1");
    $ui->comboboxAppend($cbox, "Combobox Item 2");
    $ui->comboboxAppend($cbox, "Combobox Item 3");
    $ui->boxAppend($vbox, $cbox, 0);

    $ecbox = $ui->newEditableCombobox();
    $ui->editableComboboxAppend($ecbox, "Editable Item 1");
    $ui->editableComboboxAppend($ecbox, "Editable Item 2");
    $ui->editableComboboxAppend($ecbox, "Editable Item 3");
    $ui->boxAppend($vbox, $ecbox, 0);

    $rb = $ui->newRadioButtons();
    $ui->radioButtonsAppend($rb, "Radio Button 1");
    $ui->radioButtonsAppend($rb, "Radio Button 2");
    $ui->radioButtonsAppend($rb, "Radio Button 3");
    $ui->boxAppend($vbox, $rb, 0);

    return $hbox;
}

function onOpenFileClicked($b, $data)
{
    global $ui, $mainwin;
    $entry = $data;

    $filename = $ui->openFile($mainwin);
    if ($filename == NULL) {
        $ui->entrySetText($entry, "(cancelled)");
        return;
    }
    $ui->entrySetText($entry, $filename);
    $ui->freeText($filename);
}
function onSaveFileClicked($b, $data)
{
    global $ui, $mainwin;
    $entry = $data;
    $filename = $ui->saveFile($mainwin);
    if ($filename == NULL) {
        $ui->entrySetText($entry, "(cancelled)");
        return;
    }
    $ui->entrySetText($entry, $filename);
    $ui->freeText($filename);
}
function onMsgBoxClicked($b, $data)
{
    global $ui, $mainwin;
    $ui->msgBox(
        $mainwin,
        "This is a normal message box.",
        "More detailed information can be shown here."
    );
}

function onMsgBoxErrorClicked($b, $data)
{
    global $ui, $mainwin;
    $ui->msgBoxError(
        $mainwin,
        "This message box describes an error.",
        "More detailed information can be shown here."
    );
}
function makeDataChoosersPage()
{
    global $ui;
    $hbox = $ui->newHorizontalBox();
    $ui->boxSetPadded($hbox, 1);

    $vbox = $ui->newVerticalBox();
    $ui->boxSetPadded($vbox, 1);
    $ui->boxAppend($hbox, $vbox, 0);

    $ui->boxAppend(
        $vbox,
        $ui->newDatePicker(),
        0
    );
    $ui->boxAppend(
        $vbox,
        $ui->newTimePicker(),
        0
    );
    $ui->boxAppend(
        $vbox,
        $ui->newDateTimePicker(),
        0
    );

    $ui->boxAppend(
        $vbox,
        $ui->newFontButton(),
        0
    );
    $ui->boxAppend(
        $vbox,
        $ui->newColorButton(),
        0
    );

    $ui->boxAppend(
        $hbox,
        $ui->newVerticalSeparator(),
        0
    );

    $vbox = $ui->newVerticalBox();
    $ui->boxSetPadded($vbox, 1);
    $ui->boxAppend($hbox, $vbox, 1);

    $grid = $ui->newGrid();
    $ui->gridSetPadded($grid, 1);
    $ui->boxAppend($vbox, $grid, 0);

    $button = $ui->newButton("Open File");
    $entry = $ui->newEntry();
    $ui->entrySetReadOnly($entry, 1);
    $ui->buttonOnClicked($button, 'onOpenFileClicked', $entry);
    $ui->gridAppend(
        $grid,
        $button,
        0,
        0,
        1,
        1,
        0,
        $ui::ALIGN_FILL,
        0,
        $ui::ALIGN_FILL
    );
    $ui->gridAppend(
        $grid,
        $entry,
        1,
        0,
        1,
        1,
        1,
        $ui::ALIGN_FILL,
        0,
        $ui::ALIGN_FILL
    );

    $button = $ui->newButton("Save File");
    $entry = $ui->newEntry();
    $ui->entrySetReadOnly($entry, 1);
    $ui->buttonOnClicked($button, 'onSaveFileClicked', $entry);
    $ui->gridAppend(
        $grid,
        $button,
        0,
        1,
        1,
        1,
        0,
        $ui::ALIGN_FILL,
        0,
        $ui::ALIGN_FILL
    );
    $ui->gridAppend(
        $grid,
        $entry,
        1,
        1,
        1,
        1,
        1,
        $ui::ALIGN_FILL,
        0,
        $ui::ALIGN_FILL
    );

    $msggrid = $ui->newGrid();
    $ui->gridSetPadded($msggrid, 1);
    $ui->gridAppend(
        $grid,
        $msggrid,
        0,
        2,
        2,
        1,
        0,
        $ui::ALIGN_CENTER,
        0,
        $ui::ALIGN_START
    );

    $button = $ui->newButton("Message Box");
    $ui->buttonOnClicked($button, 'onMsgBoxClicked', NULL);
    $ui->gridAppend(
        $msggrid,
        $button,
        0,
        0,
        1,
        1,
        0,
        $ui::ALIGN_FILL,
        0,
        $ui::ALIGN_FILL
    );
    $button = $ui->newButton("Error Box");
    $ui->buttonOnClicked($button, 'onMsgBoxErrorClicked', NULL);
    $ui->gridAppend(
        $msggrid,
        $button,
        1,
        0,
        1,
        1,
        0,
        $ui::ALIGN_FILL,
        0,
        $ui::ALIGN_FILL
    );

    return $hbox;
}

function makeBasicControlsPage()
{
    global $ui;

    $vbox = $ui->newVerticalBox();
    $ui->boxSetPadded($vbox, 1);

    $hbox = $ui->newHorizontalBox();
    $ui->boxSetPadded($hbox, 1);
    $ui->boxAppend($vbox, $hbox, 0);

    $ui->boxAppend(
        $hbox,
        $ui->newButton("Button"),
        0
    );
    $ui->boxAppend(
        $hbox,
        $ui->newCheckbox("Checkbox"),
        0
    );

    $ui->boxAppend(
        $vbox,
        $ui->newLabel("This is a label. Right now, labels can only span one line."),
        0
    );

    $ui->boxAppend(
        $vbox,
        $ui->newHorizontalSeparator(),
        0
    );

    $group = $ui->newGroup("Entries");
    $ui->groupSetMargined($group, 1);
    $ui->boxAppend($vbox, $group, 1);

    $entryForm = $ui->newForm();
    $ui->formSetPadded($entryForm, 1);
    $ui->groupSetChild($group, $entryForm);

    $ui->formAppend(
        $entryForm,
        "Entry",
        $ui->newEntry(),
        0
    );
    $ui->formAppend(
        $entryForm,
        "Password Entry",
        $ui->newPasswordEntry(),
        0
    );
    $ui->formAppend(
        $entryForm,
        "Search Entry",
        $ui->newSearchEntry(),
        0
    );
    $ui->formAppend(
        $entryForm,
        "Multiline Entry",
        $ui->newMultilineEntry(),
        1
    );
    $ui->formAppend(
        $entryForm,
        "Multiline Entry No Wrap",
        $ui->newNonWrappingMultilineEntry(),
        1
    );

    return $vbox;
}

<?php
include __DIR__ .'/loadui.php';
$attrstr = FFI::addr($ui->new('uiAttributedString'));
$fontButton = FFI::addr($ui->new('uiFontButton'));
$alignment = FFI::addr($ui->new('uiCombobox'));
$handler = $ui->new('uiAreaHandler');
$area = FFI::addr($ui->new('uiArea'));
$mainwin = FFI::addr($ui->new('uiWindow'));

main();

function appendWithAttribute(string $what,  $attr, $attr2)
{
    global $ui, $attrstr;
    $start = $ui->attributedStringLen($attrstr);
    $end = $start + strlen($what);
    $ui->attributedStringAppendUnattributed($attrstr, $what);
    $ui->attributedStringSetAttribute($attrstr, $attr, $start, $end);
    if ($attr2 != NULL)
        $ui->attributedStringSetAttribute($attrstr, $attr2, $start, $end);
}

function makeAttributedString()
{
    global $ui, $attrstr;

    $attrstr = $ui->newAttributedString(
        "Drawing strings with libui is done with the uiAttributedString and uiDrawTextLayout objects.\nuiAttributedString lets you have a variety of attributes: "
    );

    $attr = $ui->newFamilyAttribute("Courier New");
    appendWithAttribute("font family", $attr, NULL);
    $ui->attributedStringAppendUnattributed($attrstr, ", ");

    $attr = $ui->newSizeAttribute(18);
    appendWithAttribute("font size", $attr, NULL);
    $ui->attributedStringAppendUnattributed($attrstr, ", ");

    $attr = $ui->newWeightAttribute($ui::TEXT_WEIGHT_BOLD);
    appendWithAttribute("font weight", $attr, NULL);
    $ui->attributedStringAppendUnattributed($attrstr, ", ");

    $attr = $ui->newItalicAttribute($ui::TEXT_ITALIC_ITALIC);
    appendWithAttribute("font italicness", $attr, NULL);
    $ui->attributedStringAppendUnattributed($attrstr, ", ");

    $attr = $ui->newStretchAttribute($ui::TEXT_STRETCH_CONDENSED);
    appendWithAttribute("font stretch", $attr, NULL);
    $ui->attributedStringAppendUnattributed($attrstr, ", ");

    $attr = $ui->newColorAttribute(0.75, 0.25, 0.5, 0.75);
    appendWithAttribute("text color", $attr, NULL);
    $ui->attributedStringAppendUnattributed($attrstr, ", ");

    $attr = $ui->newBackgroundAttribute(0.5, 0.5, 0.25, 0.5);
    appendWithAttribute("text background color", $attr, NULL);
    $ui->attributedStringAppendUnattributed($attrstr, ", ");


    $attr = $ui->newUnderlineAttribute($ui::UNDERLINE_SINGLE);
    appendWithAttribute("underline style", $attr, NULL);
    $ui->attributedStringAppendUnattributed($attrstr, ", ");

    $ui->attributedStringAppendUnattributed($attrstr, "and ");
    $attr = $ui->newUnderlineAttribute($ui::UNDERLINE_DOUBLE);
    $attr2 = $ui->newUnderlineColorAttribute($ui::UNDERLINE_COLOR_CUSTOM, 1.0, 0.0, 0.5, 1.0);
    appendWithAttribute("underline color", $attr, $attr2);
    $ui->attributedStringAppendUnattributed($attrstr, ". ");

    $ui->attributedStringAppendUnattributed($attrstr, "Furthermore, there are attributes allowing for ");
    $attr = $ui->newUnderlineAttribute($ui::UNDERLINE_SUGGESTION);
    $attr2 = $ui->newUnderlineColorAttribute($ui::UNDERLINE_COLOR_SPELLING, 0, 0, 0, 0);
    appendWithAttribute("special underlines for indicating spelling errors", $attr, $attr2);
    $ui->attributedStringAppendUnattributed($attrstr, " (and other types of errors) ");

    $ui->attributedStringAppendUnattributed($attrstr, "and control over OpenType features such as ligatures (for instance, ");
    $otf = $ui->newOpenTypeFeatures();
    $ui->openTypeFeaturesAdd($otf, 'l', 'i', 'g', 'a', 0);
    $attr = $ui->newFeaturesAttribute($otf);
    appendWithAttribute("afford", $attr, NULL);
    $ui->attributedStringAppendUnattributed($attrstr, " vs. ");
    $ui->openTypeFeaturesAdd($otf, 'l', 'i', 'g', 'a', 1);
    $attr = $ui->newFeaturesAttribute($otf);
    appendWithAttribute("afford", $attr, NULL);
    $ui->freeOpenTypeFeatures($otf);
    $ui->attributedStringAppendUnattributed($attrstr, ").\n");

    $ui->attributedStringAppendUnattributed($attrstr, "Use the controls opposite to the text to control properties of the text.");
}

function handlerDraw($a, $area, $p)
{
    global $ui, $fontButton, $attrstr, $alignment;
    try {
    $defaultFont = FFI::addr($ui->new('uiFontDescriptor'));
    $params = $ui->new('uiDrawTextLayoutParams');

    $params->String = $attrstr;
    $ui->fontButtonFont($fontButton, $defaultFont);
    $params->DefaultFont = $defaultFont;
    $params->Width = $p->AreaWidth;
    $params->Align = $ui->comboboxSelected($alignment);
    $textLayout = $ui->drawNewTextLayout(FFI::addr($params));
    $ui->drawText($p->Context, $textLayout, 0, 0);
    $ui->drawFreeTextLayout($textLayout);
    $ui->freeFontButtonFont($defaultFont);
    } catch(\Error $e) {
        echo $e;
    } catch(\Exception $e) {
        echo $e;
    }
}

function handlerMouseEvent($a, $area, $e)
{
    // do nothing
}

function handlerMouseCrossed($ah, $a, int $left)
{
    // do nothing
}

function handlerDragBroken($ah, $a)
{
    // do nothing
}

function handlerKeyEvent($ah, $a, $e): int
{
    // reject all keys
    return 0;
}

function onFontChanged($b, $data)
{
    global $ui, $area;
    $ui->areaQueueRedrawAll($area);
}

function onComboboxSelected($b, $data)
{
    global $ui, $area;
    $ui->areaQueueRedrawAll($area);
}

function onClosing($w, $data): int
{
    global $ui, $mainwin;
    $ui->controlDestroy($mainwin);
    $ui->quit();
    return 0;
}

function shouldQuit($data): int
{
    global $ui, $mainwin;
    $ui->controlDestroy($mainwin);
    return 1;
}

function main(): int
{
    global $ui, $handler, $mainwin, $alignment, $fontButton, $attrstr;

    $handler->Draw = 'handlerDraw';
    $handler->MouseEvent = 'handlerMouseEvent';
    $handler->MouseCrossed = 'handlerMouseCrossed';
    $handler->DragBroken = 'handlerDragBroken';
    $handler->KeyEvent = 'handlerKeyEvent';

    $err = $ui->init();
    if ($err != NULL) {
        fprintf(STDERR, "error initializing ui: %s\n", $err);
        $ui->freeInitError($err);
        return 1;
    }

    $ui->onShouldQuit('shouldQuit', NULL);

    makeAttributedString();

    $mainwin = $ui->newWindow("libui Text-Drawing Example", 640, 480, 1);
    $ui->windowSetMargined($mainwin, 1);
    $ui->windowOnClosing($mainwin, 'onClosing', NULL);

    $hbox = $ui->newHorizontalBox();
    $ui->boxSetPadded($hbox, 1);
    $ui->windowSetChild($mainwin, $hbox);

    $vbox = $ui->newVerticalBox();
    $ui->boxSetPadded($vbox, 1);
    $ui->boxAppend($hbox, $vbox, 0);

    $fontButton = $ui->newFontButton();
    $ui->fontButtonOnChanged($fontButton, 'onFontChanged', NULL);
    $ui->boxAppend($vbox, $fontButton, 0);

    $form = $ui->newForm();
    $ui->formSetPadded($form, 1);
    // TODO on OS X if this is set to 1 then the window can't resize; does the form not have the concept of stretchy trailing space?
    $ui->boxAppend($vbox, $form, 0);

    $alignment = $ui->newCombobox();
    // note that the items match with the values of the $ui->drawTextAlign values
    $ui->comboboxAppend($alignment, "Left");
    $ui->comboboxAppend($alignment, "Center");
    $ui->comboboxAppend($alignment, "Right");
    $ui->comboboxSetSelected($alignment, 0);        // start with left alignment
    $ui->comboboxOnSelected($alignment, 'onComboboxSelected', NULL);
    $ui->formAppend($form, "Alignment", $alignment, 0);

    $area = $ui->newArea(FFI::addr($handler));
    $ui->boxAppend($hbox, $area, 1);

    $ui->controlShow($mainwin);
    $ui->main();
    $ui->freeAttributedString($attrstr);
    $ui->uninit();
    return 0;
}

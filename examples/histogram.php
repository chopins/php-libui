<?php
include __DIR__ . '/loadui.php';

$manwin = $ui->new('uiWindow*');

$histogram =  $ui->new('uiArea*');

$handler = $ui->new('uiAreaHandler');
$datapoints = $ui->new('uiSpinbox*[10]');

$colorButton = $ui->new('uiColorButton*');
$currentPoint = -1;

// some metrics
const X_OFF_LEFT = 20;            /* histogram margins */
const Y_OFF_TOP = 20;
const X_OFF_RIGHT = 20;
const Y_OFF_BOTTOM = 20;
const POINT_RADIUS =  5;
const COLOR_WHITE = 0xFFFFFF;
const COLOR_BLACK = 0x000000;
const COLOR_DODGER_BLUE = 0x1E90FF;
try {
main();
} catch(Error $e) {
    echo $e;
}
// helper to quickly set a brush color
function setSolidBrush($brush, $color,  $alpha)
{
    global $ui;
    $brush->Type = $ui::DRAW_BRUSH_TYPE_SOLID;
    $component = (int) (($color >> 16) & 0xFF);
    $brush->R = ((float) $component) / 255;
    $component = (int) (($color >> 8) & 0xFF);
    $brush->G = ((float) $component) / 255;
    $component = (int) ($color & 0xFF);
    $brush->B = ((float) $component) / 255;
    $brush->A = $alpha;
}


function pointLocations($width,  $height,  $xs,  $ys)
{
    global $ui, $datapoints;

    $xincr = $width->cdata / 9;        // 10 - 1 to make the last point be at the end
    $yincr = $height->cdata / 100;

    for ($i = 0; $i < 10; $i++) {
        // get the value of the point
        $n = $ui->spinboxValue($datapoints[$i]);
        // because y=0 is the top but n=0 is the bottom, we need to flip
        $n = 100 - $n;
        $xs[$i] = $xincr * $i;
        $ys[$i] = $yincr * $n;
    }
}

function constructGraph($width,  $height, $extend)
{
    global $ui, $datapoints;
    $xs = $ui->new('double[10]');
    $ys = $ui->new('double[10]');
    pointLocations($width, $height, $xs, $ys);

    $path = $ui->drawNewPath($ui::DRAW_FILL_MODE_WINDING);

    $ui->drawPathNewFigure($path, $xs[0], $ys[0]);
    for ($i = 1; $i < 10; $i++)
        $ui->drawPathLineTo($path, $xs[$i], $ys[$i]);

    if ($extend) {
        $ui->drawPathLineTo($path,$width->cdata, $height->cdata);
        $ui->drawPathLineTo($path, 0, $height->cdata);
        $ui->drawPathCloseFigure($path);
    }

    $ui->drawPathEnd($path);
    return $path;
}

function graphSize($clientWidth,  $clientHeight,  $graphWidth,  $graphHeight)
{
    $graphWidth = $clientWidth - X_OFF_LEFT - X_OFF_RIGHT;
    $graphHeight = $clientHeight - Y_OFF_TOP - Y_OFF_BOTTOM;
}

function handlerDraw($a, $area, $p)
{
    global $ui, $colorButton, $currentPoint;
    try {
        $brush = $ui->new('uiDrawBrush');
        $brushPtr = FFI::addr($brush);

        $sp = $ui->new('uiDrawStrokeParams');
        $m = $ui->new('uiDrawMatrix');
        $mptr = FFI::addr($m);
        $spptr = FFI::addr($sp);
        $graphHeight = $ui->new('double');
        $graphHeightPtr = FFI::addr($graphHeight);
        $graphWidth = $ui->new('double');
        $graphWidthPtr = FFI::addr($graphWidth);

        $graphR = $ui->new('double');
        $graphG = $ui->new('double');
        $graphB = $ui->new('double');
        $graphA = $ui->new('double');

        // fill the area with white
        setSolidBrush($brushPtr, COLOR_WHITE, 1.0);
        $path = $ui->drawNewPath($ui::DRAW_FILL_MODE_WINDING);

        $ui->drawPathAddRectangle($path, 0, 0, $p->AreaWidth, $p->AreaHeight);
        $ui->drawPathEnd($path);
        $ui->drawFill($p->Context, $path, $brushPtr);
        $ui->drawFreePath($path);

        // figure out dimensions
        graphSize($p->AreaWidth, $p->AreaHeight, $graphWidthPtr, $graphHeightPtr);

        // clear sp to avoid passing garbage to $ui->drawStroke()
        // for example, we don't use dashing
        FFI::memset(FFI::addr($sp), 0, $ui->struct->getTypeSize('uiDrawStrokeParams'));

        // make a stroke for both the axes and the histogram line
        $sp->Cap = $ui::DRAW_LINE_CAP_FLAT;
        $sp->Join = $ui::DRAW_LINE_JOIN_MITER;
        $sp->Thickness = 2;
        $sp->MiterLimit = $ui::DRAW_DEFAULT_MITER_LIMIT;

        // draw the axes
        setSolidBrush($brushPtr, COLOR_BLACK, 1.0);
        $path = $ui->drawNewPath($ui::DRAW_FILL_MODE_WINDING);
        $ui->drawPathNewFigure(
            $path,
            X_OFF_LEFT,
            Y_OFF_TOP
        );
        $ui->drawPathLineTo(
            $path,
            X_OFF_LEFT,
            Y_OFF_TOP + $graphHeight->cdata
        );
        $ui->drawPathLineTo(
            $path,
            X_OFF_LEFT + $graphWidth->cdata,
            Y_OFF_TOP + $graphHeight->cdata
        );
        $ui->drawPathEnd($path);
        $ui->drawStroke($p->Context, $path, $brushPtr, $spptr);
        $ui->drawFreePath($path);

        // now transform the coordinate space so (0, 0) is the top-left corner of the graph
        $ui->drawMatrixSetIdentity($mptr);
        $ui->drawMatrixTranslate($mptr, X_OFF_LEFT, Y_OFF_TOP);
        $ui->drawTransform($p->Context, $mptr);

        // now get the color for the graph itself and set up the brush
        $ui->colorButtonColor($colorButton, FFI::addr($graphR), FFI::addr($graphG), FFI::addr($graphB), FFI::addr($graphA));
        $brush->Type = $ui::DRAW_BRUSH_TYPE_SOLID;
        $brush->R = $graphR->cdata;
        $brush->G = $graphG->cdata;
        $brush->B = $graphB->cdata;
        // we set brush->A below to different values for the fill and stroke

        // now create the fill for the graph below the graph line
        $path = constructGraph($graphWidth, $graphHeight, 1);
        $brush->A = $graphA->cdata / 2;
        $ui->drawFill($p->Context, $path, $brushPtr);
        $ui->drawFreePath($path);

        // now draw the histogram line
        $path = constructGraph($graphWidth, $graphHeight, 0);
        $brush->A = $graphA->cdata;
        $ui->drawStroke($p->Context, $path, $brushPtr, $spptr);
        $ui->drawFreePath($path);

        // now draw the point being hovered over
        if ($currentPoint != -1) {
            $xs = $ui->new('double[10]');
            $ys = $ui->new('double[10]');

            pointLocations($graphWidth, $graphHeight, $xs, $ys);
            $path = $ui->drawNewPath($ui::DRAW_FILL_MODE_WINDING);
            $ui->drawPathNewFigureWithArc(
                $path,
                $xs[$currentPoint],
                $ys[$currentPoint],
                POINT_RADIUS,
                0,
                6.23,        // TODO pi
                0
            );
            $ui->drawPathEnd($path);
            // use the same brush as for the histogram lines
            $ui->drawFill($p->Context, $path, $brushPtr);
            $ui->drawFreePath($path);
        }
    } catch (Error $e) {
        echo $e;
    } catch(Execption $e) {
        echo $e;
    }
}

function inPoint($x, $y, $xtest, $ytest)
{
    // TODO switch to using a matrix
    $x -= X_OFF_LEFT;
    $y -= Y_OFF_TOP;
    return ($x >= $xtest - POINT_RADIUS) && ($x <= $xtest + POINT_RADIUS) && ($y >= $ytest - POINT_RADIUS) && ($y <= $ytest + POINT_RADIUS);
}

function handlerMouseEvent($a, $area, $e)
{
    global $ui, $currentPoint, $histogram;
    try {
    $graphWidth = $ui->new('double');
    $graphHeight = $ui->new('double');
    $xs = $ui->new('double[10]');
    $ys = $ui->new('double[10]');

    graphSize($e->AreaWidth, $e->AreaHeight, FFI::addr($graphWidth), FFI::addr($graphHeight));
    pointLocations($graphWidth, $graphHeight, $xs, $ys);

    for ($i = 0; $i < 10; $i++)
        if (inPoint($e->X, $e->Y, $xs[$i], $ys[$i]))
            break;
    if ($i == 10)        // not $in a point
        $i = -1;

    $currentPoint = $i;
    // TODO only redraw the relevant area
    $ui->areaQueueRedrawAll($histogram);
    }catch(Error $e) {
        echo $e;
    }
}

function handlerMouseCrossed($ah, $a,  $left)
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

function onDatapointChanged($s, $data)
{
    global $ui, $histogram;
    $ui->areaQueueRedrawAll($histogram);
}

function onColorChanged($b, $data)
{
    global $ui, $histogram;
    $ui->areaQueueRedrawAll($histogram);
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
    global $ui, $handler, $mainwin, $colorButton, $histogram, $datapoints;

    $brush = $ui->new('uiDrawBrush');

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

    $mainwin = $ui->newWindow("libui Histogram Example", 640, 480, 1);
    $ui->windowSetMargined($mainwin, 1);
    $ui->windowOnClosing($mainwin, 'onClosing', NULL);

    $hbox = $ui->newHorizontalBox();
    $ui->boxSetPadded($hbox, 1);
    $ui->windowSetChild($mainwin, $hbox);

    $vbox = $ui->newVerticalBox();
    $ui->boxSetPadded($vbox, 1);
    $ui->boxAppend($hbox, $vbox, 0);

    srand(time());
    for ($i = 0; $i < 10; $i++) {
        $datapoints[$i] = $ui->newSpinbox(0, 100);
        $ui->spinboxSetValue($datapoints[$i], rand() % 101);
        $ui->spinboxOnChanged($datapoints[$i], 'onDatapointChanged', NULL);
        $ui->boxAppend($vbox, $datapoints[$i], 0);
    }

    $colorButton = $ui->newColorButton();
    // TODO inline these

    setSolidBrush(FFI::addr($brush), COLOR_DODGER_BLUE, 1.0);

    $ui->colorButtonSetColor(
        $colorButton,
        $brush->R,
        $brush->G,
        $brush->B,
        $brush->A
    );

    $ui->colorButtonOnChanged($colorButton, 'onColorChanged', NULL);
    $ui->boxAppend($vbox, $colorButton, 0);

    $histogram = $ui->newArea(FFI::addr($handler));
    $ui->boxAppend($hbox, $histogram, 1);

    $ui->controlShow($mainwin);
    $ui->main();
    $ui->uninit();
    return 0;
}

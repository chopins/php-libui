<?php

/**
 * libui (http://toknot.com)
 *
 * @copyright  Copyright (c) 2011 - 2019 Toknot.com
 * @license    http://toknot.com/LICENSE.txt New BSD License
 * @link       https://github.com/chopins/php-libui
 */

namespace UI;

use FFI;
use FFI\CData;
use FFI\CType;
use UI\UIBuild;
use UI\Struct\Struct;

/**
 *
 * libui
 * All return C type is uiControll of CData instance
 *
 * @method void freeInitError(const char *err);
 * @method void main(void);
 * @method void mainSteps(void);
 * @method int mainStep(int wait);
 * @method void quit(void);
 * @method void queueMain(void (*f)(void *data), void *data);
 * @method void timer(int milliseconds, int (*f)(void *data), void *data);
 * @method void onShouldQuit(int (*f)(void *data), void *data);
 * @method void freeText(char *text);
 * @method void controlDestroy(uiControl *);
 * @method uintptr_t controlHandle(uiControl *);
 * @method uiControl *controlParent(uiControl *);
 * @method void controlSetParent(uiControl *, uiControl *);
 * @method int controlToplevel(uiControl *);
 * @method int controlVisible(uiControl *);
 * @method void controlShow(uiControl *);
 * @method void controlHide(uiControl *);
 * @method int controlEnabled(uiControl *);
 * @method void controlEnable(uiControl *);
 * @method void controlDisable(uiControl *);
 * @method uiControl *allocControl(size_t n, uint32_t OSsig, uint32_t typesig, const char *typenamestr);
 * @method void freeControl(uiControl *);
 * @method void controlVerifySetParent(uiControl *, uiControl *);
 * @method int controlEnabledToUser(uiControl *);
 * @method void userBugCannotSetParentOnToplevel(const char *type);
 * @method char *windowTitle(uiWindow *w);
 * @method void windowSetTitle(uiWindow *w, const char *title);
 * @method void windowContentSize(uiWindow *w, int *width, int *height);
 * @method void windowSetContentSize(uiWindow *w, int width, int height);
 * @method int windowFullscreen(uiWindow *w);
 * @method void windowSetFullscreen(uiWindow *w, int fullscreen);
 * @method void windowOnContentSizeChanged(uiWindow *w, void (*f)(uiWindow *, void *), void *data);
 * @method void windowOnClosing(uiWindow *w, int (*f)(uiWindow *w, void *data), void *data);
 * @method int windowBorderless(uiWindow *w);
 * @method void windowSetBorderless(uiWindow *w, int borderless);
 * @method void windowSetChild(uiWindow *w, uiControl *child);
 * @method int windowMargined(uiWindow *w);
 * @method void windowSetMargined(uiWindow *w, int margined);
 * @method uiWindow *newWindow(const char *title, int width, int height, int hasMenubar);
 * @method char *buttonText(uiButton *b);
 * @method void buttonSetText(uiButton *b, const char *text);
 * @method void buttonOnClicked(uiButton *b, void (*f)(uiButton *b, void *data), void *data);
 * @method uiButton *newButton(const char *text);
 * @method void boxAppend(uiBox *b, uiControl *child, int stretchy);
 * @method void boxDelete(uiBox *b, int index);
 * @method int boxPadded(uiBox *b);
 * @method void boxSetPadded(uiBox *b, int padded);
 * @method uiBox *newHorizontalBox(void);
 * @method uiBox *newVerticalBox(void);
 * @method char *checkboxText(uiCheckbox *c);
 * @method void checkboxSetText(uiCheckbox *c, const char *text);
 * @method void checkboxOnToggled(uiCheckbox *c, void (*f)(uiCheckbox *c, void *data), void *data);
 * @method int checkboxChecked(uiCheckbox *c);
 * @method void checkboxSetChecked(uiCheckbox *c, int checked);
 * @method uiCheckbox *newCheckbox(const char *text);
 * @method char *entryText(uiEntry *e);
 * @method void entrySetText(uiEntry *e, const char *text);
 * @method void entryOnChanged(uiEntry *e, void (*f)(uiEntry *e, void *data), void *data);
 * @method int entryReadOnly(uiEntry *e);
 * @method void entrySetReadOnly(uiEntry *e, int readonly);
 * @method uiEntry *newEntry(void);
 * @method uiEntry *newPasswordEntry(void);
 * @method uiEntry *newSearchEntry(void);
 * @method char *labelText(uiLabel *l);
 * @method void labelSetText(uiLabel *l, const char *text);
 * @method uiLabel *newLabel(const char *text);
 * @method void tabAppend(uiTab *t, const char *name, uiControl *c);
 * @method void tabInsertAt(uiTab *t, const char *name, int before, uiControl *c);
 * @method void tabDelete(uiTab *t, int index);
 * @method int tabNumPages(uiTab *t);
 * @method int tabMargined(uiTab *t, int page);
 * @method void tabSetMargined(uiTab *t, int page, int margined);
 * @method uiTab *newTab(void);
 * @method char *groupTitle(uiGroup *g);
 * @method void groupSetTitle(uiGroup *g, const char *title);
 * @method void groupSetChild(uiGroup *g, uiControl *c);
 * @method int groupMargined(uiGroup *g);
 * @method void groupSetMargined(uiGroup *g, int margined);
 * @method uiGroup *newGroup(const char *title);
 * @method int spinboxValue(uiSpinbox *s);
 * @method void spinboxSetValue(uiSpinbox *s, int value);
 * @method void spinboxOnChanged(uiSpinbox *s, void (*f)(uiSpinbox *s, void *data), void *data);
 * @method uiSpinbox *newSpinbox(int min, int max);
 * @method int sliderValue(uiSlider *s);
 * @method void sliderSetValue(uiSlider *s, int value);
 * @method void sliderOnChanged(uiSlider *s, void (*f)(uiSlider *s, void *data), void *data);
 * @method uiSlider *newSlider(int min, int max);
 * @method int progressBarValue(uiProgressBar *p);
 * @method void progressBarSetValue(uiProgressBar *p, int n);
 * @method uiProgressBar *newProgressBar(void);
 * @method uiSeparator *newHorizontalSeparator(void);
 * @method uiSeparator *newVerticalSeparator(void);
 * @method void comboboxAppend(uiCombobox *c, const char *text);
 * @method int comboboxSelected(uiCombobox *c);
 * @method void comboboxSetSelected(uiCombobox *c, int n);
 * @method void comboboxOnSelected(uiCombobox *c, void (*f)(uiCombobox *c, void *data), void *data);
 * @method uiCombobox *newCombobox(void);
 * @method void editableComboboxAppend(uiEditableCombobox *c, const char *text);
 * @method char *editableComboboxText(uiEditableCombobox *c);
 * @method void editableComboboxSetText(uiEditableCombobox *c, const char *text);
 * @method void editableComboboxOnChanged(uiEditableCombobox *c, void (*f)(uiEditableCombobox *c, void *data), void *data);
 * @method uiEditableCombobox *newEditableCombobox(void);
 * @method void radioButtonsAppend(uiRadioButtons *r, const char *text);
 * @method int radioButtonsSelected(uiRadioButtons *r);
 * @method void radioButtonsSetSelected(uiRadioButtons *r, int n);
 * @method void radioButtonsOnSelected(uiRadioButtons *r, void (*f)(uiRadioButtons *, void *), void *data);
 * @method uiRadioButtons *newRadioButtons(void);
 * @method void dateTimePickerTime(uiDateTimePicker *d, struct tm *time);
 * @method void dateTimePickerSetTime(uiDateTimePicker *d, const struct tm *time);
 * @method void dateTimePickerOnChanged(uiDateTimePicker *d, void (*f)(uiDateTimePicker *, void *), void *data);
 * @method uiDateTimePicker *newDateTimePicker(void);
 * @method uiDateTimePicker *newDatePicker(void);
 * @method uiDateTimePicker *newTimePicker(void);
 * @method char *multilineEntryText(uiMultilineEntry *e);
 * @method void multilineEntrySetText(uiMultilineEntry *e, const char *text);
 * @method void multilineEntryAppend(uiMultilineEntry *e, const char *text);
 * @method void multilineEntryOnChanged(uiMultilineEntry *e, void (*f)(uiMultilineEntry *e, void *data), void *data);
 * @method int multilineEntryReadOnly(uiMultilineEntry *e);
 * @method void multilineEntrySetReadOnly(uiMultilineEntry *e, int readonly);
 * @method uiMultilineEntry *newMultilineEntry(void);
 * @method uiMultilineEntry *newNonWrappingMultilineEntry(void);
 * @method void menuItemEnable(uiMenuItem *m);
 * @method void menuItemDisable(uiMenuItem *m);
 * @method void menuItemOnClicked(uiMenuItem *m, void (*f)(uiMenuItem *sender, uiWindow *window, void *data), void *data);
 * @method int menuItemChecked(uiMenuItem *m);
 * @method void menuItemSetChecked(uiMenuItem *m, int checked);
 * @method uiMenuItem *menuAppendItem(uiMenu *m, const char *name);
 * @method uiMenuItem *menuAppendCheckItem(uiMenu *m, const char *name);
 * @method uiMenuItem *menuAppendQuitItem(uiMenu *m);
 * @method uiMenuItem *menuAppendPreferencesItem(uiMenu *m);
 * @method uiMenuItem *menuAppendAboutItem(uiMenu *m);
 * @method void menuAppendSeparator(uiMenu *m);
 * @method uiMenu *newMenu(const char *name);
 * @method char *openFile(uiWindow *parent);
 * @method char *saveFile(uiWindow *parent);
 * @method void msgBox(uiWindow *parent, const char *title, const char *description);
 * @method void msgBoxError(uiWindow *parent, const char *title, const char *description);
 * @method void areaSetSize(uiArea *a, int width, int height);
 * @method void areaQueueRedrawAll(uiArea *a);
 * @method void areaScrollTo(uiArea *a, double x, double y, double width, double height);
 * @method void areaBeginUserWindowMove(uiArea *a);
 * @method void areaBeginUserWindowResize(uiArea *a, uiWindowResizeEdge edge);
 * @method uiArea *newArea(uiAreaHandler *ah);
 * @method uiArea *newScrollingArea(uiAreaHandler *ah, int width, int height);
 * @method uiDrawPath *drawNewPath(uiDrawFillMode fillMode);
 * @method void drawFreePath(uiDrawPath *p);
 * @method void drawPathNewFigure(uiDrawPath *p, double x, double y);
 * @method void drawPathNewFigureWithArc(uiDrawPath *p, double xCenter, double yCenter, double radius, double startAngle, double sweep, int negative);
 * @method void drawPathLineTo(uiDrawPath *p, double x, double y);
 * @method void drawPathArcTo(uiDrawPath *p, double xCenter, double yCenter, double radius, double startAngle, double sweep, int negative);
 * @method void drawPathBezierTo(uiDrawPath *p, double c1x, double c1y, double c2x, double c2y, double endX, double endY);
 * @method void drawPathCloseFigure(uiDrawPath *p);
 * @method void drawPathAddRectangle(uiDrawPath *p, double x, double y, double width, double height);
 * @method void drawPathEnd(uiDrawPath *p);
 * @method void drawStroke(uiDrawContext *c, uiDrawPath *path, uiDrawBrush *b, uiDrawStrokeParams *p);
 * @method void drawFill(uiDrawContext *c, uiDrawPath *path, uiDrawBrush *b);
 * @method void drawMatrixSetIdentity(uiDrawMatrix *m);
 * @method void drawMatrixTranslate(uiDrawMatrix *m, double x, double y);
 * @method void drawMatrixScale(uiDrawMatrix *m, double xCenter, double yCenter, double x, double y);
 * @method void drawMatrixRotate(uiDrawMatrix *m, double x, double y, double amount);
 * @method void drawMatrixSkew(uiDrawMatrix *m, double x, double y, double xamount, double yamount);
 * @method void drawMatrixMultiply(uiDrawMatrix *dest, uiDrawMatrix *src);
 * @method int drawMatrixInvertible(uiDrawMatrix *m);
 * @method int drawMatrixInvert(uiDrawMatrix *m);
 * @method void drawMatrixTransformPoint(uiDrawMatrix *m, double *x, double *y);
 * @method void drawMatrixTransformSize(uiDrawMatrix *m, double *x, double *y);
 * @method void drawTransform(uiDrawContext *c, uiDrawMatrix *m);
 * @method void drawClip(uiDrawContext *c, uiDrawPath *path);
 * @method void drawSave(uiDrawContext *c);
 * @method void drawRestore(uiDrawContext *c);
 * @method void freeAttribute(uiAttribute *a);
 * @method uiattributetype attributeGetType(const uiAttribute *a);
 * @method uiAttribute *newFamilyAttribute(const char *family);
 * @method string *attributeFamily(const uiAttribute *a);
 * @method uiAttribute *newSizeAttribute(double size);
 * @method double attributeSize(const uiAttribute *a);
 * @method uiAttribute *newWeightAttribute(uiTextWeight weight);
 * @method uitextweight attributeWeight(const uiAttribute *a);
 * @method uiAttribute *newItalicAttribute(uiTextItalic italic);
 * @method uitextitalic attributeItalic(const uiAttribute *a);
 * @method uiAttribute *newStretchAttribute(uiTextStretch stretch);
 * @method uitextstretch attributeStretch(const uiAttribute *a);
 * @method uiAttribute *newColorAttribute(double r, double g, double b, double a);
 * @method void attributeColor(const uiAttribute *a, double *r, double *g, double *b, double *alpha);
 * @method uiAttribute *newBackgroundAttribute(double r, double g, double b, double a);
 * @method uiAttribute *newUnderlineAttribute(uiUnderline u);
 * @method uiunderline attributeUnderline(const uiAttribute *a);
 * @method uiAttribute *newUnderlineColorAttribute(uiUnderlineColor u, double r, double g, double b, double a);
 * @method void attributeUnderlineColor(const uiAttribute *a, uiUnderlineColor *u, double *r, double *g, double *b, double *alpha);
 * @method uiOpenTypeFeatures *newOpenTypeFeatures(void);
 * @method void freeOpenTypeFeatures(uiOpenTypeFeatures *otf);
 * @method uiOpenTypeFeatures *openTypeFeaturesClone(const uiOpenTypeFeatures *otf);
 * @method void openTypeFeaturesAdd(uiOpenTypeFeatures *otf, char a, char b, char c, char d, uint32_t value);
 * @method void openTypeFeaturesRemove(uiOpenTypeFeatures *otf, char a, char b, char c, char d);
 * @method int openTypeFeaturesGet(const uiOpenTypeFeatures *otf, char a, char b, char c, char d, uint32_t *value);
 * @method void openTypeFeaturesForEach(const uiOpenTypeFeatures *otf, uiOpenTypeFeaturesForEachFunc f, void *data);
 * @method uiAttribute *newFeaturesAttribute(const uiOpenTypeFeatures *otf);
 * @method const openTypeFeatures *uiAttributeFeatures(const uiAttribute *a);
 * @method uiAttributedString *newAttributedString(const char *initialString);
 * @method void freeAttributedString(uiAttributedString *s);
 * @method string *attributedStringString(const uiAttributedString *s);
 * @method size_t attributedStringLen(const uiAttributedString *s);
 * @method void attributedStringAppendUnattributed(uiAttributedString *s, const char *str);
 * @method void attributedStringInsertAtUnattributed(uiAttributedString *s, const char *str, size_t at);
 * @method void attributedStringDelete(uiAttributedString *s, size_t start, size_t end);
 * @method void attributedStringSetAttribute(uiAttributedString *s, uiAttribute *a, size_t start, size_t end);
 * @method void attributedStringForEachAttribute(const uiAttributedString *s, uiAttributedStringForEachAttributeFunc f, void *data);
 * @method size_t attributedStringNumGraphemes(uiAttributedString *s);
 * @method size_t attributedStringByteIndexToGrapheme(uiAttributedString *s, size_t pos);
 * @method size_t attributedStringGraphemeToByteIndex(uiAttributedString *s, size_t pos);
 * @method uiDrawTextLayout *drawNewTextLayout(uiDrawTextLayoutParams *params);
 * @method void drawFreeTextLayout(uiDrawTextLayout *tl);
 * @method void drawText(uiDrawContext *c, uiDrawTextLayout *tl, double x, double y);
 * @method void drawTextLayoutExtents(uiDrawTextLayout *tl, double *width, double *height);

 * @method void fontButtonFont(uiFontButton *b, uiFontDescriptor *desc);
 * @method void fontButtonOnChanged(uiFontButton *b, void (*f)(uiFontButton *, void *), void *data);
 * @method uiFontButton *newFontButton(void);
 * @method void freeFontButtonFont(uiFontDescriptor *desc);
 * @method void colorButtonColor(uiColorButton *b, double *r, double *g, double *bl, double *a);
 * @method void colorButtonSetColor(uiColorButton *b, double r, double g, double bl, double a);
 * @method void colorButtonOnChanged(uiColorButton *b, void (*f)(uiColorButton *, void *), void *data);
 * @method uiColorButton *newColorButton(void);
 * @method void formAppend(uiForm *f, const char *label, uiControl *c, int stretchy);
 * @method void formDelete(uiForm *f, int index);
 * @method int formPadded(uiForm *f);
 * @method void formSetPadded(uiForm *f, int padded);
 * @method uiForm *newForm(void);
 * @method void gridAppend(uiGrid *g, uiControl *c, int left, int top, int xspan, int yspan, int hexpand, uiAlign halign, int vexpand, uiAlign valign);
 * @method void gridInsertAt(uiGrid *g, uiControl *c, uiControl *existing, uiAt at, int xspan, int yspan, int hexpand, uiAlign halign, int vexpand, uiAlign valign);
 * @method int gridPadded(uiGrid *g);
 * @method void gridSetPadded(uiGrid *g, int padded);
 * @method uiGrid *newGrid(void);
 * @method uiImage *newImage(double width, double height);
 * @method void freeImage(uiImage *i);
 * @method void imageAppend(uiImage *i, void *pixels, int pixelWidth, int pixelHeight, int byteStride);
 * @method void freeTableValue(uiTableValue *v);
 * @method uitablevaluetype tableValueGetType(const uiTableValue *v);
 * @method uiTableValue *newTableValueString(const char *str);
 * @method string *tableValueString(const uiTableValue *v);
 * @method uiTableValue *newTableValueImage(uiImage *img);
 * @method uiImage *tableValueImage(const uiTableValue *v);
 * @method uiTableValue *newTableValueInt(int i);
 * @method int tableValueInt(const uiTableValue *v);
 * @method uiTableValue *newTableValueColor(double r, double g, double b, double a);
 * @method void tableValueColor(const uiTableValue *v, double *r, double *g, double *b, double *a);
 * @method uiTableModel *newTableModel(uiTableModelHandler *mh);
 * @method void freeTableModel(uiTableModel *m);
 * @method void tableModelRowInserted(uiTableModel *m, int newIndex);
 * @method void tableModelRowChanged(uiTableModel *m, int index);
 * @method void tableModelRowDeleted(uiTableModel *m, int oldIndex);
 * @method void tableAppendTextColumn(uiTable *t,const char *name,int textModelColumn,int textEditableModelColumn,uiTableTextColumnOptionalParams *textParams);
 * @method void tableAppendImageColumn(uiTable *t,const char *name,int imageModelColumn);
 * @method void tableAppendImageTextColumn(uiTable *t,const char *name,int imageModelColumn,int textModelColumn,int textEditableModelColumn,uiTableTextColumnOptionalParams *textParams);
 * @method void tableAppendCheckboxColumn(uiTable *t,const char *name,int checkboxModelColumn,int checkboxEditableModelColumn);
 * @method void tableAppendCheckboxTextColumn(uiTable *t,const char *name,int checkboxModelColumn,int checkboxEditableModelColumn,int textModelColumn,int textEditableModelColumn,uiTableTextColumnOptionalParams *textParams);
 * @method void tableAppendProgressBarColumn(uiTable *t,const char *name,int progressModelColumn);
 * @method void tableAppendButtonColumn(uiTable *t,const char *name,int buttonModelColumn,int buttonClickableModelColumn);
 * @method uiTable *newTable(uiTableParams *params);
 */
class UI
{
    /**
     * π value
     * @var float
     */
    const PI = 3.14159265358979323846264338327950288419716939937510582097494459;
    const ALIGN_FILL = 0;
    const ALIGN_START = 1;
    const ALIGN_CENTER = 2;
    const ALIGN_END = 3;
    const FOR_EACH_CONTINUE = 0;
    const FOR_EACH_STOP = 1;
    const WINDOW_RESIZE_EDGE_LEFT = 0;
    const WINDOW_RESIZE_EDGE_TOP = 1;
    const WINDOW_RESIZE_EDGE_RIGHT = 2;
    const WINDOW_RESIZE_EDGE_BOTTOM = 3;
    const WINDOW_RESIZE_EDGE_TOP_LEFT = 4;
    const WINDOW_RESIZE_EDGE_TOP_RIGHT = 5;
    const WINDOW_RESIZE_EDGE_BOTTOM_LEFT = 6;
    const WINDOW_RESIZE_EDGE_BOTTOM_RIGHT = 7;
    const DRAW_BRUSH_TYPE_SOLID = 0;
    const DRAW_BRUSH_TYPE_LINEAR_GRADIENT = 1;
    const DRAW_BRUSH_TYPE_RADIAL_GRADIENT = 2;
    const DRAW_BRUSH_TYPE_IMAGE = 3;
    const DRAW_LINE_CAP_FLAT = 0;
    const DRAW_LINE_CAP_ROUND = 1;
    const DRAW_LINE_CAP_SQUARE = 2;
    const DRAW_LINE_JOIN_MITER = 0;
    const DRAW_LINE_JOIN_ROUND = 1;
    const DRAW_LINE_JOIN_BEVEL = 2;
    const DRAW_FILL_MODE_WINDING = 0;
    const DRAW_FILL_MODE_ALTERNATE = 1;
    const ATTRIBUTE_TYPE_FAMILY = 0;
    const ATTRIBUTE_TYPE_SIZE = 1;
    const ATTRIBUTE_TYPE_WEIGHT = 2;
    const ATTRIBUTE_TYPE_ITALIC = 3;
    const ATTRIBUTE_TYPE_STRETCH = 4;
    const ATTRIBUTE_TYPE_COLOR = 4;
    const ATTRIBUTE_TYPE_BACKGROUND = 5;
    const ATTRIBUTE_TYPE_UNDERLINE = 6;
    const ATTRIBUTE_TYPE_UNDERLINE_COLOR = 7;
    const ATTRIBUTE_TYPE_FEATURES = 8;
    const TEXT_WEIGHT_MINIMUM = 0;
    const TEXT_WEIGHT_THIN = 100;
    const TEXT_WEIGHT_ULTRA_LIGHT = 200;
    const TEXT_WEIGHT_LIGHT = 300;
    const TEXT_WEIGHT_BOOK = 350;
    const TEXT_WEIGHT_NORMAL = 400;
    const TEXT_WEIGHT_MEDIUM = 500;
    const TEXT_WEIGHT_SEMI_BOLD = 600;
    const TEXT_WEIGHT_BOLD = 700;
    const TEXT_WEIGHT_ULTRA_BOLD = 800;
    const TEXT_WEIGHT_HEAVY = 900;
    const TEXT_WEIGHT_ULTRA_HEAVY = 950;
    const TEXT_WEIGHT_MAXIMUM = 1000;
    const TEXT_ITALIC_NORMAL = 0;
    const TEXT_ITALIC_OBLIQUE = 1;
    const TEXT_ITALIC_ITALIC = 2;
    const TEXT_STRETCH_ULTRA_CONDENSED = 0;
    const TEXT_STRETCH_EXTRA_CONDENSED = 1;
    const TEXT_STRETCH_CONDENSED = 2;
    const TEXT_STRETCH_SEMI_CONDENSED = 3;
    const TEXT_STRETCH_NORMAL = 4;
    const TEXT_STRETCH_SEMI_EXPANDED = 5;
    const TEXT_STRETCH_EXPANDED = 6;
    const TEXT_STRETCH_EXTRA_EXPANDED = 7;
    const TEXT_STRETCH_ULTRA_EXPANDED = 8;
    const UNDERLINE_NONE = 0;
    const UNDERLINE_SINGLE = 1;
    const UNDERLINE_DOUBLE = 2;
    const UNDERLINE_SUGGESTION = 3;
    const UNDERLINE_COLOR_CUSTOM = 0;
    const UNDERLINE_COLOR_SPELLING = 1;
    const UNDERLINE_COLOR_GRAMMAR = 2;
    const UNDERLINE_COLOR_AUXILIARY = 3;
    const DRAW_TEXT_ALIGN_LEFT = 0;
    const DRAW_TEXT_ALIGN_CENTER = 1;
    const DRAW_TEXT_ALIGN_RIGHT = 2;
    const MODIFIER_CTRL = 1 << 0;
    const MODIFIER_ALT = 1 << 1;
    const MODIFIER_SHIFT = 1 << 2;
    const MODIFIER_SUPER = 1 << 3;
    const EXT_KEY_ESCAPE = 1;
    const EXT_KEY_INSERT = 2;
    const EXT_KEY_DELETE = 3;
    const EXT_KEY_HOME = 4;
    const EXT_KEY_END = 5;
    const EXT_KEY_PAGE_UP = 6;
    const EXT_KEY_PAGE_DOWN = 7;
    const EXT_KEY_UP = 8;
    const EXT_KEY_DOWN = 9;
    const EXT_KEY_LEFT = 10;
    const EXT_KEY_RIGHT = 11;
    const EXT_KEY_F1 = 12;
    const EXT_KEY_F2 = 13;
    const EXT_KEY_F3 = 14;
    const EXT_KEY_F4 = 15;
    const EXT_KEY_F5 = 16;
    const EXT_KEY_F6 = 17;
    const EXT_KEY_F7 = 18;
    const EXT_KEY_F8 = 19;
    const EXT_KEY_F9 = 20;
    const EXT_KEY_F10 = 21;
    const EXT_KEY_F11 = 22;
    const EXT_KEY_F12 = 23;
    const EXT_KEY_N0 = 24;
    const EXT_KEY_N1 = 25;
    const EXT_KEY_N2 = 26;
    const EXT_KEY_N3 = 27;
    const EXT_KEY_N4 = 28;
    const EXT_KEY_N5 = 29;
    const EXT_KEY_N6 = 30;
    const EXT_KEY_N7 = 31;
    const EXT_KEY_N8 = 32;
    const EXT_KEY_N9 = 33;
    const EXT_KEY_N_DOT = 34;
    const EXT_KEY_N_ENTER = 35;
    const EXT_KEY_N_ADD = 36;
    const EXT_KEY_N_SUBTRACT = 37;
    const EXT_KEY_N_MULTIPLY = 38;
    const EXT_KEY_N_DIVIDE = 39;
    const AT_LEADING = 0;
    const AT_TOP = 1;
    const AT_TRAILING = 2;
    const AT_BOTTOM = 3;
    const TABLE_VALUE_TYPE_STRING = 0;
    const TABLE_VALUE_TYPE_IMAGE = 1;
    const TABLE_VALUE_TYPE_INT = 2;
    const TABLE_VALUE_TYPE_COLOR = 3;
    const DRAW_DEFAULT_MITER_LIMIT = 10.0;
    const TABLE_MODEL_COLUMN_NEVER_EDITABLE = -1;
    const TABLE_MODEL_COLUMN_ALWAYS_EDITABLE = -2;

    /**
     * @var FFI
     */
    private static $ffi;

    /**
     * @var Struct
     */
    public $struct = null;

    /**
     * @param string $dll The libui dynamic link library path
     */
    public function __construct(string $dll = '', bool $new = false)
    {
        if (!$new && self::$ffi instanceof FFI) {
            return self::$ffi;
        }
        $code = file_get_contents(__DIR__ . '/include/libui.h');
        if (!$dll) {
            $dll = $this->findDll();
        }
        self::$ffi = FFI::cdef($code, $dll);
        $this->struct = $this->struct();
        $this->autoload();
    }

    protected function findDll(): string
    {
        switch (PHP_OS_FAMILY) {
            case 'Linux':
            case 'BSD':
                $path = ['/usr/lib', '/usr/lib64', '/usr/local/lib', '/usr/local/lib64'];
                foreach ($path as $p) {
                    $path = "$p/libui.so";
                    if (file_exists($path)) {
                        return $path;
                    }
                }
                break;
            case 'Darwin':
                $path = '/usr/lib/libui.dylib';
                if (file_exists($path)) {
                    return $path;
                }
                break;
            case 'Windows':
                $path = 'C:\Program Files\libui\libui.dll';
                if (file_exists($path)) {
                    return $path;
                }
                break;
        }
        $path = dirname(realpath($_SERVER['PHP_SELF'])) . DIRECTORY_SEPARATOR . 'libui.' . PHP_SHLIB_SUFFIX;
        return $path;
    }

    /**
     * most return FFI\CData of uiControl
     *
     * @return FFI\CData
     */
    public function __call($name, $arg = [])
    {
        $name = 'ui' . ucfirst($name);
        $number = count($arg);
        switch ($number) {
            case 0:
                return self::$ffi->$name();
            case 1:
                return self::$ffi->$name($arg[0]);
            case 2:
                return self::$ffi->$name($arg[0], $arg[1]);
            case 3:
                return self::$ffi->$name($arg[0], $arg[1], $arg[2]);
            case 4:
                return self::$ffi->$name($arg[0], $arg[1], $arg[2], $arg[3]);
            case 5:
                return self::$ffi->$name($arg[0], $arg[1], $arg[2], $arg[3], $arg[4]);
            default:
                return call_user_func_array([$this->ffi(), $name], $arg);
        }
    }

    public static function __callStatic($name, $arguments)
    {
        return call_user_func_array(['FFI', $name], $arguments);
    }

    /**
     * init ui
     *
     * @return string
     */
    public function init(): string
    {
        $o = self::$ffi->new('uiInitOptions');
        FFI::memset(FFI::addr($o), 0, FFI::sizeof($o));
        $msg = self::$ffi->uiInit(FFI::addr($o));
        if ($msg) {
            return $this->string($msg);
        }
        return $msg;
    }

    public function string(FFI\CData $data): string
    {
        return self::$ffi::string($data);
    }

    public function addr($data): CData
    {
        return self::$ffi::addr($data);
    }

    /**
     *
     * @return FFI
     */
    public function ffi(): FFI
    {
        return self::$ffi;
    }

    /**
     * @return FFI\CData
     */
    public function new($type, $owned = TRUE, $persistent = FALSE): CData
    {
        return self::$ffi->new($type, $owned, $persistent);
    }

    public function ptr($type, $owned = true, $persistent = false): CData
    {
        return self::$ffi->new("$type*", $owned, $persistent);
    }

    /**
     * @return FFI\CType
     */
    public function type($type): CType
    {
        return self::$ffi->type($type);
    }

    /**
     * @return FFI\CData
     */
    public function cast($type, &$ptr): CData
    {
        return self::$ffi->cast($type, $ptr);
    }

    /**
     * @return FFI\CData
     */
    public function castPtr($dst, $t): CData
    {
        return FFI::addr($this->cast($dst, $t[0]));
    }

    /**
     *  @param bool $isPtr whether return pointer
     *
     */
    public function newTm($isPtr = false): CData
    {
        $type = self::$ffi->new('tm');
        if (!$isPtr) {
            return $type;
        }
        return FFI::addr($type);
    }

    public function struct(): Struct
    {
        return new Struct;
    }

    /**
     * @param array $config  similar : 
     * <code>
     * [
     *    'title' => 'title',
     *    'width' => 100, 
     *    'height' => 100,
     *    'border' => 0,
     *    'margin' => 0,
     *    'quit' => ['quit_callable','data'], 
     *    'close' => ['close_callable'], 
     *    'resize' => ['resize_callable','data'],
     *    'menu' => [
     *                 [
     *                   'title' => 'menu_name', 
     *                  'childs' => [], 
     *                  'click' => ['click_callable', 'callback_data'
     *                 ]
     *               ],
     *    'body' => [
     *        [ 'name' =>'box', 'attr' => ['padded' => 0, 
     *                'dir' => 'v',
     *                'childs' => [....]],
     *        ['name' => 'table', 'attr' => [
     *                    'th' => [
     *                        ['title' => 'Colum1', 'idx' => 0, 'type' => 'text'],
     *                         ['title' => 'Colum2', 'idx' => 1, 'type' => 'button'],
     *                        ['title' => 'Colum3', 'idx' => 2, 'type' => 'text'],
     *                   ],
     *                   'tbody' => [
     *                       [1, 'button0', 3],
     *                       [1, 'button1', 3],
     *                       [1, 'button2', 3]
     *                   ],
     *               ]
     *    ]
     * </code>
     */
    public function build(array $config): UIBuild
    {
        return new UIBuild($this, $config);
    }

    public function autoload()
    {
        spl_autoload_register(function ($class) {
            $classInfo = explode('\\', $class);
            array_shift($classInfo);
            array_unshift($classInfo, __DIR__);
            $path = join(DIRECTORY_SEPARATOR, $classInfo) . '.php';
            if (file_exists($path)) {
                include_once $path;
            }
        });
    }

}

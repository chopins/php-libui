struct tm {
    int tm_sec;
    int tm_min;
    int tm_hour;
    int tm_mday;
    int tm_mon;
    int tm_year;
    int tm_wday;
    int tm_yday; 
    int tm_isdst;
    long int tm_gmtoff;
    const char *tm_zone;
};
typedef struct tm tm;

/**
 * Below Code license
 * *****************************************************************************************************
 * Copyright (c) 2014 Pietro Gagliardi
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and 
 * associated documentation files (the "Software"), to deal in the Software without restriction, including 
 * without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell 
 * copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the 
 * following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in all copies or substantial
 * portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT 
 * LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO
 * EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, 
 * WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE 
 * SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 * 
 * (this is called the MIT License or Expat License; see http://www.opensource.org/licenses/MIT)
 * *********************************************************************************************************
 */
typedef unsigned int uiForEach;enum{
    uiForEachContinue,
    uiForEachStop,
};
typedef struct uiInitOptions uiInitOptions;
struct uiInitOptions {
    size_t Size;
};
extern  const char *uiInit(uiInitOptions *options);
extern  void uiUninit(void);
extern  void uiFreeInitError(const char *err);
extern  void uiMain(void);
extern  void uiMainSteps(void);
extern  int uiMainStep(int wait);
extern  void uiQuit(void);
extern  void uiQueueMain(void (*f)(void *data), void *data);
extern  void uiTimer(int milliseconds, int (*f)(void *data), void *data);
extern  void uiOnShouldQuit(int (*f)(void *data), void *data);
extern  void uiFreeText(char *text);
typedef struct uiControl uiControl;
struct uiControl {
    uint32_t Signature;
    uint32_t OSSignature;
    uint32_t TypeSignature;
    void (*Destroy)(uiControl *);
    uintptr_t (*Handle)(uiControl *);
    uiControl *(*Parent)(uiControl *);
    void (*SetParent)(uiControl *, uiControl *);
    int (*Toplevel)(uiControl *);
    int (*Visible)(uiControl *);
    void (*Show)(uiControl *);
    void (*Hide)(uiControl *);
    int (*Enabled)(uiControl *);
    void (*Enable)(uiControl *);
    void (*Disable)(uiControl *);
};
extern  void uiControlDestroy(uiControl *);
extern  uintptr_t uiControlHandle(uiControl *);
extern  uiControl *uiControlParent(uiControl *);
extern  void uiControlSetParent(uiControl *, uiControl *);
extern  int uiControlToplevel(uiControl *);
extern  int uiControlVisible(uiControl *);
extern  void uiControlShow(uiControl *);
extern  void uiControlHide(uiControl *);
extern  int uiControlEnabled(uiControl *);
extern  void uiControlEnable(uiControl *);
extern  void uiControlDisable(uiControl *);
extern  uiControl *uiAllocControl(size_t n, uint32_t OSsig, uint32_t typesig, const char *typenamestr);
extern  void uiFreeControl(uiControl *);
extern  void uiControlVerifySetParent(uiControl *, uiControl *);
extern  int uiControlEnabledToUser(uiControl *);
extern  void uiUserBugCannotSetParentOnToplevel(const char *type);
typedef struct  uiControl uiWindow ;
extern  char *uiWindowTitle(uiWindow *w);
extern  void uiWindowSetTitle(uiWindow *w, const char *title);
extern  void uiWindowContentSize(uiWindow *w, int *width, int *height);
extern  void uiWindowSetContentSize(uiWindow *w, int width, int height);
extern  int uiWindowFullscreen(uiWindow *w);
extern  void uiWindowSetFullscreen(uiWindow *w, int fullscreen);
extern  void uiWindowOnContentSizeChanged(uiWindow *w, void (*f)(uiWindow *, void *), void *data);
extern  void uiWindowOnClosing(uiWindow *w, int (*f)(uiWindow *w, void *data), void *data);
extern  int uiWindowBorderless(uiWindow *w);
extern  void uiWindowSetBorderless(uiWindow *w, int borderless);
extern  void uiWindowSetChild(uiWindow *w, uiControl *child);
extern  int uiWindowMargined(uiWindow *w);
extern  void uiWindowSetMargined(uiWindow *w, int margined);
extern  uiWindow *uiNewWindow(const char *title, int width, int height, int hasMenubar);
typedef struct  uiControl uiButton ;
extern  char *uiButtonText(uiButton *b);
extern  void uiButtonSetText(uiButton *b, const char *text);
extern  void uiButtonOnClicked(uiButton *b, void (*f)(uiButton *b, void *data), void *data);
extern  uiButton *uiNewButton(const char *text);
typedef struct  uiControl uiBox ;
extern  void uiBoxAppend(uiBox *b, uiControl *child, int stretchy);
extern  void uiBoxDelete(uiBox *b, int index);
extern  int uiBoxPadded(uiBox *b);
extern  void uiBoxSetPadded(uiBox *b, int padded);
extern  uiBox *uiNewHorizontalBox(void);
extern  uiBox *uiNewVerticalBox(void);
typedef struct  uiControl uiCheckbox ;
extern  char *uiCheckboxText(uiCheckbox *c);
extern  void uiCheckboxSetText(uiCheckbox *c, const char *text);
extern  void uiCheckboxOnToggled(uiCheckbox *c, void (*f)(uiCheckbox *c, void *data), void *data);
extern  int uiCheckboxChecked(uiCheckbox *c);
extern  void uiCheckboxSetChecked(uiCheckbox *c, int checked);
extern  uiCheckbox *uiNewCheckbox(const char *text);
typedef struct  uiControl uiEntry ;
extern  char *uiEntryText(uiEntry *e);
extern  void uiEntrySetText(uiEntry *e, const char *text);
extern  void uiEntryOnChanged(uiEntry *e, void (*f)(uiEntry *e, void *data), void *data);
extern  int uiEntryReadOnly(uiEntry *e);
extern  void uiEntrySetReadOnly(uiEntry *e, int readonly);
extern  uiEntry *uiNewEntry(void);
extern  uiEntry *uiNewPasswordEntry(void);
extern  uiEntry *uiNewSearchEntry(void);
typedef struct  uiControl uiLabel ;
extern  char *uiLabelText(uiLabel *l);
extern  void uiLabelSetText(uiLabel *l, const char *text);
extern  uiLabel *uiNewLabel(const char *text);
typedef struct  uiControl uiTab ;
extern  void uiTabAppend(uiTab *t, const char *name, uiControl *c);
extern  void uiTabInsertAt(uiTab *t, const char *name, int before, uiControl *c);
extern  void uiTabDelete(uiTab *t, int index);
extern  int uiTabNumPages(uiTab *t);
extern  int uiTabMargined(uiTab *t, int page);
extern  void uiTabSetMargined(uiTab *t, int page, int margined);
extern  uiTab *uiNewTab(void);
typedef struct  uiControl uiGroup ;
extern  char *uiGroupTitle(uiGroup *g);
extern  void uiGroupSetTitle(uiGroup *g, const char *title);
extern  void uiGroupSetChild(uiGroup *g, uiControl *c);
extern  int uiGroupMargined(uiGroup *g);
extern  void uiGroupSetMargined(uiGroup *g, int margined);
extern  uiGroup *uiNewGroup(const char *title);
typedef struct  uiControl uiSpinbox ;
extern  int uiSpinboxValue(uiSpinbox *s);
extern  void uiSpinboxSetValue(uiSpinbox *s, int value);
extern  void uiSpinboxOnChanged(uiSpinbox *s, void (*f)(uiSpinbox *s, void *data), void *data);
extern  uiSpinbox *uiNewSpinbox(int min, int max);
typedef struct  uiControl uiSlider ;
extern  int uiSliderValue(uiSlider *s);
extern  void uiSliderSetValue(uiSlider *s, int value);
extern  void uiSliderOnChanged(uiSlider *s, void (*f)(uiSlider *s, void *data), void *data);
extern  uiSlider *uiNewSlider(int min, int max);
typedef struct  uiControl uiProgressBar ;
extern  int uiProgressBarValue(uiProgressBar *p);
extern  void uiProgressBarSetValue(uiProgressBar *p, int n);
extern  uiProgressBar *uiNewProgressBar(void);
typedef struct  uiControl uiSeparator ;
extern  uiSeparator *uiNewHorizontalSeparator(void);
extern  uiSeparator *uiNewVerticalSeparator(void);
typedef struct  uiControl uiCombobox ;
extern  void uiComboboxAppend(uiCombobox *c, const char *text);
extern  int uiComboboxSelected(uiCombobox *c);
extern  void uiComboboxSetSelected(uiCombobox *c, int n);
extern  void uiComboboxOnSelected(uiCombobox *c, void (*f)(uiCombobox *c, void *data), void *data);
extern  uiCombobox *uiNewCombobox(void);
typedef struct  uiControl uiEditableCombobox ;
extern  void uiEditableComboboxAppend(uiEditableCombobox *c, const char *text);
extern  char *uiEditableComboboxText(uiEditableCombobox *c);
extern  void uiEditableComboboxSetText(uiEditableCombobox *c, const char *text);
extern  void uiEditableComboboxOnChanged(uiEditableCombobox *c, void (*f)(uiEditableCombobox *c, void *data), void *data);
extern  uiEditableCombobox *uiNewEditableCombobox(void);
typedef struct  uiControl uiRadioButtons ;
extern  void uiRadioButtonsAppend(uiRadioButtons *r, const char *text);
extern  int uiRadioButtonsSelected(uiRadioButtons *r);
extern  void uiRadioButtonsSetSelected(uiRadioButtons *r, int n);
extern  void uiRadioButtonsOnSelected(uiRadioButtons *r, void (*f)(uiRadioButtons *, void *), void *data);
extern  uiRadioButtons *uiNewRadioButtons(void);

typedef struct  uiControl uiDateTimePicker ;
extern  void uiDateTimePickerTime(uiDateTimePicker *d, struct tm *time);
extern  void uiDateTimePickerSetTime(uiDateTimePicker *d, const struct tm *time);
extern  void uiDateTimePickerOnChanged(uiDateTimePicker *d, void (*f)(uiDateTimePicker *, void *), void *data);
extern  uiDateTimePicker *uiNewDateTimePicker(void);
extern  uiDateTimePicker *uiNewDatePicker(void);
extern  uiDateTimePicker *uiNewTimePicker(void);
typedef struct  uiControl uiMultilineEntry ;
extern  char *uiMultilineEntryText(uiMultilineEntry *e);
extern  void uiMultilineEntrySetText(uiMultilineEntry *e, const char *text);
extern  void uiMultilineEntryAppend(uiMultilineEntry *e, const char *text);
extern  void uiMultilineEntryOnChanged(uiMultilineEntry *e, void (*f)(uiMultilineEntry *e, void *data), void *data);
extern  int uiMultilineEntryReadOnly(uiMultilineEntry *e);
extern  void uiMultilineEntrySetReadOnly(uiMultilineEntry *e, int readonly);
extern  uiMultilineEntry *uiNewMultilineEntry(void);
extern  uiMultilineEntry *uiNewNonWrappingMultilineEntry(void);
typedef struct  uiControl uiMenuItem ;
extern  void uiMenuItemEnable(uiMenuItem *m);
extern  void uiMenuItemDisable(uiMenuItem *m);
extern  void uiMenuItemOnClicked(uiMenuItem *m, void (*f)(uiMenuItem *sender, uiWindow *window, void *data), void *data);
extern  int uiMenuItemChecked(uiMenuItem *m);
extern  void uiMenuItemSetChecked(uiMenuItem *m, int checked);
typedef struct  uiControl uiMenu ;
extern  uiMenuItem *uiMenuAppendItem(uiMenu *m, const char *name);
extern  uiMenuItem *uiMenuAppendCheckItem(uiMenu *m, const char *name);
extern  uiMenuItem *uiMenuAppendQuitItem(uiMenu *m);
extern  uiMenuItem *uiMenuAppendPreferencesItem(uiMenu *m);
extern  uiMenuItem *uiMenuAppendAboutItem(uiMenu *m);
extern  void uiMenuAppendSeparator(uiMenu *m);
extern  uiMenu *uiNewMenu(const char *name);
extern  char *uiOpenFile(uiWindow *parent);
extern  char *uiSaveFile(uiWindow *parent);
extern  void uiMsgBox(uiWindow *parent, const char *title, const char *description);
extern  void uiMsgBoxError(uiWindow *parent, const char *title, const char *description);
typedef struct  uiControl uiArea ;
typedef struct  uiAreaHandler uiAreaHandler ;
typedef struct  uiAreaDrawParams uiAreaDrawParams ;
typedef struct  uiAreaMouseEvent uiAreaMouseEvent ;
typedef struct  uiAreaKeyEvent uiAreaKeyEvent ;
typedef struct  uiControl uiDrawContext ;
struct uiAreaHandler {
    void (*Draw)(uiAreaHandler *, uiArea *, uiAreaDrawParams *);    void (*MouseEvent)(uiAreaHandler *, uiArea *, uiAreaMouseEvent *);    void (*MouseCrossed)(uiAreaHandler *, uiArea *, int left);
    void (*DragBroken)(uiAreaHandler *, uiArea *);
    int (*KeyEvent)(uiAreaHandler *, uiArea *, uiAreaKeyEvent *);
};
typedef unsigned int uiWindowResizeEdge; enum {
    uiWindowResizeEdgeLeft,
    uiWindowResizeEdgeTop,
    uiWindowResizeEdgeRight,
    uiWindowResizeEdgeBottom,
    uiWindowResizeEdgeTopLeft,
    uiWindowResizeEdgeTopRight,
    uiWindowResizeEdgeBottomLeft,
    uiWindowResizeEdgeBottomRight,};
extern  void uiAreaSetSize(uiArea *a, int width, int height);
extern  void uiAreaQueueRedrawAll(uiArea *a);
extern  void uiAreaScrollTo(uiArea *a, double x, double y, double width, double height);
extern  void uiAreaBeginUserWindowMove(uiArea *a);
extern  void uiAreaBeginUserWindowResize(uiArea *a, uiWindowResizeEdge edge);
extern  uiArea *uiNewArea(uiAreaHandler *ah);
extern  uiArea *uiNewScrollingArea(uiAreaHandler *ah, int width, int height);
struct uiAreaDrawParams {
    uiDrawContext *Context;    double AreaWidth;
    double AreaHeight;
    double ClipX;
    double ClipY;
    double ClipWidth;
    double ClipHeight;
};
typedef struct  uiControl uiDrawPath ;
typedef struct  uiDrawBrush uiDrawBrush ;
typedef struct  uiDrawStrokeParams uiDrawStrokeParams ;
typedef struct  uiDrawMatrix uiDrawMatrix ;
typedef struct  uiDrawBrushGradientStop uiDrawBrushGradientStop ;
typedef unsigned int uiDrawBrushType; enum {
    uiDrawBrushTypeSolid,
    uiDrawBrushTypeLinearGradient,
    uiDrawBrushTypeRadialGradient,
    uiDrawBrushTypeImage,
};
typedef unsigned int uiDrawLineCap; enum {
    uiDrawLineCapFlat,
    uiDrawLineCapRound,
    uiDrawLineCapSquare,
};
typedef unsigned int uiDrawLineJoin; enum {
    uiDrawLineJoinMiter,
    uiDrawLineJoinRound,
    uiDrawLineJoinBevel,
};
typedef unsigned int uiDrawFillMode; enum {
    uiDrawFillModeWinding,
    uiDrawFillModeAlternate,
};
struct uiDrawMatrix {
    double M11;
    double M12;
    double M21;
    double M22;
    double M31;
    double M32;
};
struct uiDrawBrush {
    uiDrawBrushType Type;    double R;
    double G;
    double B;
    double A;    double X0;    double Y0;    double X1;    double Y1;    double OuterRadius;    uiDrawBrushGradientStop *Stops;
    size_t NumStops;
};
struct uiDrawBrushGradientStop {
    double Pos;
    double R;
    double G;
    double B;
    double A;
};
struct uiDrawStrokeParams {
    uiDrawLineCap Cap;
    uiDrawLineJoin Join;    double Thickness;
    double MiterLimit;
    double *Dashes;    size_t NumDashes;
    double DashPhase;
};
extern  uiDrawPath *uiDrawNewPath(uiDrawFillMode fillMode);
extern  void uiDrawFreePath(uiDrawPath *p);
extern  void uiDrawPathNewFigure(uiDrawPath *p, double x, double y);
extern  void uiDrawPathNewFigureWithArc(uiDrawPath *p, double xCenter, double yCenter, double radius, double startAngle, double sweep, int negative);
extern  void uiDrawPathLineTo(uiDrawPath *p, double x, double y);
extern  void uiDrawPathArcTo(uiDrawPath *p, double xCenter, double yCenter, double radius, double startAngle, double sweep, int negative);
extern  void uiDrawPathBezierTo(uiDrawPath *p, double c1x, double c1y, double c2x, double c2y, double endX, double endY);
extern  void uiDrawPathCloseFigure(uiDrawPath *p);
extern  void uiDrawPathAddRectangle(uiDrawPath *p, double x, double y, double width, double height);
extern  void uiDrawPathEnd(uiDrawPath *p);
extern  void uiDrawStroke(uiDrawContext *c, uiDrawPath *path, uiDrawBrush *b, uiDrawStrokeParams *p);
extern  void uiDrawFill(uiDrawContext *c, uiDrawPath *path, uiDrawBrush *b);
extern  void uiDrawMatrixSetIdentity(uiDrawMatrix *m);
extern  void uiDrawMatrixTranslate(uiDrawMatrix *m, double x, double y);
extern  void uiDrawMatrixScale(uiDrawMatrix *m, double xCenter, double yCenter, double x, double y);
extern  void uiDrawMatrixRotate(uiDrawMatrix *m, double x, double y, double amount);
extern  void uiDrawMatrixSkew(uiDrawMatrix *m, double x, double y, double xamount, double yamount);
extern  void uiDrawMatrixMultiply(uiDrawMatrix *dest, uiDrawMatrix *src);
extern  int uiDrawMatrixInvertible(uiDrawMatrix *m);
extern  int uiDrawMatrixInvert(uiDrawMatrix *m);
extern  void uiDrawMatrixTransformPoint(uiDrawMatrix *m, double *x, double *y);
extern  void uiDrawMatrixTransformSize(uiDrawMatrix *m, double *x, double *y);
extern  void uiDrawTransform(uiDrawContext *c, uiDrawMatrix *m);
extern  void uiDrawClip(uiDrawContext *c, uiDrawPath *path);
extern  void uiDrawSave(uiDrawContext *c);
extern  void uiDrawRestore(uiDrawContext *c);
typedef struct  uiControl uiAttribute ;
extern  void uiFreeAttribute(uiAttribute *a);
typedef unsigned int uiAttributeType; enum {
    uiAttributeTypeFamily,
    uiAttributeTypeSize,
    uiAttributeTypeWeight,
    uiAttributeTypeItalic,
    uiAttributeTypeStretch,
    uiAttributeTypeColor,
    uiAttributeTypeBackground,
    uiAttributeTypeUnderline,
    uiAttributeTypeUnderlineColor,
    uiAttributeTypeFeatures,
};
extern  uiAttributeType uiAttributeGetType(const uiAttribute *a);
extern  uiAttribute *uiNewFamilyAttribute(const char *family);
extern  const char *uiAttributeFamily(const uiAttribute *a);
extern  uiAttribute *uiNewSizeAttribute(double size);
extern  double uiAttributeSize(const uiAttribute *a);
typedef unsigned int uiTextWeight; enum {
    uiTextWeightMinimum = 0,
    uiTextWeightThin = 100,
    uiTextWeightUltraLight = 200,
    uiTextWeightLight = 300,
    uiTextWeightBook = 350,
    uiTextWeightNormal = 400,
    uiTextWeightMedium = 500,
    uiTextWeightSemiBold = 600,
    uiTextWeightBold = 700,
    uiTextWeightUltraBold = 800,
    uiTextWeightHeavy = 900,
    uiTextWeightUltraHeavy = 950,
    uiTextWeightMaximum = 1000,
};
extern  uiAttribute *uiNewWeightAttribute(uiTextWeight weight);
extern  uiTextWeight uiAttributeWeight(const uiAttribute *a);
typedef unsigned int uiTextItalic; enum {
    uiTextItalicNormal,
    uiTextItalicOblique,
    uiTextItalicItalic,
};
extern  uiAttribute *uiNewItalicAttribute(uiTextItalic italic);
extern  uiTextItalic uiAttributeItalic(const uiAttribute *a);
typedef unsigned int uiTextStretch; enum {
    uiTextStretchUltraCondensed,
    uiTextStretchExtraCondensed,
    uiTextStretchCondensed,
    uiTextStretchSemiCondensed,
    uiTextStretchNormal,
    uiTextStretchSemiExpanded,
    uiTextStretchExpanded,
    uiTextStretchExtraExpanded,
    uiTextStretchUltraExpanded,
};
extern  uiAttribute *uiNewStretchAttribute(uiTextStretch stretch);
extern  uiTextStretch uiAttributeStretch(const uiAttribute *a);
extern  uiAttribute *uiNewColorAttribute(double r, double g, double b, double a);
extern  void uiAttributeColor(const uiAttribute *a, double *r, double *g, double *b, double *alpha);
extern  uiAttribute *uiNewBackgroundAttribute(double r, double g, double b, double a);
typedef unsigned int uiUnderline; enum {
    uiUnderlineNone,
    uiUnderlineSingle,
    uiUnderlineDouble,
    uiUnderlineSuggestion,};
extern  uiAttribute *uiNewUnderlineAttribute(uiUnderline u);
extern  uiUnderline uiAttributeUnderline(const uiAttribute *a);
typedef unsigned int uiUnderlineColor; enum {
    uiUnderlineColorCustom,
    uiUnderlineColorSpelling,
    uiUnderlineColorGrammar,
    uiUnderlineColorAuxiliary,};
extern  uiAttribute *uiNewUnderlineColorAttribute(uiUnderlineColor u, double r, double g, double b, double a);
extern  void uiAttributeUnderlineColor(const uiAttribute *a, uiUnderlineColor *u, double *r, double *g, double *b, double *alpha);
typedef struct  uiControl uiOpenTypeFeatures ;
typedef uiForEach (*uiOpenTypeFeaturesForEachFunc)(const uiOpenTypeFeatures *otf, char a, char b, char c, char d, uint32_t value, void *data);
extern  uiOpenTypeFeatures *uiNewOpenTypeFeatures(void);
extern  void uiFreeOpenTypeFeatures(uiOpenTypeFeatures *otf);
extern  uiOpenTypeFeatures *uiOpenTypeFeaturesClone(const uiOpenTypeFeatures *otf);
extern  void uiOpenTypeFeaturesAdd(uiOpenTypeFeatures *otf, char a, char b, char c, char d, uint32_t value);
extern  void uiOpenTypeFeaturesRemove(uiOpenTypeFeatures *otf, char a, char b, char c, char d);
extern  int uiOpenTypeFeaturesGet(const uiOpenTypeFeatures *otf, char a, char b, char c, char d, uint32_t *value);
extern  void uiOpenTypeFeaturesForEach(const uiOpenTypeFeatures *otf, uiOpenTypeFeaturesForEachFunc f, void *data);
extern  uiAttribute *uiNewFeaturesAttribute(const uiOpenTypeFeatures *otf);
extern  const uiOpenTypeFeatures *uiAttributeFeatures(const uiAttribute *a);
typedef struct  uiControl uiAttributedString ;
typedef uiForEach (*uiAttributedStringForEachAttributeFunc)(const uiAttributedString *s, const uiAttribute *a, size_t start, size_t end, void *data);
extern  uiAttributedString *uiNewAttributedString(const char *initialString);
extern  void uiFreeAttributedString(uiAttributedString *s);
extern  const char *uiAttributedStringString(const uiAttributedString *s);
extern  size_t uiAttributedStringLen(const uiAttributedString *s);
extern  void uiAttributedStringAppendUnattributed(uiAttributedString *s, const char *str);
extern  void uiAttributedStringInsertAtUnattributed(uiAttributedString *s, const char *str, size_t at);
extern  void uiAttributedStringDelete(uiAttributedString *s, size_t start, size_t end);
extern  void uiAttributedStringSetAttribute(uiAttributedString *s, uiAttribute *a, size_t start, size_t end);
extern  void uiAttributedStringForEachAttribute(const uiAttributedString *s, uiAttributedStringForEachAttributeFunc f, void *data);
extern  size_t uiAttributedStringNumGraphemes(uiAttributedString *s);
extern  size_t uiAttributedStringByteIndexToGrapheme(uiAttributedString *s, size_t pos);
extern  size_t uiAttributedStringGraphemeToByteIndex(uiAttributedString *s, size_t pos);
typedef struct uiFontDescriptor uiFontDescriptor;
struct uiFontDescriptor {    char *Family;
    double Size;
    uiTextWeight Weight;
    uiTextItalic Italic;
    uiTextStretch Stretch;
};
typedef struct  uiControl uiDrawTextLayout ;
typedef unsigned int uiDrawTextAlign; enum {
    uiDrawTextAlignLeft,
    uiDrawTextAlignCenter,
    uiDrawTextAlignRight,
};
typedef struct uiDrawTextLayoutParams uiDrawTextLayoutParams;
struct uiDrawTextLayoutParams {
    uiAttributedString *String;
    uiFontDescriptor *DefaultFont;
    double Width;
    uiDrawTextAlign Align;
};
extern  uiDrawTextLayout *uiDrawNewTextLayout(uiDrawTextLayoutParams *params);
extern  void uiDrawFreeTextLayout(uiDrawTextLayout *tl);
extern  void uiDrawText(uiDrawContext *c, uiDrawTextLayout *tl, double x, double y);
extern  void uiDrawTextLayoutExtents(uiDrawTextLayout *tl, double *width, double *height);
typedef struct  uiControl uiFontButton ;
extern  void uiFontButtonFont(uiFontButton *b, uiFontDescriptor *desc);
extern  void uiFontButtonOnChanged(uiFontButton *b, void (*f)(uiFontButton *, void *), void *data);
extern  uiFontButton *uiNewFontButton(void);
extern  void uiFreeFontButtonFont(uiFontDescriptor *desc);
typedef unsigned int uiModifiers; enum {
    uiModifierCtrl = 1 << 0,
    uiModifierAlt = 1 << 1,
    uiModifierShift = 1 << 2,
    uiModifierSuper = 1 << 3,
};
struct uiAreaMouseEvent {    double X;
    double Y;    double AreaWidth;
    double AreaHeight;
    int Down;
    int Up;
    int Count;
    uiModifiers Modifiers;
    uint64_t Held1To64;
};
typedef unsigned int uiExtKey; enum {
    uiExtKeyEscape = 1,
    uiExtKeyInsert,    uiExtKeyDelete,
    uiExtKeyHome,
    uiExtKeyEnd,
    uiExtKeyPageUp,
    uiExtKeyPageDown,
    uiExtKeyUp,
    uiExtKeyDown,
    uiExtKeyLeft,
    uiExtKeyRight,
    uiExtKeyF1,    uiExtKeyF2,
    uiExtKeyF3,
    uiExtKeyF4,
    uiExtKeyF5,
    uiExtKeyF6,
    uiExtKeyF7,
    uiExtKeyF8,
    uiExtKeyF9,
    uiExtKeyF10,
    uiExtKeyF11,
    uiExtKeyF12,
    uiExtKeyN0,    uiExtKeyN1,    uiExtKeyN2,
    uiExtKeyN3,
    uiExtKeyN4,
    uiExtKeyN5,
    uiExtKeyN6,
    uiExtKeyN7,
    uiExtKeyN8,
    uiExtKeyN9,
    uiExtKeyNDot,
    uiExtKeyNEnter,
    uiExtKeyNAdd,
    uiExtKeyNSubtract,
    uiExtKeyNMultiply,
    uiExtKeyNDivide,
};
struct uiAreaKeyEvent {
    char Key;
    uiExtKey ExtKey;
    uiModifiers Modifier;
    uiModifiers Modifiers;
    int Up;
};
typedef struct  uiControl uiColorButton ;
extern  void uiColorButtonColor(uiColorButton *b, double *r, double *g, double *bl, double *a);
extern  void uiColorButtonSetColor(uiColorButton *b, double r, double g, double bl, double a);
extern  void uiColorButtonOnChanged(uiColorButton *b, void (*f)(uiColorButton *, void *), void *data);
extern  uiColorButton *uiNewColorButton(void);
typedef struct  uiControl uiForm ;
extern  void uiFormAppend(uiForm *f, const char *label, uiControl *c, int stretchy);
extern  void uiFormDelete(uiForm *f, int index);
extern  int uiFormPadded(uiForm *f);
extern  void uiFormSetPadded(uiForm *f, int padded);
extern  uiForm *uiNewForm(void);
typedef unsigned int uiAlign; enum {
    uiAlignFill,
    uiAlignStart,
    uiAlignCenter,
    uiAlignEnd,
};
typedef unsigned int uiAt; enum {
    uiAtLeading,
    uiAtTop,
    uiAtTrailing,
    uiAtBottom,
};
typedef struct  uiControl uiGrid ;
extern  void uiGridAppend(uiGrid *g, uiControl *c, int left, int top, int xspan, int yspan, int hexpand, uiAlign halign, int vexpand, uiAlign valign);
extern  void uiGridInsertAt(uiGrid *g, uiControl *c, uiControl *existing, uiAt at, int xspan, int yspan, int hexpand, uiAlign halign, int vexpand, uiAlign valign);
extern  int uiGridPadded(uiGrid *g);
extern  void uiGridSetPadded(uiGrid *g, int padded);
extern  uiGrid *uiNewGrid(void);
typedef struct  uiControl uiImage ;
extern  uiImage *uiNewImage(double width, double height);
extern  void uiFreeImage(uiImage *i);
extern  void uiImageAppend(uiImage *i, void *pixels, int pixelWidth, int pixelHeight, int byteStride);
typedef struct  uiControl uiTableValue ;
extern  void uiFreeTableValue(uiTableValue *v);
typedef unsigned int uiTableValueType; enum {
    uiTableValueTypeString,
    uiTableValueTypeImage,
    uiTableValueTypeInt,
    uiTableValueTypeColor,
};
extern  uiTableValueType uiTableValueGetType(const uiTableValue *v);
extern  uiTableValue *uiNewTableValueString(const char *str);
extern  const char *uiTableValueString(const uiTableValue *v);
extern  uiTableValue *uiNewTableValueImage(uiImage *img);
extern  uiImage *uiTableValueImage(const uiTableValue *v);
extern  uiTableValue *uiNewTableValueInt(int i);
extern  int uiTableValueInt(const uiTableValue *v);
extern  uiTableValue *uiNewTableValueColor(double r, double g, double b, double a);
extern  void uiTableValueColor(const uiTableValue *v, double *r, double *g, double *b, double *a);
typedef struct  uiControl uiTableModel ;
typedef struct uiTableModelHandler uiTableModelHandler;

struct uiTableModelHandler {
    int (*NumColumns)(uiTableModelHandler *, uiTableModel *);    
    uiTableValueType (*ColumnType)(uiTableModelHandler *, uiTableModel *, int);
    int (*NumRows)(uiTableModelHandler *, uiTableModel *);
    uiTableValue (*CellValue)(uiTableModelHandler *mh, uiTableModel *m, int row, int column);
    void (*SetCellValue)(uiTableModelHandler *, uiTableModel *, int, int, const uiTableValue *);
};
extern  uiTableModel *uiNewTableModel(uiTableModelHandler *mh);
extern  void uiFreeTableModel(uiTableModel *m);
extern  void uiTableModelRowInserted(uiTableModel *m, int newIndex);
extern  void uiTableModelRowChanged(uiTableModel *m, int index);
extern  void uiTableModelRowDeleted(uiTableModel *m, int oldIndex);
typedef struct uiTableTextColumnOptionalParams uiTableTextColumnOptionalParams;
typedef struct uiTableParams uiTableParams;
struct uiTableTextColumnOptionalParams {int ColorModelColumn;};
struct uiTableParams {
    uiTableModel *Model;
    int RowBackgroundColorModelColumn;
};
typedef struct  uiControl uiTable ;
extern  void uiTableAppendTextColumn(uiTable *t,
    const char *name,int textModelColumn,
    int textEditableModelColumn,
    uiTableTextColumnOptionalParams *textParams);
extern  void uiTableAppendImageColumn(uiTable *t,
    const char *name,
    int imageModelColumn);
extern  void uiTableAppendImageTextColumn(uiTable *t,
    const char *name,
    int imageModelColumn,
    int textModelColumn,
    int textEditableModelColumn,
    uiTableTextColumnOptionalParams *textParams);
extern  void uiTableAppendCheckboxColumn(uiTable *t,
    const char *name,
    int checkboxModelColumn,
    int checkboxEditableModelColumn);
extern  void uiTableAppendCheckboxTextColumn(uiTable *t,
    const char *name,
    int checkboxModelColumn,
    int checkboxEditableModelColumn,
    int textModelColumn,
    int textEditableModelColumn,
    uiTableTextColumnOptionalParams *textParams);
extern  void uiTableAppendProgressBarColumn(uiTable *t,
    const char *name,
    int progressModelColumn);
extern  void uiTableAppendButtonColumn(uiTable *t,
    const char *name,
    int buttonModelColumn,
    int buttonClickableModelColumn);
extern  uiTable *uiNewTable(uiTableParams *params);

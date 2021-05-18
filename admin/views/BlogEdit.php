<?php

namespace PHPMaker2021\inplaze;

// Page object
$BlogEdit = &$Page;
?>
<script>
var currentForm, currentPageID;
var fblogedit;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "edit";
    fblogedit = currentForm = new ew.Form("fblogedit", "edit");

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "blog")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.blog)
        ew.vars.tables.blog = currentTable;
    fblogedit.addFields([
        ["Image", [fields.Image.visible && fields.Image.required ? ew.Validators.fileRequired(fields.Image.caption) : null], fields.Image.isInvalid],
        ["Title", [fields.Title.visible && fields.Title.required ? ew.Validators.required(fields.Title.caption) : null], fields.Title.isInvalid],
        ["Intro", [fields.Intro.visible && fields.Intro.required ? ew.Validators.required(fields.Intro.caption) : null], fields.Intro.isInvalid],
        ["_Content", [fields._Content.visible && fields._Content.required ? ew.Validators.required(fields._Content.caption) : null], fields._Content.isInvalid],
        ["_New", [fields._New.visible && fields._New.required ? ew.Validators.required(fields._New.caption) : null], fields._New.isInvalid],
        ["Images", [fields.Images.visible && fields.Images.required ? ew.Validators.fileRequired(fields.Images.caption) : null], fields.Images.isInvalid],
        ["Modified", [fields.Modified.visible && fields.Modified.required ? ew.Validators.required(fields.Modified.caption) : null], fields.Modified.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fblogedit,
            fobj = f.getForm(),
            $fobj = $(fobj),
            $k = $fobj.find("#" + f.formKeyCountName), // Get key_count
            rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1,
            startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
        for (var i = startcnt; i <= rowcnt; i++) {
            var rowIndex = ($k[0]) ? String(i) : "";
            f.setInvalid(rowIndex);
        }
    });

    // Validate form
    fblogedit.validate = function () {
        if (!this.validateRequired)
            return true; // Ignore validation
        var fobj = this.getForm(),
            $fobj = $(fobj);
        if ($fobj.find("#confirm").val() == "confirm")
            return true;
        var addcnt = 0,
            $k = $fobj.find("#" + this.formKeyCountName), // Get key_count
            rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1,
            startcnt = (rowcnt == 0) ? 0 : 1, // Check rowcnt == 0 => Inline-Add
            gridinsert = ["insert", "gridinsert"].includes($fobj.find("#action").val()) && $k[0];
        for (var i = startcnt; i <= rowcnt; i++) {
            var rowIndex = ($k[0]) ? String(i) : "";
            $fobj.data("rowindex", rowIndex);

            // Validate fields
            if (!this.validateFields(rowIndex))
                return false;

            // Call Form_CustomValidate event
            if (!this.customValidate(fobj)) {
                this.focus();
                return false;
            }
        }

        // Process detail forms
        var dfs = $fobj.find("input[name='detailpage']").get();
        for (var i = 0; i < dfs.length; i++) {
            var df = dfs[i],
                val = df.value,
                frm = ew.forms.get(val);
            if (val && frm && !frm.validate())
                return false;
        }
        return true;
    }

    // Form_CustomValidate
    fblogedit.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fblogedit.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fblogedit.lists._New = <?= $Page->_New->toClientList($Page) ?>;
    loadjs.done("fblogedit");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<form name="fblogedit" id="fblogedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="blog">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->Image->Visible) { // Image ?>
    <div id="r_Image" class="form-group row">
        <label id="elh_blog_Image" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Image->caption() ?><?= $Page->Image->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->Image->cellAttributes() ?>>
<span id="el_blog_Image">
<div id="fd_x_Image">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Page->Image->title() ?>" data-table="blog" data-field="x_Image" name="x_Image" id="x_Image" lang="<?= CurrentLanguageID() ?>"<?= $Page->Image->editAttributes() ?><?= ($Page->Image->ReadOnly || $Page->Image->Disabled) ? " disabled" : "" ?> aria-describedby="x_Image_help">
        <label class="custom-file-label ew-file-label" for="x_Image"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<?= $Page->Image->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Image->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_Image" id= "fn_x_Image" value="<?= $Page->Image->Upload->FileName ?>">
<input type="hidden" name="fa_x_Image" id= "fa_x_Image" value="<?= (Post("fa_x_Image") == "0") ? "0" : "1" ?>">
<input type="hidden" name="fs_x_Image" id= "fs_x_Image" value="100">
<input type="hidden" name="fx_x_Image" id= "fx_x_Image" value="<?= $Page->Image->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_Image" id= "fm_x_Image" value="<?= $Page->Image->UploadMaxFileSize ?>">
</div>
<table id="ft_x_Image" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Title->Visible) { // Title ?>
    <div id="r_Title" class="form-group row">
        <label id="elh_blog_Title" for="x_Title" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Title->caption() ?><?= $Page->Title->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->Title->cellAttributes() ?>>
<span id="el_blog_Title">
<input type="<?= $Page->Title->getInputTextType() ?>" data-table="blog" data-field="x_Title" name="x_Title" id="x_Title" size="60" maxlength="200" placeholder="<?= HtmlEncode($Page->Title->getPlaceHolder()) ?>" value="<?= $Page->Title->EditValue ?>"<?= $Page->Title->editAttributes() ?> aria-describedby="x_Title_help">
<?= $Page->Title->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Title->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Intro->Visible) { // Intro ?>
    <div id="r_Intro" class="form-group row">
        <label id="elh_blog_Intro" for="x_Intro" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Intro->caption() ?><?= $Page->Intro->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->Intro->cellAttributes() ?>>
<span id="el_blog_Intro">
<textarea data-table="blog" data-field="x_Intro" name="x_Intro" id="x_Intro" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->Intro->getPlaceHolder()) ?>"<?= $Page->Intro->editAttributes() ?> aria-describedby="x_Intro_help"><?= $Page->Intro->EditValue ?></textarea>
<?= $Page->Intro->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Intro->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->_Content->Visible) { // Content ?>
    <div id="r__Content" class="form-group row">
        <label id="elh_blog__Content" class="<?= $Page->LeftColumnClass ?>"><?= $Page->_Content->caption() ?><?= $Page->_Content->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->_Content->cellAttributes() ?>>
<span id="el_blog__Content">
<?php $Page->_Content->EditAttrs->appendClass("editor"); ?>
<textarea data-table="blog" data-field="x__Content" name="x__Content" id="x__Content" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->_Content->getPlaceHolder()) ?>"<?= $Page->_Content->editAttributes() ?> aria-describedby="x__Content_help"><?= $Page->_Content->EditValue ?></textarea>
<?= $Page->_Content->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->_Content->getErrorMessage() ?></div>
<script>
loadjs.ready(["fblogedit", "editor"], function() {
	ew.createEditor("fblogedit", "x__Content", 35, 4, <?= $Page->_Content->ReadOnly || false ? "true" : "false" ?>);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->_New->Visible) { // New ?>
    <div id="r__New" class="form-group row">
        <label id="elh_blog__New" class="<?= $Page->LeftColumnClass ?>"><?= $Page->_New->caption() ?><?= $Page->_New->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->_New->cellAttributes() ?>>
<span id="el_blog__New">
<div class="custom-control custom-checkbox d-inline-block">
    <input type="checkbox" class="custom-control-input<?= $Page->_New->isInvalidClass() ?>" data-table="blog" data-field="x__New" name="x__New[]" id="x__New_446224" value="1"<?= ConvertToBool($Page->_New->CurrentValue) ? " checked" : "" ?><?= $Page->_New->editAttributes() ?> aria-describedby="x__New_help">
    <label class="custom-control-label" for="x__New_446224"></label>
</div>
<?= $Page->_New->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->_New->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Images->Visible) { // Images ?>
    <div id="r_Images" class="form-group row">
        <label id="elh_blog_Images" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Images->caption() ?><?= $Page->Images->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->Images->cellAttributes() ?>>
<span id="el_blog_Images">
<div id="fd_x_Images">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Page->Images->title() ?>" data-table="blog" data-field="x_Images" name="x_Images" id="x_Images" lang="<?= CurrentLanguageID() ?>" multiple<?= $Page->Images->editAttributes() ?><?= ($Page->Images->ReadOnly || $Page->Images->Disabled) ? " disabled" : "" ?> aria-describedby="x_Images_help">
        <label class="custom-file-label ew-file-label" for="x_Images"><?= $Language->phrase("ChooseFiles") ?></label>
    </div>
</div>
<?= $Page->Images->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Images->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_Images" id= "fn_x_Images" value="<?= $Page->Images->Upload->FileName ?>">
<input type="hidden" name="fa_x_Images" id= "fa_x_Images" value="<?= (Post("fa_x_Images") == "0") ? "0" : "1" ?>">
<input type="hidden" name="fs_x_Images" id= "fs_x_Images" value="65535">
<input type="hidden" name="fx_x_Images" id= "fx_x_Images" value="<?= $Page->Images->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_Images" id= "fm_x_Images" value="<?= $Page->Images->UploadMaxFileSize ?>">
<input type="hidden" name="fc_x_Images" id= "fc_x_Images" value="<?= $Page->Images->UploadMaxFileCount ?>">
</div>
<table id="ft_x_Images" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
    <input type="hidden" data-table="blog" data-field="x_Blog_ID" data-hidden="1" name="x_Blog_ID" id="x_Blog_ID" value="<?= HtmlEncode($Page->Blog_ID->CurrentValue) ?>">
<?php if (!$Page->IsModal) { ?>
<div class="form-group row"><!-- buttons .form-group -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit"><?= $Language->phrase("SaveBtn") ?></button>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
    </div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<script>
// Field event handlers
loadjs.ready("head", function() {
    ew.addEventHandlers("blog");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>

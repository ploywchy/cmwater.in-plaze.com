<?php

namespace PHPMaker2021\inplaze;

// Page object
$ProductAdd = &$Page;
?>
<script>
var currentForm, currentPageID;
var fproductadd;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "add";
    fproductadd = currentForm = new ew.Form("fproductadd", "add");

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "product")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.product)
        ew.vars.tables.product = currentTable;
    fproductadd.addFields([
        ["Category_ID", [fields.Category_ID.visible && fields.Category_ID.required ? ew.Validators.required(fields.Category_ID.caption) : null], fields.Category_ID.isInvalid],
        ["Name", [fields.Name.visible && fields.Name.required ? ew.Validators.required(fields.Name.caption) : null], fields.Name.isInvalid],
        ["Intro", [fields.Intro.visible && fields.Intro.required ? ew.Validators.required(fields.Intro.caption) : null], fields.Intro.isInvalid],
        ["Description", [fields.Description.visible && fields.Description.required ? ew.Validators.required(fields.Description.caption) : null], fields.Description.isInvalid],
        ["Price", [fields.Price.visible && fields.Price.required ? ew.Validators.required(fields.Price.caption) : null, ew.Validators.integer], fields.Price.isInvalid],
        ["Image", [fields.Image.visible && fields.Image.required ? ew.Validators.fileRequired(fields.Image.caption) : null], fields.Image.isInvalid],
        ["Images", [fields.Images.visible && fields.Images.required ? ew.Validators.fileRequired(fields.Images.caption) : null], fields.Images.isInvalid],
        ["Created", [fields.Created.visible && fields.Created.required ? ew.Validators.required(fields.Created.caption) : null], fields.Created.isInvalid],
        ["Modified", [fields.Modified.visible && fields.Modified.required ? ew.Validators.required(fields.Modified.caption) : null], fields.Modified.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fproductadd,
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
    fproductadd.validate = function () {
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
    fproductadd.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fproductadd.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fproductadd.lists.Category_ID = <?= $Page->Category_ID->toClientList($Page) ?>;
    loadjs.done("fproductadd");
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
<form name="fproductadd" id="fproductadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="product">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<?php if ($Page->getCurrentMasterTable() == "category") { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="category">
<input type="hidden" name="fk_Category_ID" value="<?= HtmlEncode($Page->Category_ID->getSessionValue()) ?>">
<?php } ?>
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->Category_ID->Visible) { // Category_ID ?>
    <div id="r_Category_ID" class="form-group row">
        <label id="elh_product_Category_ID" for="x_Category_ID" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Category_ID->caption() ?><?= $Page->Category_ID->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->Category_ID->cellAttributes() ?>>
<?php if ($Page->Category_ID->getSessionValue() != "") { ?>
<span id="el_product_Category_ID">
<span<?= $Page->Category_ID->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->Category_ID->getDisplayValue($Page->Category_ID->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x_Category_ID" name="x_Category_ID" value="<?= HtmlEncode($Page->Category_ID->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el_product_Category_ID">
<div class="input-group flex-nowrap">
    <select
        id="x_Category_ID"
        name="x_Category_ID"
        class="form-control ew-select<?= $Page->Category_ID->isInvalidClass() ?>"
        data-select2-id="product_x_Category_ID"
        data-table="product"
        data-field="x_Category_ID"
        data-value-separator="<?= $Page->Category_ID->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->Category_ID->getPlaceHolder()) ?>"
        <?= $Page->Category_ID->editAttributes() ?>>
        <?= $Page->Category_ID->selectOptionListHtml("x_Category_ID") ?>
    </select>
    <?php if (AllowAdd(CurrentProjectID() . "category") && !$Page->Category_ID->ReadOnly) { ?>
    <div class="input-group-append"><button type="button" class="btn btn-default ew-add-opt-btn" id="aol_x_Category_ID" title="<?= HtmlTitle($Language->phrase("AddLink")) . "&nbsp;" . $Page->Category_ID->caption() ?>" data-title="<?= $Page->Category_ID->caption() ?>" onclick="ew.addOptionDialogShow({lnk:this,el:'x_Category_ID',url:'<?= GetUrl("CategoryAddopt") ?>'});"><i class="fas fa-plus ew-icon"></i></button></div>
    <?php } ?>
</div>
<?= $Page->Category_ID->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Category_ID->getErrorMessage() ?></div>
<?= $Page->Category_ID->Lookup->getParamTag($Page, "p_x_Category_ID") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='product_x_Category_ID']"),
        options = { name: "x_Category_ID", selectId: "product_x_Category_ID", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.product.fields.Category_ID.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Name->Visible) { // Name ?>
    <div id="r_Name" class="form-group row">
        <label id="elh_product_Name" for="x_Name" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Name->caption() ?><?= $Page->Name->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->Name->cellAttributes() ?>>
<span id="el_product_Name">
<input type="<?= $Page->Name->getInputTextType() ?>" data-table="product" data-field="x_Name" name="x_Name" id="x_Name" size="30" maxlength="200" placeholder="<?= HtmlEncode($Page->Name->getPlaceHolder()) ?>" value="<?= $Page->Name->EditValue ?>"<?= $Page->Name->editAttributes() ?> aria-describedby="x_Name_help">
<?= $Page->Name->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Name->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Intro->Visible) { // Intro ?>
    <div id="r_Intro" class="form-group row">
        <label id="elh_product_Intro" for="x_Intro" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Intro->caption() ?><?= $Page->Intro->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->Intro->cellAttributes() ?>>
<span id="el_product_Intro">
<textarea data-table="product" data-field="x_Intro" name="x_Intro" id="x_Intro" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->Intro->getPlaceHolder()) ?>"<?= $Page->Intro->editAttributes() ?> aria-describedby="x_Intro_help"><?= $Page->Intro->EditValue ?></textarea>
<?= $Page->Intro->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Intro->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Description->Visible) { // Description ?>
    <div id="r_Description" class="form-group row">
        <label id="elh_product_Description" for="x_Description" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Description->caption() ?><?= $Page->Description->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->Description->cellAttributes() ?>>
<span id="el_product_Description">
<textarea data-table="product" data-field="x_Description" name="x_Description" id="x_Description" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->Description->getPlaceHolder()) ?>"<?= $Page->Description->editAttributes() ?> aria-describedby="x_Description_help"><?= $Page->Description->EditValue ?></textarea>
<?= $Page->Description->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Description->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Price->Visible) { // Price ?>
    <div id="r_Price" class="form-group row">
        <label id="elh_product_Price" for="x_Price" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Price->caption() ?><?= $Page->Price->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->Price->cellAttributes() ?>>
<span id="el_product_Price">
<input type="<?= $Page->Price->getInputTextType() ?>" data-table="product" data-field="x_Price" name="x_Price" id="x_Price" size="10" placeholder="<?= HtmlEncode($Page->Price->getPlaceHolder()) ?>" value="<?= $Page->Price->EditValue ?>"<?= $Page->Price->editAttributes() ?> aria-describedby="x_Price_help">
<?= $Page->Price->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Price->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Image->Visible) { // Image ?>
    <div id="r_Image" class="form-group row">
        <label id="elh_product_Image" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Image->caption() ?><?= $Page->Image->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->Image->cellAttributes() ?>>
<span id="el_product_Image">
<div id="fd_x_Image">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Page->Image->title() ?>" data-table="product" data-field="x_Image" name="x_Image" id="x_Image" lang="<?= CurrentLanguageID() ?>"<?= $Page->Image->editAttributes() ?><?= ($Page->Image->ReadOnly || $Page->Image->Disabled) ? " disabled" : "" ?> aria-describedby="x_Image_help">
        <label class="custom-file-label ew-file-label" for="x_Image"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<?= $Page->Image->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Image->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_Image" id= "fn_x_Image" value="<?= $Page->Image->Upload->FileName ?>">
<input type="hidden" name="fa_x_Image" id= "fa_x_Image" value="0">
<input type="hidden" name="fs_x_Image" id= "fs_x_Image" value="100">
<input type="hidden" name="fx_x_Image" id= "fx_x_Image" value="<?= $Page->Image->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_Image" id= "fm_x_Image" value="<?= $Page->Image->UploadMaxFileSize ?>">
</div>
<table id="ft_x_Image" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Images->Visible) { // Images ?>
    <div id="r_Images" class="form-group row">
        <label id="elh_product_Images" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Images->caption() ?><?= $Page->Images->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->Images->cellAttributes() ?>>
<span id="el_product_Images">
<div id="fd_x_Images">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Page->Images->title() ?>" data-table="product" data-field="x_Images" name="x_Images" id="x_Images" lang="<?= CurrentLanguageID() ?>" multiple<?= $Page->Images->editAttributes() ?><?= ($Page->Images->ReadOnly || $Page->Images->Disabled) ? " disabled" : "" ?> aria-describedby="x_Images_help">
        <label class="custom-file-label ew-file-label" for="x_Images"><?= $Language->phrase("ChooseFiles") ?></label>
    </div>
</div>
<?= $Page->Images->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Images->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_Images" id= "fn_x_Images" value="<?= $Page->Images->Upload->FileName ?>">
<input type="hidden" name="fa_x_Images" id= "fa_x_Images" value="0">
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
<?php if (!$Page->IsModal) { ?>
<div class="form-group row"><!-- buttons .form-group -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit"><?= $Language->phrase("AddBtn") ?></button>
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
    ew.addEventHandlers("product");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>

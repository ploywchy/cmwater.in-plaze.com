<?php

namespace PHPMaker2021\inplaze;

// Set up and run Grid object
$Grid = Container("ProductGrid");
$Grid->run();
?>
<?php if (!$Grid->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fproductgrid;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    fproductgrid = new ew.Form("fproductgrid", "grid");
    fproductgrid.formKeyCountName = '<?= $Grid->FormKeyCountName ?>';

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "product")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.product)
        ew.vars.tables.product = currentTable;
    fproductgrid.addFields([
        ["Category_ID", [fields.Category_ID.visible && fields.Category_ID.required ? ew.Validators.required(fields.Category_ID.caption) : null], fields.Category_ID.isInvalid],
        ["Name", [fields.Name.visible && fields.Name.required ? ew.Validators.required(fields.Name.caption) : null], fields.Name.isInvalid],
        ["Price", [fields.Price.visible && fields.Price.required ? ew.Validators.required(fields.Price.caption) : null], fields.Price.isInvalid],
        ["_New", [fields._New.visible && fields._New.required ? ew.Validators.required(fields._New.caption) : null], fields._New.isInvalid],
        ["Image", [fields.Image.visible && fields.Image.required ? ew.Validators.fileRequired(fields.Image.caption) : null], fields.Image.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fproductgrid,
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
    fproductgrid.validate = function () {
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
            var checkrow = (gridinsert) ? !this.emptyRow(rowIndex) : true;
            if (checkrow) {
                addcnt++;

            // Validate fields
            if (!this.validateFields(rowIndex))
                return false;

            // Call Form_CustomValidate event
            if (!this.customValidate(fobj)) {
                this.focus();
                return false;
            }
            } // End Grid Add checking
        }
        return true;
    }

    // Check empty row
    fproductgrid.emptyRow = function (rowIndex) {
        var fobj = this.getForm();
        if (ew.valueChanged(fobj, rowIndex, "Category_ID", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "Name", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "Price", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "_New[]", true))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "Image", false))
            return false;
        return true;
    }

    // Form_CustomValidate
    fproductgrid.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fproductgrid.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fproductgrid.lists.Category_ID = <?= $Grid->Category_ID->toClientList($Grid) ?>;
    fproductgrid.lists._New = <?= $Grid->_New->toClientList($Grid) ?>;
    loadjs.done("fproductgrid");
});
</script>
<?php } ?>
<?php
$Grid->renderOtherOptions();
?>
<?php if ($Grid->TotalRecords > 0 || $Grid->CurrentAction) { ?>
<div class="card ew-card ew-grid<?php if ($Grid->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> product">
<?php if ($Grid->ShowOtherOptions) { ?>
<div class="card-header ew-grid-upper-panel">
<?php $Grid->OtherOptions->render("body") ?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="fproductgrid" class="ew-form ew-list-form form-inline">
<div id="gmp_product" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<table id="tbl_productgrid" class="table ew-table"><!-- .ew-table -->
<thead>
    <tr class="ew-table-header">
<?php
// Header row
$Grid->RowType = ROWTYPE_HEADER;

// Render list options
$Grid->renderListOptions();

// Render list options (header, left)
$Grid->ListOptions->render("header", "left");
?>
<?php if ($Grid->Category_ID->Visible) { // Category_ID ?>
        <th data-name="Category_ID" class="<?= $Grid->Category_ID->headerCellClass() ?>" style="white-space: nowrap;"><div id="elh_product_Category_ID" class="product_Category_ID"><?= $Grid->renderSort($Grid->Category_ID) ?></div></th>
<?php } ?>
<?php if ($Grid->Name->Visible) { // Name ?>
        <th data-name="Name" class="<?= $Grid->Name->headerCellClass() ?>" style="white-space: nowrap;"><div id="elh_product_Name" class="product_Name"><?= $Grid->renderSort($Grid->Name) ?></div></th>
<?php } ?>
<?php if ($Grid->Price->Visible) { // Price ?>
        <th data-name="Price" class="<?= $Grid->Price->headerCellClass() ?>" style="white-space: nowrap;"><div id="elh_product_Price" class="product_Price"><?= $Grid->renderSort($Grid->Price) ?></div></th>
<?php } ?>
<?php if ($Grid->_New->Visible) { // New ?>
        <th data-name="_New" class="<?= $Grid->_New->headerCellClass() ?>" style="white-space: nowrap;"><div id="elh_product__New" class="product__New"><?= $Grid->renderSort($Grid->_New) ?></div></th>
<?php } ?>
<?php if ($Grid->Image->Visible) { // Image ?>
        <th data-name="Image" class="<?= $Grid->Image->headerCellClass() ?>" style="white-space: nowrap;"><div id="elh_product_Image" class="product_Image"><?= $Grid->renderSort($Grid->Image) ?></div></th>
<?php } ?>
<?php
// Render list options (header, right)
$Grid->ListOptions->render("header", "right");
?>
    </tr>
</thead>
<tbody>
<?php
$Grid->StartRecord = 1;
$Grid->StopRecord = $Grid->TotalRecords; // Show all records

// Restore number of post back records
if ($CurrentForm && ($Grid->isConfirm() || $Grid->EventCancelled)) {
    $CurrentForm->Index = -1;
    if ($CurrentForm->hasValue($Grid->FormKeyCountName) && ($Grid->isGridAdd() || $Grid->isGridEdit() || $Grid->isConfirm())) {
        $Grid->KeyCount = $CurrentForm->getValue($Grid->FormKeyCountName);
        $Grid->StopRecord = $Grid->StartRecord + $Grid->KeyCount - 1;
    }
}
$Grid->RecordCount = $Grid->StartRecord - 1;
if ($Grid->Recordset && !$Grid->Recordset->EOF) {
    // Nothing to do
} elseif (!$Grid->AllowAddDeleteRow && $Grid->StopRecord == 0) {
    $Grid->StopRecord = $Grid->GridAddRowCount;
}

// Initialize aggregate
$Grid->RowType = ROWTYPE_AGGREGATEINIT;
$Grid->resetAttributes();
$Grid->renderRow();
if ($Grid->isGridAdd())
    $Grid->RowIndex = 0;
if ($Grid->isGridEdit())
    $Grid->RowIndex = 0;
while ($Grid->RecordCount < $Grid->StopRecord) {
    $Grid->RecordCount++;
    if ($Grid->RecordCount >= $Grid->StartRecord) {
        $Grid->RowCount++;
        if ($Grid->isGridAdd() || $Grid->isGridEdit() || $Grid->isConfirm()) {
            $Grid->RowIndex++;
            $CurrentForm->Index = $Grid->RowIndex;
            if ($CurrentForm->hasValue($Grid->FormActionName) && ($Grid->isConfirm() || $Grid->EventCancelled)) {
                $Grid->RowAction = strval($CurrentForm->getValue($Grid->FormActionName));
            } elseif ($Grid->isGridAdd()) {
                $Grid->RowAction = "insert";
            } else {
                $Grid->RowAction = "";
            }
        }

        // Set up key count
        $Grid->KeyCount = $Grid->RowIndex;

        // Init row class and style
        $Grid->resetAttributes();
        $Grid->CssClass = "";
        if ($Grid->isGridAdd()) {
            if ($Grid->CurrentMode == "copy") {
                $Grid->loadRowValues($Grid->Recordset); // Load row values
                $Grid->OldKey = $Grid->getKey(true); // Get from CurrentValue
            } else {
                $Grid->loadRowValues(); // Load default values
                $Grid->OldKey = "";
            }
        } else {
            $Grid->loadRowValues($Grid->Recordset); // Load row values
            $Grid->OldKey = $Grid->getKey(true); // Get from CurrentValue
        }
        $Grid->setKey($Grid->OldKey);
        $Grid->RowType = ROWTYPE_VIEW; // Render view
        if ($Grid->isGridAdd()) { // Grid add
            $Grid->RowType = ROWTYPE_ADD; // Render add
        }
        if ($Grid->isGridAdd() && $Grid->EventCancelled && !$CurrentForm->hasValue("k_blankrow")) { // Insert failed
            $Grid->restoreCurrentRowFormValues($Grid->RowIndex); // Restore form values
        }
        if ($Grid->isGridEdit()) { // Grid edit
            if ($Grid->EventCancelled) {
                $Grid->restoreCurrentRowFormValues($Grid->RowIndex); // Restore form values
            }
            if ($Grid->RowAction == "insert") {
                $Grid->RowType = ROWTYPE_ADD; // Render add
            } else {
                $Grid->RowType = ROWTYPE_EDIT; // Render edit
            }
        }
        if ($Grid->isGridEdit() && ($Grid->RowType == ROWTYPE_EDIT || $Grid->RowType == ROWTYPE_ADD) && $Grid->EventCancelled) { // Update failed
            $Grid->restoreCurrentRowFormValues($Grid->RowIndex); // Restore form values
        }
        if ($Grid->RowType == ROWTYPE_EDIT) { // Edit row
            $Grid->EditRowCount++;
        }
        if ($Grid->isConfirm()) { // Confirm row
            $Grid->restoreCurrentRowFormValues($Grid->RowIndex); // Restore form values
        }

        // Set up row id / data-rowindex
        $Grid->RowAttrs->merge(["data-rowindex" => $Grid->RowCount, "id" => "r" . $Grid->RowCount . "_product", "data-rowtype" => $Grid->RowType]);

        // Render row
        $Grid->renderRow();

        // Render list options
        $Grid->renderListOptions();

        // Skip delete row / empty row for confirm page
        if ($Grid->RowAction != "delete" && $Grid->RowAction != "insertdelete" && !($Grid->RowAction == "insert" && $Grid->isConfirm() && $Grid->emptyRow())) {
?>
    <tr <?= $Grid->rowAttributes() ?>>
<?php
// Render list options (body, left)
$Grid->ListOptions->render("body", "left", $Grid->RowCount);
?>
    <?php if ($Grid->Category_ID->Visible) { // Category_ID ?>
        <td data-name="Category_ID" <?= $Grid->Category_ID->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<?php if ($Grid->Category_ID->getSessionValue() != "") { ?>
<span id="el<?= $Grid->RowCount ?>_product_Category_ID" class="form-group">
<span<?= $Grid->Category_ID->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->Category_ID->getDisplayValue($Grid->Category_ID->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_Category_ID" name="x<?= $Grid->RowIndex ?>_Category_ID" value="<?= HtmlEncode($Grid->Category_ID->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el<?= $Grid->RowCount ?>_product_Category_ID" class="form-group">
<div class="input-group flex-nowrap">
    <select
        id="x<?= $Grid->RowIndex ?>_Category_ID"
        name="x<?= $Grid->RowIndex ?>_Category_ID"
        class="form-control ew-select<?= $Grid->Category_ID->isInvalidClass() ?>"
        data-select2-id="product_x<?= $Grid->RowIndex ?>_Category_ID"
        data-table="product"
        data-field="x_Category_ID"
        data-value-separator="<?= $Grid->Category_ID->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->Category_ID->getPlaceHolder()) ?>"
        <?= $Grid->Category_ID->editAttributes() ?>>
        <?= $Grid->Category_ID->selectOptionListHtml("x{$Grid->RowIndex}_Category_ID") ?>
    </select>
    <?php if (AllowAdd(CurrentProjectID() . "category") && !$Grid->Category_ID->ReadOnly) { ?>
    <div class="input-group-append"><button type="button" class="btn btn-default ew-add-opt-btn" id="aol_x<?= $Grid->RowIndex ?>_Category_ID" title="<?= HtmlTitle($Language->phrase("AddLink")) . "&nbsp;" . $Grid->Category_ID->caption() ?>" data-title="<?= $Grid->Category_ID->caption() ?>" onclick="ew.addOptionDialogShow({lnk:this,el:'x<?= $Grid->RowIndex ?>_Category_ID',url:'<?= GetUrl("CategoryAddopt") ?>'});"><i class="fas fa-plus ew-icon"></i></button></div>
    <?php } ?>
</div>
<div class="invalid-feedback"><?= $Grid->Category_ID->getErrorMessage() ?></div>
<?= $Grid->Category_ID->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_Category_ID") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='product_x<?= $Grid->RowIndex ?>_Category_ID']"),
        options = { name: "x<?= $Grid->RowIndex ?>_Category_ID", selectId: "product_x<?= $Grid->RowIndex ?>_Category_ID", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.product.fields.Category_ID.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<input type="hidden" data-table="product" data-field="x_Category_ID" data-hidden="1" name="o<?= $Grid->RowIndex ?>_Category_ID" id="o<?= $Grid->RowIndex ?>_Category_ID" value="<?= HtmlEncode($Grid->Category_ID->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<?php if ($Grid->Category_ID->getSessionValue() != "") { ?>
<span id="el<?= $Grid->RowCount ?>_product_Category_ID" class="form-group">
<span<?= $Grid->Category_ID->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->Category_ID->getDisplayValue($Grid->Category_ID->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_Category_ID" name="x<?= $Grid->RowIndex ?>_Category_ID" value="<?= HtmlEncode($Grid->Category_ID->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el<?= $Grid->RowCount ?>_product_Category_ID" class="form-group">
<div class="input-group flex-nowrap">
    <select
        id="x<?= $Grid->RowIndex ?>_Category_ID"
        name="x<?= $Grid->RowIndex ?>_Category_ID"
        class="form-control ew-select<?= $Grid->Category_ID->isInvalidClass() ?>"
        data-select2-id="product_x<?= $Grid->RowIndex ?>_Category_ID"
        data-table="product"
        data-field="x_Category_ID"
        data-value-separator="<?= $Grid->Category_ID->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->Category_ID->getPlaceHolder()) ?>"
        <?= $Grid->Category_ID->editAttributes() ?>>
        <?= $Grid->Category_ID->selectOptionListHtml("x{$Grid->RowIndex}_Category_ID") ?>
    </select>
    <?php if (AllowAdd(CurrentProjectID() . "category") && !$Grid->Category_ID->ReadOnly) { ?>
    <div class="input-group-append"><button type="button" class="btn btn-default ew-add-opt-btn" id="aol_x<?= $Grid->RowIndex ?>_Category_ID" title="<?= HtmlTitle($Language->phrase("AddLink")) . "&nbsp;" . $Grid->Category_ID->caption() ?>" data-title="<?= $Grid->Category_ID->caption() ?>" onclick="ew.addOptionDialogShow({lnk:this,el:'x<?= $Grid->RowIndex ?>_Category_ID',url:'<?= GetUrl("CategoryAddopt") ?>'});"><i class="fas fa-plus ew-icon"></i></button></div>
    <?php } ?>
</div>
<div class="invalid-feedback"><?= $Grid->Category_ID->getErrorMessage() ?></div>
<?= $Grid->Category_ID->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_Category_ID") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='product_x<?= $Grid->RowIndex ?>_Category_ID']"),
        options = { name: "x<?= $Grid->RowIndex ?>_Category_ID", selectId: "product_x<?= $Grid->RowIndex ?>_Category_ID", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.product.fields.Category_ID.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_product_Category_ID">
<span<?= $Grid->Category_ID->viewAttributes() ?>>
<?= $Grid->Category_ID->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="product" data-field="x_Category_ID" data-hidden="1" name="fproductgrid$x<?= $Grid->RowIndex ?>_Category_ID" id="fproductgrid$x<?= $Grid->RowIndex ?>_Category_ID" value="<?= HtmlEncode($Grid->Category_ID->FormValue) ?>">
<input type="hidden" data-table="product" data-field="x_Category_ID" data-hidden="1" name="fproductgrid$o<?= $Grid->RowIndex ?>_Category_ID" id="fproductgrid$o<?= $Grid->RowIndex ?>_Category_ID" value="<?= HtmlEncode($Grid->Category_ID->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->Name->Visible) { // Name ?>
        <td data-name="Name" <?= $Grid->Name->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_product_Name" class="form-group">
<input type="<?= $Grid->Name->getInputTextType() ?>" data-table="product" data-field="x_Name" name="x<?= $Grid->RowIndex ?>_Name" id="x<?= $Grid->RowIndex ?>_Name" size="30" maxlength="200" placeholder="<?= HtmlEncode($Grid->Name->getPlaceHolder()) ?>" value="<?= $Grid->Name->EditValue ?>"<?= $Grid->Name->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->Name->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="product" data-field="x_Name" data-hidden="1" name="o<?= $Grid->RowIndex ?>_Name" id="o<?= $Grid->RowIndex ?>_Name" value="<?= HtmlEncode($Grid->Name->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_product_Name" class="form-group">
<input type="<?= $Grid->Name->getInputTextType() ?>" data-table="product" data-field="x_Name" name="x<?= $Grid->RowIndex ?>_Name" id="x<?= $Grid->RowIndex ?>_Name" size="30" maxlength="200" placeholder="<?= HtmlEncode($Grid->Name->getPlaceHolder()) ?>" value="<?= $Grid->Name->EditValue ?>"<?= $Grid->Name->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->Name->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_product_Name">
<span<?= $Grid->Name->viewAttributes() ?>>
<?= $Grid->Name->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="product" data-field="x_Name" data-hidden="1" name="fproductgrid$x<?= $Grid->RowIndex ?>_Name" id="fproductgrid$x<?= $Grid->RowIndex ?>_Name" value="<?= HtmlEncode($Grid->Name->FormValue) ?>">
<input type="hidden" data-table="product" data-field="x_Name" data-hidden="1" name="fproductgrid$o<?= $Grid->RowIndex ?>_Name" id="fproductgrid$o<?= $Grid->RowIndex ?>_Name" value="<?= HtmlEncode($Grid->Name->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->Price->Visible) { // Price ?>
        <td data-name="Price" <?= $Grid->Price->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_product_Price" class="form-group">
<input type="<?= $Grid->Price->getInputTextType() ?>" data-table="product" data-field="x_Price" name="x<?= $Grid->RowIndex ?>_Price" id="x<?= $Grid->RowIndex ?>_Price" size="10" placeholder="<?= HtmlEncode($Grid->Price->getPlaceHolder()) ?>" value="<?= $Grid->Price->EditValue ?>"<?= $Grid->Price->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->Price->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="product" data-field="x_Price" data-hidden="1" name="o<?= $Grid->RowIndex ?>_Price" id="o<?= $Grid->RowIndex ?>_Price" value="<?= HtmlEncode($Grid->Price->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_product_Price" class="form-group">
<input type="<?= $Grid->Price->getInputTextType() ?>" data-table="product" data-field="x_Price" name="x<?= $Grid->RowIndex ?>_Price" id="x<?= $Grid->RowIndex ?>_Price" size="10" placeholder="<?= HtmlEncode($Grid->Price->getPlaceHolder()) ?>" value="<?= $Grid->Price->EditValue ?>"<?= $Grid->Price->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->Price->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_product_Price">
<span<?= $Grid->Price->viewAttributes() ?>>
<?= $Grid->Price->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="product" data-field="x_Price" data-hidden="1" name="fproductgrid$x<?= $Grid->RowIndex ?>_Price" id="fproductgrid$x<?= $Grid->RowIndex ?>_Price" value="<?= HtmlEncode($Grid->Price->FormValue) ?>">
<input type="hidden" data-table="product" data-field="x_Price" data-hidden="1" name="fproductgrid$o<?= $Grid->RowIndex ?>_Price" id="fproductgrid$o<?= $Grid->RowIndex ?>_Price" value="<?= HtmlEncode($Grid->Price->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->_New->Visible) { // New ?>
        <td data-name="_New" <?= $Grid->_New->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_product__New" class="form-group">
<div class="custom-control custom-checkbox d-inline-block">
    <input type="checkbox" class="custom-control-input<?= $Grid->_New->isInvalidClass() ?>" data-table="product" data-field="x__New" name="x<?= $Grid->RowIndex ?>__New[]" id="x<?= $Grid->RowIndex ?>__New_722747" value="1"<?= ConvertToBool($Grid->_New->CurrentValue) ? " checked" : "" ?><?= $Grid->_New->editAttributes() ?>>
    <label class="custom-control-label" for="x<?= $Grid->RowIndex ?>__New_722747"></label>
</div>
<div class="invalid-feedback"><?= $Grid->_New->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="product" data-field="x__New" data-hidden="1" name="o<?= $Grid->RowIndex ?>__New[]" id="o<?= $Grid->RowIndex ?>__New[]" value="<?= HtmlEncode($Grid->_New->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_product__New" class="form-group">
<div class="custom-control custom-checkbox d-inline-block">
    <input type="checkbox" class="custom-control-input<?= $Grid->_New->isInvalidClass() ?>" data-table="product" data-field="x__New" name="x<?= $Grid->RowIndex ?>__New[]" id="x<?= $Grid->RowIndex ?>__New_476107" value="1"<?= ConvertToBool($Grid->_New->CurrentValue) ? " checked" : "" ?><?= $Grid->_New->editAttributes() ?>>
    <label class="custom-control-label" for="x<?= $Grid->RowIndex ?>__New_476107"></label>
</div>
<div class="invalid-feedback"><?= $Grid->_New->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_product__New">
<span<?= $Grid->_New->viewAttributes() ?>>
<div class="custom-control custom-checkbox d-inline-block">
    <input type="checkbox" id="x__New_<?= $Grid->RowCount ?>" class="custom-control-input" value="<?= $Grid->_New->getViewValue() ?>" disabled<?php if (ConvertToBool($Grid->_New->CurrentValue)) { ?> checked<?php } ?>>
    <label class="custom-control-label" for="x__New_<?= $Grid->RowCount ?>"></label>
</div></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="product" data-field="x__New" data-hidden="1" name="fproductgrid$x<?= $Grid->RowIndex ?>__New" id="fproductgrid$x<?= $Grid->RowIndex ?>__New" value="<?= HtmlEncode($Grid->_New->FormValue) ?>">
<input type="hidden" data-table="product" data-field="x__New" data-hidden="1" name="fproductgrid$o<?= $Grid->RowIndex ?>__New[]" id="fproductgrid$o<?= $Grid->RowIndex ?>__New[]" value="<?= HtmlEncode($Grid->_New->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->Image->Visible) { // Image ?>
        <td data-name="Image" <?= $Grid->Image->cellAttributes() ?>>
<?php if ($Grid->RowAction == "insert") { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_product_Image" class="form-group product_Image">
<div id="fd_x<?= $Grid->RowIndex ?>_Image">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Grid->Image->title() ?>" data-table="product" data-field="x_Image" name="x<?= $Grid->RowIndex ?>_Image" id="x<?= $Grid->RowIndex ?>_Image" lang="<?= CurrentLanguageID() ?>"<?= $Grid->Image->editAttributes() ?><?= ($Grid->Image->ReadOnly || $Grid->Image->Disabled) ? " disabled" : "" ?>>
        <label class="custom-file-label ew-file-label" for="x<?= $Grid->RowIndex ?>_Image"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<div class="invalid-feedback"><?= $Grid->Image->getErrorMessage() ?></div>
<input type="hidden" name="fn_x<?= $Grid->RowIndex ?>_Image" id= "fn_x<?= $Grid->RowIndex ?>_Image" value="<?= $Grid->Image->Upload->FileName ?>">
<input type="hidden" name="fa_x<?= $Grid->RowIndex ?>_Image" id= "fa_x<?= $Grid->RowIndex ?>_Image" value="0">
<input type="hidden" name="fs_x<?= $Grid->RowIndex ?>_Image" id= "fs_x<?= $Grid->RowIndex ?>_Image" value="100">
<input type="hidden" name="fx_x<?= $Grid->RowIndex ?>_Image" id= "fx_x<?= $Grid->RowIndex ?>_Image" value="<?= $Grid->Image->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?= $Grid->RowIndex ?>_Image" id= "fm_x<?= $Grid->RowIndex ?>_Image" value="<?= $Grid->Image->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?= $Grid->RowIndex ?>_Image" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
<input type="hidden" data-table="product" data-field="x_Image" data-hidden="1" name="o<?= $Grid->RowIndex ?>_Image" id="o<?= $Grid->RowIndex ?>_Image" value="<?= HtmlEncode($Grid->Image->OldValue) ?>">
<?php } elseif ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_product_Image">
<span>
<?= GetFileViewTag($Grid->Image, $Grid->Image->getViewValue(), false) ?>
</span>
</span>
<?php } else  { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_product_Image" class="form-group product_Image">
<div id="fd_x<?= $Grid->RowIndex ?>_Image">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Grid->Image->title() ?>" data-table="product" data-field="x_Image" name="x<?= $Grid->RowIndex ?>_Image" id="x<?= $Grid->RowIndex ?>_Image" lang="<?= CurrentLanguageID() ?>"<?= $Grid->Image->editAttributes() ?><?= ($Grid->Image->ReadOnly || $Grid->Image->Disabled) ? " disabled" : "" ?>>
        <label class="custom-file-label ew-file-label" for="x<?= $Grid->RowIndex ?>_Image"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<div class="invalid-feedback"><?= $Grid->Image->getErrorMessage() ?></div>
<input type="hidden" name="fn_x<?= $Grid->RowIndex ?>_Image" id= "fn_x<?= $Grid->RowIndex ?>_Image" value="<?= $Grid->Image->Upload->FileName ?>">
<input type="hidden" name="fa_x<?= $Grid->RowIndex ?>_Image" id= "fa_x<?= $Grid->RowIndex ?>_Image" value="<?= (Post("fa_x<?= $Grid->RowIndex ?>_Image") == "0") ? "0" : "1" ?>">
<input type="hidden" name="fs_x<?= $Grid->RowIndex ?>_Image" id= "fs_x<?= $Grid->RowIndex ?>_Image" value="100">
<input type="hidden" name="fx_x<?= $Grid->RowIndex ?>_Image" id= "fx_x<?= $Grid->RowIndex ?>_Image" value="<?= $Grid->Image->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?= $Grid->RowIndex ?>_Image" id= "fm_x<?= $Grid->RowIndex ?>_Image" value="<?= $Grid->Image->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?= $Grid->RowIndex ?>_Image" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
<?php } ?>
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Grid->ListOptions->render("body", "right", $Grid->RowCount);
?>
    </tr>
<?php if ($Grid->RowType == ROWTYPE_ADD || $Grid->RowType == ROWTYPE_EDIT) { ?>
<script>
loadjs.ready(["fproductgrid","load"], function () {
    fproductgrid.updateLists(<?= $Grid->RowIndex ?>);
});
</script>
<?php } ?>
<?php
    }
    } // End delete row checking
    if (!$Grid->isGridAdd() || $Grid->CurrentMode == "copy")
        if (!$Grid->Recordset->EOF) {
            $Grid->Recordset->moveNext();
        }
}
?>
<?php
    if ($Grid->CurrentMode == "add" || $Grid->CurrentMode == "copy" || $Grid->CurrentMode == "edit") {
        $Grid->RowIndex = '$rowindex$';
        $Grid->loadRowValues();

        // Set row properties
        $Grid->resetAttributes();
        $Grid->RowAttrs->merge(["data-rowindex" => $Grid->RowIndex, "id" => "r0_product", "data-rowtype" => ROWTYPE_ADD]);
        $Grid->RowAttrs->appendClass("ew-template");
        $Grid->RowType = ROWTYPE_ADD;

        // Render row
        $Grid->renderRow();

        // Render list options
        $Grid->renderListOptions();
        $Grid->StartRowCount = 0;
?>
    <tr <?= $Grid->rowAttributes() ?>>
<?php
// Render list options (body, left)
$Grid->ListOptions->render("body", "left", $Grid->RowIndex);
?>
    <?php if ($Grid->Category_ID->Visible) { // Category_ID ?>
        <td data-name="Category_ID">
<?php if (!$Grid->isConfirm()) { ?>
<?php if ($Grid->Category_ID->getSessionValue() != "") { ?>
<span id="el$rowindex$_product_Category_ID" class="form-group product_Category_ID">
<span<?= $Grid->Category_ID->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->Category_ID->getDisplayValue($Grid->Category_ID->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_Category_ID" name="x<?= $Grid->RowIndex ?>_Category_ID" value="<?= HtmlEncode($Grid->Category_ID->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el$rowindex$_product_Category_ID" class="form-group product_Category_ID">
<div class="input-group flex-nowrap">
    <select
        id="x<?= $Grid->RowIndex ?>_Category_ID"
        name="x<?= $Grid->RowIndex ?>_Category_ID"
        class="form-control ew-select<?= $Grid->Category_ID->isInvalidClass() ?>"
        data-select2-id="product_x<?= $Grid->RowIndex ?>_Category_ID"
        data-table="product"
        data-field="x_Category_ID"
        data-value-separator="<?= $Grid->Category_ID->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->Category_ID->getPlaceHolder()) ?>"
        <?= $Grid->Category_ID->editAttributes() ?>>
        <?= $Grid->Category_ID->selectOptionListHtml("x{$Grid->RowIndex}_Category_ID") ?>
    </select>
    <?php if (AllowAdd(CurrentProjectID() . "category") && !$Grid->Category_ID->ReadOnly) { ?>
    <div class="input-group-append"><button type="button" class="btn btn-default ew-add-opt-btn" id="aol_x<?= $Grid->RowIndex ?>_Category_ID" title="<?= HtmlTitle($Language->phrase("AddLink")) . "&nbsp;" . $Grid->Category_ID->caption() ?>" data-title="<?= $Grid->Category_ID->caption() ?>" onclick="ew.addOptionDialogShow({lnk:this,el:'x<?= $Grid->RowIndex ?>_Category_ID',url:'<?= GetUrl("CategoryAddopt") ?>'});"><i class="fas fa-plus ew-icon"></i></button></div>
    <?php } ?>
</div>
<div class="invalid-feedback"><?= $Grid->Category_ID->getErrorMessage() ?></div>
<?= $Grid->Category_ID->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_Category_ID") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='product_x<?= $Grid->RowIndex ?>_Category_ID']"),
        options = { name: "x<?= $Grid->RowIndex ?>_Category_ID", selectId: "product_x<?= $Grid->RowIndex ?>_Category_ID", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.product.fields.Category_ID.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_product_Category_ID" class="form-group product_Category_ID">
<span<?= $Grid->Category_ID->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->Category_ID->getDisplayValue($Grid->Category_ID->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="product" data-field="x_Category_ID" data-hidden="1" name="x<?= $Grid->RowIndex ?>_Category_ID" id="x<?= $Grid->RowIndex ?>_Category_ID" value="<?= HtmlEncode($Grid->Category_ID->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="product" data-field="x_Category_ID" data-hidden="1" name="o<?= $Grid->RowIndex ?>_Category_ID" id="o<?= $Grid->RowIndex ?>_Category_ID" value="<?= HtmlEncode($Grid->Category_ID->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->Name->Visible) { // Name ?>
        <td data-name="Name">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_product_Name" class="form-group product_Name">
<input type="<?= $Grid->Name->getInputTextType() ?>" data-table="product" data-field="x_Name" name="x<?= $Grid->RowIndex ?>_Name" id="x<?= $Grid->RowIndex ?>_Name" size="30" maxlength="200" placeholder="<?= HtmlEncode($Grid->Name->getPlaceHolder()) ?>" value="<?= $Grid->Name->EditValue ?>"<?= $Grid->Name->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->Name->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_product_Name" class="form-group product_Name">
<span<?= $Grid->Name->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->Name->getDisplayValue($Grid->Name->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="product" data-field="x_Name" data-hidden="1" name="x<?= $Grid->RowIndex ?>_Name" id="x<?= $Grid->RowIndex ?>_Name" value="<?= HtmlEncode($Grid->Name->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="product" data-field="x_Name" data-hidden="1" name="o<?= $Grid->RowIndex ?>_Name" id="o<?= $Grid->RowIndex ?>_Name" value="<?= HtmlEncode($Grid->Name->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->Price->Visible) { // Price ?>
        <td data-name="Price">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_product_Price" class="form-group product_Price">
<input type="<?= $Grid->Price->getInputTextType() ?>" data-table="product" data-field="x_Price" name="x<?= $Grid->RowIndex ?>_Price" id="x<?= $Grid->RowIndex ?>_Price" size="10" placeholder="<?= HtmlEncode($Grid->Price->getPlaceHolder()) ?>" value="<?= $Grid->Price->EditValue ?>"<?= $Grid->Price->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->Price->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_product_Price" class="form-group product_Price">
<span<?= $Grid->Price->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->Price->getDisplayValue($Grid->Price->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="product" data-field="x_Price" data-hidden="1" name="x<?= $Grid->RowIndex ?>_Price" id="x<?= $Grid->RowIndex ?>_Price" value="<?= HtmlEncode($Grid->Price->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="product" data-field="x_Price" data-hidden="1" name="o<?= $Grid->RowIndex ?>_Price" id="o<?= $Grid->RowIndex ?>_Price" value="<?= HtmlEncode($Grid->Price->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->_New->Visible) { // New ?>
        <td data-name="_New">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_product__New" class="form-group product__New">
<div class="custom-control custom-checkbox d-inline-block">
    <input type="checkbox" class="custom-control-input<?= $Grid->_New->isInvalidClass() ?>" data-table="product" data-field="x__New" name="x<?= $Grid->RowIndex ?>__New[]" id="x<?= $Grid->RowIndex ?>__New_567817" value="1"<?= ConvertToBool($Grid->_New->CurrentValue) ? " checked" : "" ?><?= $Grid->_New->editAttributes() ?>>
    <label class="custom-control-label" for="x<?= $Grid->RowIndex ?>__New_567817"></label>
</div>
<div class="invalid-feedback"><?= $Grid->_New->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_product__New" class="form-group product__New">
<span<?= $Grid->_New->viewAttributes() ?>>
<div class="custom-control custom-checkbox d-inline-block">
    <input type="checkbox" id="x__New_<?= $Grid->RowCount ?>" class="custom-control-input" value="<?= $Grid->_New->ViewValue ?>" disabled<?php if (ConvertToBool($Grid->_New->CurrentValue)) { ?> checked<?php } ?>>
    <label class="custom-control-label" for="x__New_<?= $Grid->RowCount ?>"></label>
</div></span>
</span>
<input type="hidden" data-table="product" data-field="x__New" data-hidden="1" name="x<?= $Grid->RowIndex ?>__New" id="x<?= $Grid->RowIndex ?>__New" value="<?= HtmlEncode($Grid->_New->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="product" data-field="x__New" data-hidden="1" name="o<?= $Grid->RowIndex ?>__New[]" id="o<?= $Grid->RowIndex ?>__New[]" value="<?= HtmlEncode($Grid->_New->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->Image->Visible) { // Image ?>
        <td data-name="Image">
<span id="el$rowindex$_product_Image" class="form-group product_Image">
<div id="fd_x<?= $Grid->RowIndex ?>_Image">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Grid->Image->title() ?>" data-table="product" data-field="x_Image" name="x<?= $Grid->RowIndex ?>_Image" id="x<?= $Grid->RowIndex ?>_Image" lang="<?= CurrentLanguageID() ?>"<?= $Grid->Image->editAttributes() ?><?= ($Grid->Image->ReadOnly || $Grid->Image->Disabled) ? " disabled" : "" ?>>
        <label class="custom-file-label ew-file-label" for="x<?= $Grid->RowIndex ?>_Image"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<div class="invalid-feedback"><?= $Grid->Image->getErrorMessage() ?></div>
<input type="hidden" name="fn_x<?= $Grid->RowIndex ?>_Image" id= "fn_x<?= $Grid->RowIndex ?>_Image" value="<?= $Grid->Image->Upload->FileName ?>">
<input type="hidden" name="fa_x<?= $Grid->RowIndex ?>_Image" id= "fa_x<?= $Grid->RowIndex ?>_Image" value="0">
<input type="hidden" name="fs_x<?= $Grid->RowIndex ?>_Image" id= "fs_x<?= $Grid->RowIndex ?>_Image" value="100">
<input type="hidden" name="fx_x<?= $Grid->RowIndex ?>_Image" id= "fx_x<?= $Grid->RowIndex ?>_Image" value="<?= $Grid->Image->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?= $Grid->RowIndex ?>_Image" id= "fm_x<?= $Grid->RowIndex ?>_Image" value="<?= $Grid->Image->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?= $Grid->RowIndex ?>_Image" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
<input type="hidden" data-table="product" data-field="x_Image" data-hidden="1" name="o<?= $Grid->RowIndex ?>_Image" id="o<?= $Grid->RowIndex ?>_Image" value="<?= HtmlEncode($Grid->Image->OldValue) ?>">
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Grid->ListOptions->render("body", "right", $Grid->RowIndex);
?>
<script>
loadjs.ready(["fproductgrid","load"], function() {
    fproductgrid.updateLists(<?= $Grid->RowIndex ?>);
});
</script>
    </tr>
<?php
    }
?>
</tbody>
</table><!-- /.ew-table -->
</div><!-- /.ew-grid-middle-panel -->
<?php if ($Grid->CurrentMode == "add" || $Grid->CurrentMode == "copy") { ?>
<input type="hidden" name="<?= $Grid->FormKeyCountName ?>" id="<?= $Grid->FormKeyCountName ?>" value="<?= $Grid->KeyCount ?>">
<?= $Grid->MultiSelectKey ?>
<?php } ?>
<?php if ($Grid->CurrentMode == "edit") { ?>
<input type="hidden" name="<?= $Grid->FormKeyCountName ?>" id="<?= $Grid->FormKeyCountName ?>" value="<?= $Grid->KeyCount ?>">
<?= $Grid->MultiSelectKey ?>
<?php } ?>
<?php if ($Grid->CurrentMode == "") { ?>
<input type="hidden" name="action" id="action" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fproductgrid">
</div><!-- /.ew-list-form -->
<?php
// Close recordset
if ($Grid->Recordset) {
    $Grid->Recordset->close();
}
?>
<?php if ($Grid->ShowOtherOptions) { ?>
<div class="card-footer ew-grid-lower-panel">
<?php $Grid->OtherOptions->render("body", "bottom") ?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div><!-- /.ew-grid -->
<?php } ?>
<?php if ($Grid->TotalRecords == 0 && !$Grid->CurrentAction) { // Show other options ?>
<div class="ew-list-other-options">
<?php $Grid->OtherOptions->render("body") ?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if (!$Grid->isExport()) { ?>
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
<?php } ?>

<?php

namespace PHPMaker2021\inplaze;

// Page object
$ProductDelete = &$Page;
?>
<script>
var currentForm, currentPageID;
var fproductdelete;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "delete";
    fproductdelete = currentForm = new ew.Form("fproductdelete", "delete");
    loadjs.done("fproductdelete");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<script>
if (!ew.vars.tables.product) ew.vars.tables.product = <?= JsonEncode(GetClientVar("tables", "product")) ?>;
</script>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<form name="fproductdelete" id="fproductdelete" class="form-inline ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="product">
<input type="hidden" name="action" id="action" value="delete">
<?php foreach ($Page->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode(Config("COMPOSITE_KEY_SEPARATOR"), $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?= HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="card ew-card ew-grid">
<div class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<table class="table ew-table">
    <thead>
    <tr class="ew-table-header">
<?php if ($Page->Category_ID->Visible) { // Category_ID ?>
        <th class="<?= $Page->Category_ID->headerCellClass() ?>"><span id="elh_product_Category_ID" class="product_Category_ID"><?= $Page->Category_ID->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Name->Visible) { // Name ?>
        <th class="<?= $Page->Name->headerCellClass() ?>"><span id="elh_product_Name" class="product_Name"><?= $Page->Name->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Price->Visible) { // Price ?>
        <th class="<?= $Page->Price->headerCellClass() ?>"><span id="elh_product_Price" class="product_Price"><?= $Page->Price->caption() ?></span></th>
<?php } ?>
<?php if ($Page->_New->Visible) { // New ?>
        <th class="<?= $Page->_New->headerCellClass() ?>"><span id="elh_product__New" class="product__New"><?= $Page->_New->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Image->Visible) { // Image ?>
        <th class="<?= $Page->Image->headerCellClass() ?>"><span id="elh_product_Image" class="product_Image"><?= $Page->Image->caption() ?></span></th>
<?php } ?>
    </tr>
    </thead>
    <tbody>
<?php
$Page->RecordCount = 0;
$i = 0;
while (!$Page->Recordset->EOF) {
    $Page->RecordCount++;
    $Page->RowCount++;

    // Set row properties
    $Page->resetAttributes();
    $Page->RowType = ROWTYPE_VIEW; // View

    // Get the field contents
    $Page->loadRowValues($Page->Recordset);

    // Render row
    $Page->renderRow();
?>
    <tr <?= $Page->rowAttributes() ?>>
<?php if ($Page->Category_ID->Visible) { // Category_ID ?>
        <td <?= $Page->Category_ID->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_product_Category_ID" class="product_Category_ID">
<span<?= $Page->Category_ID->viewAttributes() ?>>
<?= $Page->Category_ID->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->Name->Visible) { // Name ?>
        <td <?= $Page->Name->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_product_Name" class="product_Name">
<span<?= $Page->Name->viewAttributes() ?>>
<?= $Page->Name->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->Price->Visible) { // Price ?>
        <td <?= $Page->Price->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_product_Price" class="product_Price">
<span<?= $Page->Price->viewAttributes() ?>>
<?= $Page->Price->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->_New->Visible) { // New ?>
        <td <?= $Page->_New->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_product__New" class="product__New">
<span<?= $Page->_New->viewAttributes() ?>>
<div class="custom-control custom-checkbox d-inline-block">
    <input type="checkbox" id="x__New_<?= $Page->RowCount ?>" class="custom-control-input" value="<?= $Page->_New->getViewValue() ?>" disabled<?php if (ConvertToBool($Page->_New->CurrentValue)) { ?> checked<?php } ?>>
    <label class="custom-control-label" for="x__New_<?= $Page->RowCount ?>"></label>
</div></span>
</span>
</td>
<?php } ?>
<?php if ($Page->Image->Visible) { // Image ?>
        <td <?= $Page->Image->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_product_Image" class="product_Image">
<span>
<?= GetFileViewTag($Page->Image, $Page->Image->getViewValue(), false) ?>
</span>
</span>
</td>
<?php } ?>
    </tr>
<?php
    $Page->Recordset->moveNext();
}
$Page->Recordset->close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit"><?= $Language->phrase("DeleteBtn") ?></button>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
</div>
</form>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>

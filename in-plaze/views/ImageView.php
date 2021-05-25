<?php

namespace PHPMaker2021\inplaze;

// Page object
$ImageView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fimageview;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "view";
    fimageview = currentForm = new ew.Form("fimageview", "view");
    loadjs.done("fimageview");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php } ?>
<script>
if (!ew.vars.tables.image) ew.vars.tables.image = <?= JsonEncode(GetClientVar("tables", "image")) ?>;
</script>
<?php if (!$Page->isExport()) { ?>
<div class="btn-toolbar ew-toolbar">
<?php $Page->ExportOptions->render("body") ?>
<?php $Page->OtherOptions->render("body") ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<form name="fimageview" id="fimageview" class="form-inline ew-form ew-view-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="image">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->Image_ID->Visible) { // Image_ID ?>
    <tr id="r_Image_ID">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_image_Image_ID"><?= $Page->Image_ID->caption() ?></span></td>
        <td data-name="Image_ID" <?= $Page->Image_ID->cellAttributes() ?>>
<span id="el_image_Image_ID">
<span<?= $Page->Image_ID->viewAttributes() ?>>
<?= $Page->Image_ID->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Name->Visible) { // Name ?>
    <tr id="r_Name">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_image_Name"><?= $Page->Name->caption() ?></span></td>
        <td data-name="Name" <?= $Page->Name->cellAttributes() ?>>
<span id="el_image_Name">
<span<?= $Page->Name->viewAttributes() ?>>
<?= $Page->Name->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Value->Visible) { // Value ?>
    <tr id="r_Value">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_image_Value"><?= $Page->Value->caption() ?></span></td>
        <td data-name="Value" <?= $Page->Value->cellAttributes() ?>>
<span id="el_image_Value">
<span>
<?= GetFileViewTag($Page->Value, $Page->Value->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Enable->Visible) { // Enable ?>
    <tr id="r_Enable">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_image_Enable"><?= $Page->Enable->caption() ?></span></td>
        <td data-name="Enable" <?= $Page->Enable->cellAttributes() ?>>
<span id="el_image_Enable">
<span<?= $Page->Enable->viewAttributes() ?>>
<div class="custom-control custom-checkbox d-inline-block">
    <input type="checkbox" id="x_Enable_<?= $Page->RowCount ?>" class="custom-control-input" value="<?= $Page->Enable->getViewValue() ?>" disabled<?php if (ConvertToBool($Page->Enable->CurrentValue)) { ?> checked<?php } ?>>
    <label class="custom-control-label" for="x_Enable_<?= $Page->RowCount ?>"></label>
</div></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Created->Visible) { // Created ?>
    <tr id="r_Created">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_image_Created"><?= $Page->Created->caption() ?></span></td>
        <td data-name="Created" <?= $Page->Created->cellAttributes() ?>>
<span id="el_image_Created">
<span<?= $Page->Created->viewAttributes() ?>>
<?= $Page->Created->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Modified->Visible) { // Modified ?>
    <tr id="r_Modified">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_image_Modified"><?= $Page->Modified->caption() ?></span></td>
        <td data-name="Modified" <?= $Page->Modified->cellAttributes() ?>>
<span id="el_image_Modified">
<span<?= $Page->Modified->viewAttributes() ?>>
<?= $Page->Modified->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
</table>
</form>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<?php if (!$Page->isExport()) { ?>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>

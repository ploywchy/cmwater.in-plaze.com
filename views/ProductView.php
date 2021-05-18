<?php

namespace PHPMaker2021\inplaze;

// Page object
$ProductView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fproductview;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "view";
    fproductview = currentForm = new ew.Form("fproductview", "view");
    loadjs.done("fproductview");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php } ?>
<script>
if (!ew.vars.tables.product) ew.vars.tables.product = <?= JsonEncode(GetClientVar("tables", "product")) ?>;
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
<form name="fproductview" id="fproductview" class="form-inline ew-form ew-view-form" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="product">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->Category_ID->Visible) { // Category_ID ?>
    <tr id="r_Category_ID">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_product_Category_ID"><?= $Page->Category_ID->caption() ?></span></td>
        <td data-name="Category_ID" <?= $Page->Category_ID->cellAttributes() ?>>
<span id="el_product_Category_ID">
<span<?= $Page->Category_ID->viewAttributes() ?>>
<?= $Page->Category_ID->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Name->Visible) { // Name ?>
    <tr id="r_Name">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_product_Name"><?= $Page->Name->caption() ?></span></td>
        <td data-name="Name" <?= $Page->Name->cellAttributes() ?>>
<span id="el_product_Name">
<span<?= $Page->Name->viewAttributes() ?>>
<?= $Page->Name->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Intro->Visible) { // Intro ?>
    <tr id="r_Intro">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_product_Intro"><?= $Page->Intro->caption() ?></span></td>
        <td data-name="Intro" <?= $Page->Intro->cellAttributes() ?>>
<span id="el_product_Intro">
<span<?= $Page->Intro->viewAttributes() ?>>
<?= $Page->Intro->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Description->Visible) { // Description ?>
    <tr id="r_Description">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_product_Description"><?= $Page->Description->caption() ?></span></td>
        <td data-name="Description" <?= $Page->Description->cellAttributes() ?>>
<span id="el_product_Description">
<span<?= $Page->Description->viewAttributes() ?>>
<?= $Page->Description->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Price->Visible) { // Price ?>
    <tr id="r_Price">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_product_Price"><?= $Page->Price->caption() ?></span></td>
        <td data-name="Price" <?= $Page->Price->cellAttributes() ?>>
<span id="el_product_Price">
<span<?= $Page->Price->viewAttributes() ?>>
<?= $Page->Price->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Image->Visible) { // Image ?>
    <tr id="r_Image">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_product_Image"><?= $Page->Image->caption() ?></span></td>
        <td data-name="Image" <?= $Page->Image->cellAttributes() ?>>
<span id="el_product_Image">
<span>
<?= GetFileViewTag($Page->Image, $Page->Image->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Created->Visible) { // Created ?>
    <tr id="r_Created">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_product_Created"><?= $Page->Created->caption() ?></span></td>
        <td data-name="Created" <?= $Page->Created->cellAttributes() ?>>
<span id="el_product_Created">
<span<?= $Page->Created->viewAttributes() ?>>
<?= $Page->Created->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Modified->Visible) { // Modified ?>
    <tr id="r_Modified">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_product_Modified"><?= $Page->Modified->caption() ?></span></td>
        <td data-name="Modified" <?= $Page->Modified->cellAttributes() ?>>
<span id="el_product_Modified">
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

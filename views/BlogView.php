<?php

namespace PHPMaker2021\inplaze;

// Page object
$BlogView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fblogview;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "view";
    fblogview = currentForm = new ew.Form("fblogview", "view");
    loadjs.done("fblogview");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php } ?>
<script>
if (!ew.vars.tables.blog) ew.vars.tables.blog = <?= JsonEncode(GetClientVar("tables", "blog")) ?>;
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
<form name="fblogview" id="fblogview" class="form-inline ew-form ew-view-form" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="blog">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->Image->Visible) { // Image ?>
    <tr id="r_Image">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_blog_Image"><?= $Page->Image->caption() ?></span></td>
        <td data-name="Image" <?= $Page->Image->cellAttributes() ?>>
<span id="el_blog_Image">
<span>
<?= GetFileViewTag($Page->Image, $Page->Image->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Title->Visible) { // Title ?>
    <tr id="r_Title">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_blog_Title"><?= $Page->Title->caption() ?></span></td>
        <td data-name="Title" <?= $Page->Title->cellAttributes() ?>>
<span id="el_blog_Title">
<span<?= $Page->Title->viewAttributes() ?>>
<?= $Page->Title->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Intro->Visible) { // Intro ?>
    <tr id="r_Intro">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_blog_Intro"><?= $Page->Intro->caption() ?></span></td>
        <td data-name="Intro" <?= $Page->Intro->cellAttributes() ?>>
<span id="el_blog_Intro">
<span<?= $Page->Intro->viewAttributes() ?>>
<?= $Page->Intro->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->_Content->Visible) { // Content ?>
    <tr id="r__Content">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_blog__Content"><?= $Page->_Content->caption() ?></span></td>
        <td data-name="_Content" <?= $Page->_Content->cellAttributes() ?>>
<span id="el_blog__Content">
<span<?= $Page->_Content->viewAttributes() ?>>
<?= $Page->_Content->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Created->Visible) { // Created ?>
    <tr id="r_Created">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_blog_Created"><?= $Page->Created->caption() ?></span></td>
        <td data-name="Created" <?= $Page->Created->cellAttributes() ?>>
<span id="el_blog_Created">
<span<?= $Page->Created->viewAttributes() ?>>
<?= $Page->Created->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Modified->Visible) { // Modified ?>
    <tr id="r_Modified">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_blog_Modified"><?= $Page->Modified->caption() ?></span></td>
        <td data-name="Modified" <?= $Page->Modified->cellAttributes() ?>>
<span id="el_blog_Modified">
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

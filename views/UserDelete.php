<?php

namespace PHPMaker2021\inplaze;

// Page object
$UserDelete = &$Page;
?>
<script>
var currentForm, currentPageID;
var fuserdelete;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "delete";
    fuserdelete = currentForm = new ew.Form("fuserdelete", "delete");
    loadjs.done("fuserdelete");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<script>
if (!ew.vars.tables.user) ew.vars.tables.user = <?= JsonEncode(GetClientVar("tables", "user")) ?>;
</script>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<form name="fuserdelete" id="fuserdelete" class="form-inline ew-form ew-delete-form" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="user">
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
<?php if ($Page->Name->Visible) { // Name ?>
        <th class="<?= $Page->Name->headerCellClass() ?>"><span id="elh_user_Name" class="user_Name"><?= $Page->Name->caption() ?></span></th>
<?php } ?>
<?php if ($Page->_Username->Visible) { // Username ?>
        <th class="<?= $Page->_Username->headerCellClass() ?>"><span id="elh_user__Username" class="user__Username"><?= $Page->_Username->caption() ?></span></th>
<?php } ?>
<?php if ($Page->User_Level_ID->Visible) { // User_Level_ID ?>
        <th class="<?= $Page->User_Level_ID->headerCellClass() ?>"><span id="elh_user_User_Level_ID" class="user_User_Level_ID"><?= $Page->User_Level_ID->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Enable->Visible) { // Enable ?>
        <th class="<?= $Page->Enable->headerCellClass() ?>"><span id="elh_user_Enable" class="user_Enable"><?= $Page->Enable->caption() ?></span></th>
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
<?php if ($Page->Name->Visible) { // Name ?>
        <td <?= $Page->Name->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_user_Name" class="user_Name">
<span<?= $Page->Name->viewAttributes() ?>>
<?= $Page->Name->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->_Username->Visible) { // Username ?>
        <td <?= $Page->_Username->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_user__Username" class="user__Username">
<span<?= $Page->_Username->viewAttributes() ?>>
<?= $Page->_Username->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->User_Level_ID->Visible) { // User_Level_ID ?>
        <td <?= $Page->User_Level_ID->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_user_User_Level_ID" class="user_User_Level_ID">
<span<?= $Page->User_Level_ID->viewAttributes() ?>>
<?= $Page->User_Level_ID->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->Enable->Visible) { // Enable ?>
        <td <?= $Page->Enable->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_user_Enable" class="user_Enable">
<span<?= $Page->Enable->viewAttributes() ?>>
<div class="custom-control custom-checkbox d-inline-block">
    <input type="checkbox" id="x_Enable_<?= $Page->RowCount ?>" class="custom-control-input" value="<?= $Page->Enable->getViewValue() ?>" disabled<?php if (ConvertToBool($Page->Enable->CurrentValue)) { ?> checked<?php } ?>>
    <label class="custom-control-label" for="x_Enable_<?= $Page->RowCount ?>"></label>
</div></span>
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

<?php

namespace PHPMaker2021\inplaze;

// Page object
$UserAdd = &$Page;
?>
<script>
var currentForm, currentPageID;
var fuseradd;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "add";
    fuseradd = currentForm = new ew.Form("fuseradd", "add");

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "user")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.user)
        ew.vars.tables.user = currentTable;
    fuseradd.addFields([
        ["Name", [fields.Name.visible && fields.Name.required ? ew.Validators.required(fields.Name.caption) : null], fields.Name.isInvalid],
        ["_Username", [fields._Username.visible && fields._Username.required ? ew.Validators.required(fields._Username.caption) : null], fields._Username.isInvalid],
        ["_Password", [fields._Password.visible && fields._Password.required ? ew.Validators.required(fields._Password.caption) : null], fields._Password.isInvalid],
        ["User_Level_ID", [fields.User_Level_ID.visible && fields.User_Level_ID.required ? ew.Validators.required(fields.User_Level_ID.caption) : null], fields.User_Level_ID.isInvalid],
        ["_Email", [fields._Email.visible && fields._Email.required ? ew.Validators.required(fields._Email.caption) : null], fields._Email.isInvalid],
        ["Enable", [fields.Enable.visible && fields.Enable.required ? ew.Validators.required(fields.Enable.caption) : null], fields.Enable.isInvalid],
        ["Created_Time", [fields.Created_Time.visible && fields.Created_Time.required ? ew.Validators.required(fields.Created_Time.caption) : null], fields.Created_Time.isInvalid],
        ["Modified_Time", [fields.Modified_Time.visible && fields.Modified_Time.required ? ew.Validators.required(fields.Modified_Time.caption) : null], fields.Modified_Time.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fuseradd,
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
    fuseradd.validate = function () {
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
    fuseradd.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fuseradd.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fuseradd.lists.User_Level_ID = <?= $Page->User_Level_ID->toClientList($Page) ?>;
    fuseradd.lists.Enable = <?= $Page->Enable->toClientList($Page) ?>;
    loadjs.done("fuseradd");
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
<form name="fuseradd" id="fuseradd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="user">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->Name->Visible) { // Name ?>
    <div id="r_Name" class="form-group row">
        <label id="elh_user_Name" for="x_Name" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Name->caption() ?><?= $Page->Name->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->Name->cellAttributes() ?>>
<span id="el_user_Name">
<input type="<?= $Page->Name->getInputTextType() ?>" data-table="user" data-field="x_Name" name="x_Name" id="x_Name" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->Name->getPlaceHolder()) ?>" value="<?= $Page->Name->EditValue ?>"<?= $Page->Name->editAttributes() ?> aria-describedby="x_Name_help">
<?= $Page->Name->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Name->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->_Username->Visible) { // Username ?>
    <div id="r__Username" class="form-group row">
        <label id="elh_user__Username" for="x__Username" class="<?= $Page->LeftColumnClass ?>"><?= $Page->_Username->caption() ?><?= $Page->_Username->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->_Username->cellAttributes() ?>>
<span id="el_user__Username">
<input type="<?= $Page->_Username->getInputTextType() ?>" data-table="user" data-field="x__Username" name="x__Username" id="x__Username" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->_Username->getPlaceHolder()) ?>" value="<?= $Page->_Username->EditValue ?>"<?= $Page->_Username->editAttributes() ?> aria-describedby="x__Username_help">
<?= $Page->_Username->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->_Username->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->_Password->Visible) { // Password ?>
    <div id="r__Password" class="form-group row">
        <label id="elh_user__Password" for="x__Password" class="<?= $Page->LeftColumnClass ?>"><?= $Page->_Password->caption() ?><?= $Page->_Password->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->_Password->cellAttributes() ?>>
<span id="el_user__Password">
<div class="input-group">
    <input type="password" name="x__Password" id="x__Password" autocomplete="new-password" data-field="x__Password" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->_Password->getPlaceHolder()) ?>"<?= $Page->_Password->editAttributes() ?> aria-describedby="x__Password_help">
    <div class="input-group-append"><button type="button" class="btn btn-default ew-toggle-password rounded-right" onclick="ew.togglePassword(event);"><i class="fas fa-eye"></i></button></div>
</div>
<?= $Page->_Password->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->_Password->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->User_Level_ID->Visible) { // User_Level_ID ?>
    <div id="r_User_Level_ID" class="form-group row">
        <label id="elh_user_User_Level_ID" for="x_User_Level_ID" class="<?= $Page->LeftColumnClass ?>"><?= $Page->User_Level_ID->caption() ?><?= $Page->User_Level_ID->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->User_Level_ID->cellAttributes() ?>>
<?php if (!$Security->isAdmin() && $Security->isLoggedIn()) { // Non system admin ?>
<span id="el_user_User_Level_ID">
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->User_Level_ID->getDisplayValue($Page->User_Level_ID->EditValue))) ?>">
</span>
<?php } else { ?>
<span id="el_user_User_Level_ID">
    <select
        id="x_User_Level_ID"
        name="x_User_Level_ID"
        class="form-control ew-select<?= $Page->User_Level_ID->isInvalidClass() ?>"
        data-select2-id="user_x_User_Level_ID"
        data-table="user"
        data-field="x_User_Level_ID"
        data-value-separator="<?= $Page->User_Level_ID->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->User_Level_ID->getPlaceHolder()) ?>"
        <?= $Page->User_Level_ID->editAttributes() ?>>
        <?= $Page->User_Level_ID->selectOptionListHtml("x_User_Level_ID") ?>
    </select>
    <?= $Page->User_Level_ID->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->User_Level_ID->getErrorMessage() ?></div>
<?= $Page->User_Level_ID->Lookup->getParamTag($Page, "p_x_User_Level_ID") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='user_x_User_Level_ID']"),
        options = { name: "x_User_Level_ID", selectId: "user_x_User_Level_ID", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.user.fields.User_Level_ID.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->_Email->Visible) { // Email ?>
    <div id="r__Email" class="form-group row">
        <label id="elh_user__Email" for="x__Email" class="<?= $Page->LeftColumnClass ?>"><?= $Page->_Email->caption() ?><?= $Page->_Email->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->_Email->cellAttributes() ?>>
<span id="el_user__Email">
<input type="<?= $Page->_Email->getInputTextType() ?>" data-table="user" data-field="x__Email" name="x__Email" id="x__Email" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->_Email->getPlaceHolder()) ?>" value="<?= $Page->_Email->EditValue ?>"<?= $Page->_Email->editAttributes() ?> aria-describedby="x__Email_help">
<?= $Page->_Email->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->_Email->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Enable->Visible) { // Enable ?>
    <div id="r_Enable" class="form-group row">
        <label id="elh_user_Enable" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Enable->caption() ?><?= $Page->Enable->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->Enable->cellAttributes() ?>>
<span id="el_user_Enable">
<div class="custom-control custom-checkbox d-inline-block">
    <input type="checkbox" class="custom-control-input<?= $Page->Enable->isInvalidClass() ?>" data-table="user" data-field="x_Enable" name="x_Enable[]" id="x_Enable_428623" value="1"<?= ConvertToBool($Page->Enable->CurrentValue) ? " checked" : "" ?><?= $Page->Enable->editAttributes() ?> aria-describedby="x_Enable_help">
    <label class="custom-control-label" for="x_Enable_428623"></label>
</div>
<?= $Page->Enable->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Enable->getErrorMessage() ?></div>
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
    ew.addEventHandlers("user");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>

<?php

namespace PHPMaker2021\inplaze;

// Table
$category = Container("category");
?>
<?php if ($category->Visible) { ?>
<div id="t_category" class="card <?= ResponsiveTableClass() ?>ew-grid ew-list-form ew-master-div">
<table id="tbl_categorymaster" class="table ew-table ew-master-table ew-horizontal">
    <thead>
        <tr class="ew-table-header">
<?php if ($category->Image->Visible) { // Image ?>
            <th class="<?= $category->Image->headerCellClass() ?>"><?= $category->Image->caption() ?></th>
<?php } ?>
<?php if ($category->Name->Visible) { // Name ?>
            <th class="<?= $category->Name->headerCellClass() ?>"><?= $category->Name->caption() ?></th>
<?php } ?>
        </tr>
    </thead>
    <tbody>
        <tr>
<?php if ($category->Image->Visible) { // Image ?>
            <td <?= $category->Image->cellAttributes() ?>>
<span id="el_category_Image">
<span>
<?= GetFileViewTag($category->Image, $category->Image->getViewValue(), false) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($category->Name->Visible) { // Name ?>
            <td <?= $category->Name->cellAttributes() ?>>
<span id="el_category_Name">
<span<?= $category->Name->viewAttributes() ?>>
<?= $category->Name->getViewValue() ?></span>
</span>
</td>
<?php } ?>
        </tr>
    </tbody>
</table>
</div>
<?php } ?>

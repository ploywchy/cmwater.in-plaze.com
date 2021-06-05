<?php

namespace PHPMaker2021\inplaze;

// Menu Language
if ($Language && function_exists(PROJECT_NAMESPACE . "Config") && $Language->LanguageFolder == Config("LANGUAGE_FOLDER")) {
    $MenuRelativePath = "";
    $MenuLanguage = &$Language;
} else { // Compat reports
    $LANGUAGE_FOLDER = "../lang/";
    $MenuRelativePath = "../";
    $MenuLanguage = Container("language");
}

// Navbar menu
$topMenu = new Menu("navbar", true, true);
echo $topMenu->toScript();

// Sidebar menu
$sideMenu = new Menu("menu", true, false);
$sideMenu->addMenuItem(40, "mci_Back_to_web_page", $MenuLanguage->MenuPhrase("40", "MenuText"), $MenuRelativePath . "../", -1, "", true, false, true, "fa-arrow-circle-left", "", false);
$sideMenu->addMenuItem(2, "mi_text", $MenuLanguage->MenuPhrase("2", "MenuText"), $MenuRelativePath . "TextList", -1, "", AllowListMenu('{inplaze}text'), false, false, "fa-font", "", false);
$sideMenu->addMenuItem(58, "mi_contents", $MenuLanguage->MenuPhrase("58", "MenuText"), $MenuRelativePath . "ContentsList", -1, "", AllowListMenu('{inplaze}contents'), false, false, "fa-align-left", "", false);
$sideMenu->addMenuItem(32, "mi_image", $MenuLanguage->MenuPhrase("32", "MenuText"), $MenuRelativePath . "ImageList", -1, "", AllowListMenu('{inplaze}image'), false, false, "fa-image", "", false);
$sideMenu->addMenuItem(59, "mi_blog", $MenuLanguage->MenuPhrase("59", "MenuText"), $MenuRelativePath . "BlogList", -1, "", AllowListMenu('{inplaze}blog'), false, false, "fa fa-newspaper", "", false);
$sideMenu->addMenuItem(56, "mi_product", $MenuLanguage->MenuPhrase("56", "MenuText"), $MenuRelativePath . "ProductList?cmd=resetall", -1, "", AllowListMenu('{inplaze}product'), false, false, "fa-shopping-cart", "", false);
$sideMenu->addMenuItem(51, "mci_Setting", $MenuLanguage->MenuPhrase("51", "MenuText"), "", -1, "", true, false, true, "fa-cog", "", false);
$sideMenu->addMenuItem(29, "mi_user", $MenuLanguage->MenuPhrase("29", "MenuText"), $MenuRelativePath . "UserList", 51, "", AllowListMenu('{inplaze}user'), false, false, "fa-user", "", false);
$sideMenu->addMenuItem(30, "mi_user_level2", $MenuLanguage->MenuPhrase("30", "MenuText"), $MenuRelativePath . "UserLevel2List", 51, "", AllowListMenu('{inplaze}user_level'), false, false, "fa-users", "", false);
$sideMenu->addMenuItem(57, "mi_category", $MenuLanguage->MenuPhrase("57", "MenuText"), $MenuRelativePath . "CategoryList", 51, "", AllowListMenu('{inplaze}category'), false, false, "fa-folder", "", false);
$sideMenu->addMenuItem(63, "mi_tag", $MenuLanguage->MenuPhrase("63", "MenuText"), $MenuRelativePath . "TagList", 51, "", AllowListMenu('{inplaze}tag'), false, false, "fa-tags", "", false);
echo $sideMenu->toScript();

<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global \CMain $APPLICATION */
/** @global \CUser $USER */
/** @global \CDatabase $DB */
/** @var \CBitrixComponent $this */
/** @var \CBitrixComponent $component */
/** @var string $epilogFile */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var array $templateData */

if (!empty($arResult["SLOGAN_HEAD"])) {
    $APPLICATION->SetPageProperty("slogan_head", $arResult["SLOGAN_HEAD"]);
}

if (!empty($arResult["DETAIL_PICTURE"])) {
    $css = "background-image:url(" . $arResult["DETAIL_PICTURE"] . "); background-size: contain";
    $APPLICATION->SetPageProperty("head_style", $css);
}
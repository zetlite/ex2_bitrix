<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global \CMain $APPLICATION */
/** @global \CUser $USER */
/** @global \CDatabase $DB */
/** @var CBitrixComponentTemplate $this */


$arResult["SPECIALDATE"] = $arResult["ITEMS"][0]["ACTIVE_FROM"] ?: '';

$this->__component->SetResultCacheKeys([
    "SPECIALDATE",
]);
<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global \CMain $APPLICATION */
/** @global \CUser $USER */
/** @global \CDatabase $DB */
/** @var CBitrixComponentTemplate $this */

if (!empty($arParams["IBLOCK_ID_CANONICAL"])) {
    $arSelect = ["ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM", "PROPERTY_LINK_NEWS"];
    $arFilter = [
        "IBLOCK_ID" => $arParams["IBLOCK_ID_CANONICAL"],
        "ACTIVE_DATE" => "Y",
        "ACTIVE" => "Y",
        "PROPERTY_LINK_NEWS" => $arResult["ID"]
    ];
    $res = CIBlockElement::GetList([], $arFilter, false, [], $arSelect);
    if ($item = $res->Fetch()) {
        $arResult["CANONICAL"] = $item["NAME"];
    }
}

$this->__component->SetResultCacheKeys([
   "CANONICAL"
]);
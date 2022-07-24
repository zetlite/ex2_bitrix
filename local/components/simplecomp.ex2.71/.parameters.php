<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arCurrentValues */

if (!CModule::IncludeModule("iblock")) {
    return;
}

use \Bitrix\Main\Localization\Loc;

$arComponentParameters = [
    "GROUPS" => [
    ],
    "PARAMETERS" => [
        "IBLOCK_ID_PRODUCT" => [
            "PARENT" => "BASE",
            "NAME" => Loc::getMessage("IBLOCK_ID_PRODUCT"),
            "TYPE" => "STRING",
        ],
        "IBLOCK_ID_CLASSIFICATOR" => [
            "PARENT" => "BASE",
            "NAME" => Loc::getMessage("IBLOCK_ID_CLASSIFICATOR"),
            "TYPE" => "STRING",
        ],
        "CODE_PROPERTY" => [
            "PARENT" => "BASE",
            "NAME" => Loc::getMessage("CODE_PROPERTY"),
            "TYPE" => "STRING",
        ],
        "TEMPLATE_URL_LINK" => [
            "PARENT" => "BASE",
            "NAME" => Loc::getMessage("TEMPLATE_URL_LINK"),
            "TYPE" => "STRING",
        ],
        "CACHE_TIME" => [
            "DEFAULT" => 36000000,
        ],
        "CACHE_GROUPS" => [
            "PARENT" => "CACHE_SETTINGS",
            "NAME" => Loc::getMessage("CP_BNL_CACHE_GROUPS"),
            "TYPE" => "CHECKBOX",
            "DEFAULT" => "Y",
        ],
        "ELEMENTS_PER_PAGE" => [
            "PARENT"  => "BASE",
            "NAME"    => Loc::getMessage("ELEMENTS_PER_PAGE"),
            "TYPE"    => "STRING",
            "DEFAULT" => "2",
        ],
    ],
];

CIBlockParameters::AddPagerSettings(
    $arComponentParameters,
    Loc::getMessage("TITLE_PAGINATION"), //$pager_title
    true, //$bDescNumbering
    true //$bShowAllParam
);
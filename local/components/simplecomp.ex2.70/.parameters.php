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
        "IBLOCK_ID_NEWS" => [
            "PARENT" => "BASE",
            "NAME" => Loc::getMessage("IBLOCK_ID_NEWS"),
            "TYPE" => "STRING",
        ],
        "CODE_PROPERTY" => [
            "PARENT" => "BASE",
            "NAME" => Loc::getMessage("CODE_PROPERTY"),
            "TYPE" => "STRING",
        ],
        "CACHE_TIME" => ["DEFAULT" => 36000000],
    ],
];

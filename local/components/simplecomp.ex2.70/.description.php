<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
use \Bitrix\Main\Localization\Loc;

$arComponentDescription = [
    "NAME" => Loc::getMessage("EX2_70_NAME"),
    "CACHE_PATH" => "Y",
    "PATH" => [
        "ID" => "simplecomp.exam",
        "NAME" => Loc::getMessage("EX2_70_TITLE"),
    ],
];

?>
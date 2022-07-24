<?php

namespace Ex2\Handlers;

use Ex2\Constants;
use Ex2\Element;
use \Bitrix\Main\Localization\Loc;

class Iblock
{
    const WORD_REPLACE = "калейдоскоп";

    public static function onBeforeIBlockElementUpdateHandler(&$arFields)
    {
        if ($arFields["IBLOCK_ID"] === Constants::CATALOG_IBLOCK_ID && $arFields["ACTIVE"] === "N") {
            $el = Element::checkProductDeactivate($arFields);
            if ($el["ERROR"]) {
                global $APPLICATION;
                $APPLICATION->throwException($el["ERROR"]);
                return false;
            }
        }

        if ($arFields["IBLOCK_ID"] === Constants::NEWS_IBLOCK_ID) {
            if (strpos($arFields["PREVIEW_TEXT"], self::WORD_REPLACE) !== false) {
                $arFields["PREVIEW_TEXT"] = str_replace(self::WORD_REPLACE, '[...]', $arFields["PREVIEW_TEXT"]);
                \CEventLog::Add([
                    "SEVERITY" => "INFO",
                    "AUDIT_TYPE_ID" => Loc::getMessage("REPLACE_WORD"),
                    "MODULE_ID" => "main",
                    "DESCRIPTION" => Loc::getMessage("REPLACE_WORD", ["#ID_NEWS#" => $arFields["ID"]]),
                ]);
            }
        }
    }

    public static function onBeforeIBlockElementAddHandler(&$arFields)
    {
        if ($arFields["IBLOCK_ID"] === Constants::NEWS_IBLOCK_ID) {
            if (strpos($arFields["PREVIEW_TEXT"], self::WORD_REPLACE) !== false) {
                global $APPLICATION;
                $APPLICATION->throwException(Loc::getMessage("CANT_ADD_HAVE_WORD"));
                return false;
            }
        }
    }
}


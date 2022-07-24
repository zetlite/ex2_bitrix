<?php

namespace Ex2\Handlers;

use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Loader;
use \Bitrix\Iblock\ElementTable;
use Ex2\Constants;

class Search
{
    public static function beforeIndexHandler($arFields)
    {
        $dbItems = ElementTable::getList([
            'select' => ['ID', 'IBLOCK_ID', "PREVIEW_TEXT"],
            'filter' => ['ID' => $arFields["ITEM_ID"]],
        ]);

        if ($res = $dbItems->fetch()) {
            if ($res["IBLOCK_ID"] == Constants::NEWS_IBLOCK_ID) {
                $arFields["TITLE"] = TruncateText($res["PREVIEW_TEXT"], 50);
            }
        }

        return $arFields;
    }
}
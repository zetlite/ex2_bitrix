<?php

namespace Ex2;

use \Bitrix\Iblock\ElementTable;
use \Bitrix\Main\Localization\Loc;

class Element
{
    const MIN_SHOW_COUNTER_DISABLE = 2;

    public static function checkProductDeactivate(&$arFields)
    {
        $filter = ['ID' => $arFields["ID"]];
        $select = ['SHOW_COUNTER'];
        $limit = 1;

        $item = self::getList($filter, $select, $limit);
        $shownCounters = $item["SHOW_COUNTER"];
        if ($shownCounters <= self::MIN_SHOW_COUNTER_DISABLE) {
            return ["ERROR" => Loc::getMessage("ERROR_CANT_DISABLE", ["#SHOWN_COUNTERS#" => $shownCounters])];
        }

        return true;
    }

    public static function getList($filter, $select = [], $limit = '')
    {
        $dbItem = ElementTable::getList([
            'select' => $select,
            'filter' => $filter,
            'limit' => $limit,
        ]);

        if ($item = $dbItem->fetch()) {
            return $item;
        }

        return false;
    }

    public static function getProperty($iblockID, $itemID)
    {
        $db_props = \CIBlockElement::GetProperty($iblockID, $itemID);
        $props = [];
        while($arProps = $db_props->Fetch()) {
            $props[] = $arProps;
        }

        return $props;
    }
}
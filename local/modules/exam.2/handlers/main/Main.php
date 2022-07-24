<?php

namespace Ex2\Handlers;

use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Loader;

use Ex2\Constants;
use Ex2\Element;
use Ex2\Helper;

class Main
{
    public static function onEpilogHandler()
    {
        global $APPLICATION;

        if (defined("ERROR_404") && ERROR_404 === "Y") {
            \CEventLog::Add([
                "SEVERITY" => "INFO",
                "AUDIT_TYPE_ID" => "ERROR_404",
                "MODULE_ID" => "main",
                "DESCRIPTION" => $APPLICATION->GetCurUri(),
            ]);
        }
    }

    public static function onBeforeEventAddHandler(&$event, &$lid, &$arFields)
    {
        if ($event === "FEEDBACK_FORM") {
            global $USER;

            if ($USER->IsAuthorized()) {
                $userID = $USER->GetID();
                $userLogin = $USER->GetLogin();
                $userFullName = $USER->GetFullName();
                $text = Loc::getMessage("AUTHOR_AUTH", [
                    "#AUTHOR#" => $arFields["AUTHOR"],
                    "#ID#" => $userID,
                    "#LOGIN#" => $userLogin,
                    "#FULL_NAME#" => $userFullName,
                ]);
            } else {
                $text = Loc::getMessage("AUTHOR_NOT_AUTH", ["#AUTHOR#" => $arFields["AUTHOR"]]);
            }

            $arFields["AUTHOR"] = $text;

            \CEventLog::Add([
                "SEVERITY" => "INFO",
                "AUDIT_TYPE_ID" => Loc::getMessage("REPLACE"),
                "MODULE_ID" => "main",
                "DESCRIPTION" => Loc::getMessage("REPLACE_DESCRIPTION", ["#AUTHOR#" => $arFields["AUTHOR"]]),
            ]);
        }
    }

    public static function onBuildGlobalMenuHandler(&$aGlobalMenu, &$aModuleMenu)
    {
        global $USER;
        if (!$USER->IsAdmin() && in_array(Constants::USER_GROUP_CONTENT, $USER->GetUserGroupArray())) {
            foreach ($aGlobalMenu as $k => $menu) {
                if ($k !== "global_menu_content") {
                    unset($aGlobalMenu[$k]);
                }
            }

            foreach ($aModuleMenu as $k => $menu) {
                if ($menu["parent_menu"] !== "global_menu_content") {
                    unset($aModuleMenu[$k]);
                }
            }
        }
    }

    public static function onBeforePrologHandler()
    {
        if (Loader::includeModule('iblock')) {
            global $APPLICATION;

            $filter = [
                "IBLOCK_ID" => Constants::METATEG_IBLOCK_ID,
                "ACTIVE" => "Y",
                "NAME" => $APPLICATION->GetCurPage(),
            ];

            $select = ['NAME', 'ID', 'IBLOCK_ID'];
            $limit = 1;

            $item = Element::getList($filter, $select, $limit);

            if ($item) {
                $props = Element::getProperty($item["IBLOCK_ID"], $item["ID"]);
                if (!empty($props)) {
                    $titleK = Helper::findKey("TITLE", $props, "CODE");
                    $descriptionK = Helper::findKey("DESCRIPTION", $props, "CODE");

                    if ($props[$titleK]) {
                        $APPLICATION->SetPageProperty('title', $props[$titleK]["VALUE"]);
                    }

                    if ($props[$descriptionK]) {
                        $APPLICATION->SetPageProperty('description', $props[$descriptionK]["VALUE"]);
                    }
                }
            }
        }
    }
}
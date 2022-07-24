<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use \Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\UserTable;

class SimpleComp extends \CBitrixComponent
{

    var $curUserID;
    var $iCurUserType;
    var $users;
    var $userIDS = [];
    var $countNews = [];

    public function __construct($component = NULL)
    {
        global $APPLICATION, $USER;

        $this->globals["APPLICATION"] = $APPLICATION;
        $this->globals["USER"] = $USER;

        $this->curUserID = $USER->GetID();

        parent::__construct($component);
    }

    public function onPrepareComponentParams($arParams)
    {
        return parent::onPrepareComponentParams($arParams);
    }

    protected function checkModules()
    {
        return !Loader::includeModule('iblock') ? false : true;
    }

    public function getUserInfo()
    {
        $this->iCurUserType = \CUser::GetList(
            ($by = "id"),
            ($order = "asc"),
            ["ID" => $this->curUserID],
            ["SELECT" => [$this->arParams["UF_CODE_PROPERTY"]]]
        )->Fetch()[$this->arParams["UF_CODE_PROPERTY"]];
    }

    public function getUsers()
    {
        $filter = [
            "!ID" => $this->curUserID,
            $this->arParams["UF_CODE_PROPERTY"] => $this->iCurUserType,
        ];

        $select = [
            "ID",
            "NAME",
            "LOGIN",
        ];

        $result = UserTable::getList([
            'select' => $select,
            'filter' => $filter,
        ]);

        while ($arUser = $result->fetch()) {
            $this->users[$arUser["ID"]] = $arUser;
        }

        $this->userIDS = array_column($this->users, "ID");
    }

    public function collectResult()
    {
        if ($this->userIDS) {
            $filter = [
                "IBLOCK_ID" => $this->arParams["IBLOCK_ID_NEWS"],
                "!PROPERTY_" . $this->arParams["CODE_PROPERTY"] => $this->curUserID,
                "PROPERTY_" . $this->arParams["CODE_PROPERTY"] => $this->userIDS,
            ];

            $select = [
                "NAME",
                "ACTIVE_FROM",
                "ID",
                "IBLOCK_ID",
                "PROPERTY_" . $this->arParams["CODE_PROPERTY"],
            ];

            $resNews = \CIBlockElement::GetList(
                [],
                $filter,
                false,
                false,
                $select
            );

            $value = 'PROPERTY_' . $this->arParams["CODE_PROPERTY"] . '_VALUE';
            while ($arEl = $resNews->Fetch()) {
                $this->users[$arEl[$value]]["ITEMS"][] = $arEl;
                $this->countNews[] = $arEl["ID"];
            }

            $this->arResult["RESULT"] = $this->users;
        }
    }

    protected function setCacheKey()
    {
        $this->arResult["COUNT_UNIQ_NEWS"] = count(array_unique($this->countNews));

        $this->setResultCacheKeys([
            "COUNT_UNIQ_NEWS",
        ]);
    }

    protected function setMeta()
    {
        $this->globals["APPLICATION"]->SetTitle(Loc::getMessage("TITLE", ["#COUNT#" => $this->arResult["COUNT_UNIQ_NEWS"]]));
    }

    public function executeComponent()
    {

        if (!$this->checkModules()) {
            ShowError(Loc::getMessage("IBLOCK_MODULE_NOT_INSTALLED"));
            return false;
        }

        if ($this->globals["USER"]->IsAuthorized() === false) {
            return false;
        }

        $this->getUserInfo();

        if ($this->startResultCache(false, $this->iCurUserType) && !empty($this->iCurUserType)) {
            $this->getUsers();
            $this->collectResult();
            $this->setCacheKey();
            $this->includeComponentTemplate();
        }

        $this->setMeta();
    }
}
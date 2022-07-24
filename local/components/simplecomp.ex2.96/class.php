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
    var $users = [];
    var $favUsers = [];

    var $deleteArray = [];
    var $result = [];
    var $countFav = 0;
    var $usersIDS = [];

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

    public function getItemsCurUser()
    {
        $filter = [
            "IBLOCK_ID" => $this->arParams["IBLOCK_ID_PRODUCT"],
            "=PROPERTY_" . $this->arParams["CODE_PROPERTY"] => $this->curUserID,
        ];

        $select = [
            "NAME",
            "ID",
            "IBLOCK_ID",
        ];

        $resProduct = \CIBlockElement::GetList(
            [],
            $filter,
            false,
            false,
            $select
        );

        while ($ob = $resProduct->GetNextElement()) {
            $arFields = $ob->GetFields();
            $arProps = $ob->GetProperties();
            $this->result["RESULT"][] = array_merge($arFields, ["PROPERTIES" => $arProps]);
            $this->favUsers[] = $arProps[$this->arParams["CODE_PROPERTY"]]["VALUE"];
            $this->deleteArray[] = $arFields["ID"];
            $this->countFav++;
        }

    }

    public function mayLikeProduct()
    {
        $this->favUsers = $this->getSimpleArray($this->favUsers);
        $this->favUsers = array_diff($this->favUsers, [$this->curUserID]);


        $filter = [
            "IBLOCK_ID" => $this->arParams["IBLOCK_ID_PRODUCT"],
            "=PROPERTY_" . $this->arParams["CODE_PROPERTY"] => $this->favUsers,

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


        while ($ob = $resNews->GetNextElement()) {
            $arFields = $ob->GetFields();
            $arProps = $ob->GetProperties();
            if (!in_array($arFields["ID"], $this->deleteArray)) {
                $this->result["MAY_LIKE"][] = array_merge($arFields, ["PROPERTIES" => $arProps]);
                $this->usersIDS[] = $arProps[$this->arParams["CODE_PROPERTY"]]["VALUE"];
            }
        }
    }

    protected function getSimpleArray($array)
    {
        array_walk_recursive($array, function ($item, $key) use (&$result) {
            $result[] = $item;
        });

        return array_unique($result);
    }

    public function getUsers()
    {
        $this->usersIDS = $this->getSimpleArray($this->usersIDS);

        $filter = [
            "ID" => $this->usersIDS,
        ];

        $select = [
            "ID",
            "LOGIN",
        ];

        $result = UserTable::getList([
            'select' => $select,
            'filter' => $filter,
        ]);

        while ($arUser = $result->fetch()) {
            $this->result["USERS"][$arUser["ID"]] = $arUser["LOGIN"];
        }
    }

    public function collectResult()
    {
        $this->arResult = $this->result;
    }

    protected function setCacheKey()
    {
        $this->arResult["IN_FAV_GOODS"] =  $this->countFav;

        $this->setResultCacheKeys([
            "IN_FAV_GOODS",
        ]);
    }

    protected function setButtons()
    {
        $this->AddIncludeAreaIcon(
            [
                'URL' => $this->globals["APPLICATION"]->GetCurPage() . '?hello=world',
                'TITLE' => Loc::getMessage("HELLO_WORLD"),
                'IN_PARAMS_MENU' => true,
            ]
        );
    }

    protected function setMeta()
    {
        $this->globals["APPLICATION"]->SetTitle(Loc::getMessage("TITLE", ["#COUNT#" => $this->arResult["IN_FAV_GOODS"]]));
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

        if ($this->startResultCache(false, [$this->curUserID])) {
            $this->getItemsCurUser();
            $this->mayLikeProduct();
            $this->getUsers();
            $this->collectResult();
            $this->setButtons();
            $this->setCacheKey();
            $this->includeComponentTemplate();
        }

        $this->setMeta();
    }
}
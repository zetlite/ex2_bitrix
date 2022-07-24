<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;

class SimpleComp extends \CBitrixComponent
{
    var $product = [];
    var $idsSection = [];
    var $servicesSection = [];
    var $arNavParams = [];
    var $arNavigation;

    public function __construct($component = NULL)
    {
        global $APPLICATION, $USER, $CACHE_MANAGER;

        $this->globals["APPLICATION"] = $APPLICATION;
        $this->globals["USER"] = $USER;
        $this->globals["CACHE_MANAGER"] = $CACHE_MANAGER;
        $this->_GET = $_REQUEST;

        parent::__construct($component);
    }

    public function onPrepareComponentParams($params)
    {
        return $params;
    }

    protected function checkModules()
    {
        return !Loader::includeModule('iblock') ? false : true;
    }

    protected function getSimpleArray($array)
    {
        array_walk_recursive($array, function ($item, $key) use (&$result) {
            $result[] = $item;
        });

        return array_unique($result);
    }

    public function getProduct()
    {
        $filter = [
            "IBLOCK_ID" => $this->arParams['IBLOCK_ID_PRODUCT'],
            "ACTIVE" => "Y",
            "!PROPERTY_" . $this->arParams["CODE_PROPERTY_CLASSIFICATOR"] => false,
        ];

        $select = [
            "ID", "IBLOCK_ID", "NAME",
        ];

        $rsEl = CIBlockElement::GetList([], $filter, false, [], $select);


        while ($ob = $rsEl->GetNextElement()) {
            $arFields = $ob->GetFields();
            $arProps = $ob->GetProperties();
            $this->idsSection[] = $arProps[$this->arParams["CODE_PROPERTY_CLASSIFICATOR"]]["VALUE"];
            $this->products[] = array_merge($arFields, ["PROPERTIES" => $arProps]);
        }
    }

    public function getSectionServices()
    {
        $this->idsSection = $this->getSimpleArray($this->idsSection);

        $filter = [
            'IBLOCK_ID' => $this->arParams['IBLOCK_ID_SERVICES'],
            'ID' => $this->idsSection,
            "GLOBAL_ACTIVE" => "Y",
            "ACTIVE" => "Y",
        ];

        $select = [
            "ID", "NAME",
        ];

        $rsSect = CIBlockSection::GetList([], $filter, false, $select, $this->arNavParams);
        while ($arSect = $rsSect->Fetch()) {
            $this->servicesSection[] = $arSect;
        }

        $this->arResult["NAV_STRING"] = $rsSect->GetPageNavStringEx(
            $navComponentObject,
            $this->arParams["PAGER_TITLE"],
            $this->arParams["PAGER_TEMPLATE"],
            $this->arParams["PAGER_SHOW_ALWAYS"]
        );
    }

    public function collectResult()
    {

        foreach ($this->servicesSection as &$section) {
            foreach ($this->products as $product) {
                if (in_array($section["ID"], $product["PROPERTIES"][$this->arParams["CODE_PROPERTY_CLASSIFICATOR"]]["VALUE"])) {
                    $section["ITEMS"][] = $product;
                }

            }
        }

        $this->arResult["RESULT"] = $this->servicesSection;
    }

    protected function setCacheKey()
    {
        $this->arResult["COUNT_PRODUCT"] = count($this->products);

        $this->setResultCacheKeys([
            "COUNT_PRODUCT",
        ]);
    }

    protected function setMeta()
    {
        $this->globals["APPLICATION"]->SetTitle(Loc::getMessage("COUNT_PRODUCT", ["#COUNT#" => $this->arResult["COUNT_PRODUCT"]]));
    }

    public function setPagination()
    {
        $this->arNavParams = [
            "nPageSize" => $this->arParams["NEWS_COUNT"],
            "bShowAll" => $this->arParams["COUNT_ELEMENT_ON_PAGE"],
        ];
        $this->arNavigation = CDBResult::GetNavParams($this->arNavParams);
    }

    public function executeComponent()
    {
        if (!$this->checkModules()) {
            ShowError(Loc::getMessage("IBLOCK_MODULE_NOT_INSTALLED"));
            return false;
        }
        $this->setPagination();

        if ($this->startResultCache(false, [$this->arNavigation])) {
            $this->getProduct();

            $this->getSectionServices();
            $this->collectResult();
            $this->setCacheKey();

            $this->includeComponentTemplate();
        }
        $this->setMeta();
    }
}
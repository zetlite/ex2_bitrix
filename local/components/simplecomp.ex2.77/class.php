<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use \Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;

class SimpleComponent extends \CBitrixComponent
{
    var $sections = [];
    var $sectionsClassificator = [];
    var $idsClassificatorSection = [];
    var $idsSection = [];
    var $globals;
    var $countClassificator = 0;

    public function __construct($component = null)
    {
        global $APPLICATION;

        $this->globals["APPLICATION"] = $APPLICATION;

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

    public function getSectionProduct()
    {
        $filter = [
            'IBLOCK_ID' => $this->arParams['IBLOCK_ID_PRODUCT'],
            "GLOBAL_ACTIVE" => "Y",
            "ACTIVE" => "Y",
            "!" . $this->arParams['CODE_PROPERTY_CLASSIFICATOR'] => false,
        ];

        $select = [
            "ID", "NAME", $this->arParams['CODE_PROPERTY_CLASSIFICATOR'],
        ];

        $rsSect = CIBlockSection::GetList([], $filter, false, $select);
        while ($arSect = $rsSect->Fetch()) {
            $this->sections[] = $arSect;
            $this->idsSection[] = $arSect["ID"];
            if (!in_array($arSect[$this->arParams['CODE_PROPERTY_CLASSIFICATOR']], $this->idsSection)){
                $this->idsClassificatorSection[] = $arSect[$this->arParams['CODE_PROPERTY_CLASSIFICATOR']];
            }
        }
    }

    public function getSectionClassificator()
    {
        $filter = [
            'IBLOCK_ID' => $this->arParams['IBLOCK_ID_CLASSIFICATOR'],
            "GLOBAL_ACTIVE" => "Y",
            "ACTIVE" => "Y",
            "IBLOCK_SECTION_ID" => $this->idsClassificatorSection,
        ];

        $select = [
            "ID", "NAME",
        ];

        $rsSect = CIBlockSection::GetList([], $filter, false, $select);
        while ($arSect = $rsSect->Fetch()) {
            $this->sectionsClassificator[] = $arSect;
            $this->countClassificator++;
        }
    }
    
    public function getProducts()
    {
        $filter = [
            "IBLOCK_ID" => $this->arParams['IBLOCK_ID_PRODUCT'],
            "IBLOCK_SECTION_ID" => $this->idsSection,
            "GLOBAL_ACTIVE" => "Y",
            "ACTIVE" => "Y",
        ];

        $select = [
            "ID", "NAME", "PROPERTY_PRICE", "PROPERTY_MATERIAL", "PROPERTY_ARTNUMBER", "IBLOCK_SECTION_ID", "DETAIL_PAGE_URL"
        ];

        $rsEl = CIBlockElement::GetList([], $filter, false, [], $select);
        while ($arEl = $rsEl->GetNext()) {
            $k = array_search($arEl["IBLOCK_SECTION_ID"], array_column($this->sections, "ID"));
            if ($this->sections[$k]) {
                $this->sections[$k]["ITEMS"][] = $arEl;
            }
        }
    }

    public function collectResult()
    {

        foreach ($this->sectionsClassificator as &$classificator) {
            foreach ($this->sections as $section) {
                if ($classificator["ID"] == $section[$this->arParams['CODE_PROPERTY_CLASSIFICATOR']]) {
                    $classificator["SECTIONS"][] = $section;
                }
            }
        }

        $this->arResult["RESULT"] = $this->sectionsClassificator;
    }

    public function setMeta()
    {

        $this->globals["APPLICATION"]->SetTitle(Loc::getMessage("COUNT_CATALOG", ["#COUNT#" => $this->arResult["COUNT_CLASSIFICATOR"]]));
    }

    public function setCacheKey()
    {
        $this->arResult["COUNT_CLASSIFICATOR"] = $this->countClassificator;

        $this->setResultCacheKeys([
            "COUNT_CLASSIFICATOR"
        ]);
    }

    public function executeComponent()
    {
        if (!$this->checkModules()) {
            ShowError(Loc::getMessage("IBLOCK_MODULE_NOT_INSTALLED"));
            return false;
        }

        if ($this->startResultCache(false, [])) {
            $this->getSectionProduct();
            $this->getProducts();
            $this->getSectionClassificator();
            $this->collectResult();
            $this->setCacheKey();
            $this->includeComponentTemplate();
        }

        $this->setMeta();
    }
}
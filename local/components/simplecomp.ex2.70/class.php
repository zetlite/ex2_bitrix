<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;

class Simplecomp extends \CBitrixComponent
{
    var $sections = [];
    var $news = [];
    var $countProduct = 0;
    var $globals = [];

    public function __construct($component = null)
    {
        global $APPLICATION;

        $this->globals["APPLICATION"] = $APPLICATION;

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

    public function getSection()
    {
        $filter = [
            'IBLOCK_ID' => $this->arParams['IBLOCK_ID_PRODUCT'],
            "GLOBAL_ACTIVE" => "Y",
            "ACTIVE" => "Y",
            "!" . $this->arParams['CODE_PROPERTY'] => false,
        ];

        $select = [
            "ID", "NAME", $this->arParams['CODE_PROPERTY'],
        ];

        $rsSect = CIBlockSection::GetList([], $filter, false, $select);
        while ($arSect = $rsSect->Fetch()) {
            $this->sections[] = $arSect;
        }
    }

    public function getProducts()
    {
        $sectionsIDS = array_column($this->sections, "ID");
        $filter = [
            "IBLOCK_ID" => $this->arParams['IBLOCK_ID_PRODUCT'],
            "GLOBAL_ACTIVE" => "Y",
            "ACTIVE" => "Y",
            "IBLOCK_SECTION_ID" => $sectionsIDS,
        ];

        $select = [
            "ID", "NAME", "PROPERTY_PRICE", "PROPERTY_ARTNUMBER", "PROPERTY_MATERIAL", "IBLOCK_SECTION_ID",
        ];

        $rsEl = CIBlockElement::GetList([], $filter, false, [], $select);

        while ($arEl = $rsEl->Fetch()) {
            $k = array_search($arEl["IBLOCK_SECTION_ID"], $sectionsIDS);
            if ($k !== false) {
                $this->sections[$k]["ITEMS"][] = $arEl;
            }
            $this->countProduct++;
        }
    }

    public function getNews()
    {
        $idsNews = $this->returnIdNews();
        $filter = [
            "IBLOCK_ID" => $this->arParams['IBLOCK_ID_NEWS'],
            "GLOBAL_ACTIVE" => "Y",
            "ACTIVE" => "Y",
            "ID" => $idsNews,
        ];

        $select = [
            "ID", "ACTIVE_FROM", "NAME",
        ];

        $rsEl = CIBlockElement::GetList([], $filter, false, [], $select);
        while ($arEl = $rsEl->Fetch()) {
            $this->news[] = $arEl;
        }
    }

    public function collectResult()
    {
        foreach ($this->news as &$new) {
            foreach ($this->sections as $section) {
                if (in_array($new["ID"], $section[$this->arParams['CODE_PROPERTY']])) {
                    $new["SECTIONS"][] = $section;
                }
            }
        }

        $this->arResult["RESULT"] = $this->news;
    }

    protected function returnIdNews()
    {
        $elems = array_column($this->sections, $this->arParams['CODE_PROPERTY']);
        array_walk_recursive($elems, function ($item, $key) use (&$result) {
            $result[] = $item;
        });

        return array_unique($result);
    }

    protected function setCacheKey()
    {
        $this->arResult["COUNT_PRODUCT"] = $this->countProduct;

        $this->setResultCacheKeys([
            "COUNT_PRODUCT",
        ]);
    }

    protected function setMeta()
    {
        $this->globals["APPLICATION"]->SetTitle(Loc::getMessage("TITLE", ["#COUNT_EL#" => $this->arResult["COUNT_PRODUCT"]]));
    }

    public function executeComponent()
    {
        if (!$this->checkModules()) {
            ShowError(Loc::getMessage("IBLOCK_MODULE_NOT_INSTALLED"));
            return false;
        }

        if ($this->startResultCache(false, []) ) {
            $this->getSection();
            $this->getProducts();
            $this->getNews();
            $this->collectResult();
            $this->setCacheKey();
            $this->includeComponentTemplate();
        }

        $this->setMeta();
    }
}
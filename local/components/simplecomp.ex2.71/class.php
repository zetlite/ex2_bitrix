<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;
use ex2\Constants;

class Simplecomp extends \CBitrixComponent
{
    var $products = [];
    var $firms = [];
    var $idsFirms = [];
    var $_GET;
    var $filter = [];
    var $filterPrice1 = 1700;
    var $filterPrice2 = 1500;
    var $filterMaterial1 = "Дерево, ткань";
    var $filterMaterial2 = "Металл, пластик";
    var $abortCache = false;
    var $maxPrice;
    var $minPrice;
    var $arNavParams;
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

    public function onPrepareComponentParams($arParams)
    {
        return parent::onPrepareComponentParams($arParams);
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
        $sort = [
            "NAME" => "ASC",
            "SORT" => "ASC",
        ];

        $filter = [
            "IBLOCK_ID" => $this->arParams['IBLOCK_ID_PRODUCT'],
            "CHECK_PERMISSIONS" => $this->arParams["CACHE_GROUPS"],
            "ACTIVE" => "Y",
            "!PROPERTY_" . $this->arParams["CODE_PROPERTY"] => false,
        ];

        $filter = array_merge($filter, $this->filter);

        $select = [
            "ID", "IBLOCK_ID", "NAME", "DETAIL_PAGE_URL",
        ];

        $rsEl = CIBlockElement::GetList($sort, $filter, false, [], $select);
        if ($url = $this->arParams["TEMPLATE_URL_LINK"]) {
            $rsEl->SetUrlTemplates($url);
        }

        $firmsIDS = [];
        $prices = [];
        while ($ob = $rsEl->GetNextElement()) {
            $arFields = $ob->GetFields();
            $arProps = $ob->GetProperties();

            $arButtons = CIBlock::GetPanelButtons(
                $arFields["IBLOCK_ID"],
                $arFields["ID"],
                0,
                ["SECTION_BUTTONS" => false,]
            );
            $arFields["EDIT_LINK"] = $arButtons["edit"]["edit_element"]["ACTION_URL"];
            $arFields["DELETE_LINK"] = $arButtons["edit"]["delete_element"]["ACTION_URL"];


            $prices[] = $arProps["PRICE"]["VALUE"];
            $firmsIDS[] = $arProps[$this->arParams["CODE_PROPERTY"]]["VALUE"];

            $this->products[] = array_merge($arFields, ["PROPERTIES" => $arProps]);
        }

        $this->maxPrice = max($prices);
        $this->minPrice = min($prices);
        $this->idsFirms = $this->getSimpleArray($firmsIDS);
    }

    public function getFirms()
    {
        $filter = [
            "IBLOCK_ID" => $this->arParams['IBLOCK_ID_CLASSIFICATOR'],
            "CHECK_PERMISSIONS" => $this->arParams["CACHE_GROUPS"],
            "ACTIVE" => "Y",
            "ID" => $this->idsFirms,
        ];

        $select = [
            "ID", "NAME",
        ];

        $rsEl = CIBlockElement::GetList([], $filter, false, $this->arNavParams, $select);

        while ($arEl = $rsEl->Fetch()) {
            $this->firms[] = $arEl;
        }

        $this->arResult["NAV_STRING"] = $rsEl->GetPageNavStringEx(
            $navComponentObject,
            $this->arParams["PAGER_TITLE"],
            $this->arParams["PAGER_TEMPLATE"],
            $this->arParams["PAGER_SHOW_ALWAYS"]
        );
    }

    public function collectResult()
    {
        foreach ($this->firms as &$firm) {
            foreach ($this->products as $product) {
                if (in_array($firm["ID"], $product["PROPERTIES"][$this->arParams["CODE_PROPERTY"]]["VALUE"])) {
                    $firm["ITEMS"][] = $product;
                }
            }
        }
        $this->arResult["RESULT"] = $this->firms;
    }

    protected function setCacheKey()
    {
        $this->arResult["COUNT_FIRMS"] = count($this->idsFirms);
        $this->arResult["MAX_PRICE"] = $this->maxPrice;
        $this->arResult["MIN_PRICE"] = $this->minPrice;

        $this->setResultCacheKeys([
            "COUNT_FIRMS",
            "MAX_PRICE",
            "MIN_PRICE",
        ]);
    }

    protected function setMeta()
    {
        $this->globals["APPLICATION"]->SetTitle(Loc::getMessage("TITLE", ["#COUNT#" => $this->arResult["COUNT_FIRMS"]]));
    }

    protected function request()
    {
        if (!empty($this->_GET["F"])) {
            $this->filter = [
                [
                    "LOGIC" => "OR",
                    [
                        "<=PROPERTY_PRICE" => $this->filterPrice1,
                        "PROPERTY_MATERIAL" => $this->filterMaterial1,
                    ],
                    [
                        "<=PROPERTY_PRICE" => $this->filterPrice2,
                        "PROPERTY_MATERIAL" => $this->filterMaterial2,
                    ],
                ],
            ];
            $this->abortCache = true;
        }
    }

    protected function setButtons()
    {
        $arButtons = CIBlock::GetPanelButtons($this->arParams["IBLOCK_ID_PRODUCT"]);
        $this->AddIncludeAreaIcon(
            [
                'URL' => $arButtons['submenu']['element_list']['ACTION_URL'],
                'TITLE' => Loc::getMessage("IBLOCK_IN_ADMIN"),
                'IN_PARAMS_MENU' => true,
            ]
        );
    }

    public function setViewContent()
    {
        $this->globals["APPLICATION"]->AddViewContent(
            "price", Loc::getMessage(
            "PRICE", [
                "#MAX_PRICE#" => $this->arResult["MAX_PRICE"],
                "#MIN_PRICE#" => $this->arResult["MIN_PRICE"],
            ]
        )
        );
    }

    protected function setPagination()
    {
        $this->arNavParams = [
            "nPageSize" => $this->arParams["ELEMENTS_PER_PAGE"],
            "bShowAll" => $this->arParams["PAGER_SHOW_ALL"],
        ];

        $this->arNavigation = CDBResult::GetNavParams($this->arNavParams);
    }

    protected function clearTagCache()
    {
        if (defined('BX_COMP_MANAGED_CACHE')) {
            $this->globals["CACHE_MANAGER"]->RegisterTag("iblock_id_" . Constants::METATEG_IBLOCK_ID);
        }
    }

    public function executeComponent()
    {
        if (!$this->checkModules()) {
            ShowError(Loc::getMessage("IBLOCK_MODULE_NOT_INSTALLED"));
            return false;
        }

        $this->request();
        $this->setPagination();

        if ($this->startResultCache(false, [($this->arParams["CACHE_GROUPS"] === "N" ? false : $this->globals["USER"]->GetGroups()), $this->filter, $this->arNavigation])) {
            if ($this->abortCache) {
                $this->AbortResultCache();
            }
            $this->clearTagCache();
            $this->setButtons();
            $this->getProduct();
            $this->getFirms();
            $this->collectResult();
            $this->setCacheKey();
            $this->includeComponentTemplate();
        }

        $this->setMeta();
        $this->setViewContent();
    }
}
<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("ex2-71");
?><?$APPLICATION->IncludeComponent(
	"simplecomp.ex2.71", 
	".default", 
	array(
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"CODE_PROPERTY" => "FIRMS",
		"IBLOCK_ID_NEWS" => "7",
		"IBLOCK_ID_PRODUCT" => "2",
		"TEMPLATE_URL_LINK" => "catalog_exam/#SECTION_ID#/#ELEMENT_CODE#",
		"COMPONENT_TEMPLATE" => ".default",
		"IBLOCK_ID_CLASSIFICATOR" => "7",
		"ELEMENTS_PER_PAGE" => "2",
		"PAGER_TEMPLATE" => ".default",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"PAGER_TITLE" => "Странички",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N"
	),
	false
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
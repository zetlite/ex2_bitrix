<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("ex2-70");
?><?$APPLICATION->IncludeComponent(
	"simplecomp.ex2.70",
	"",
	Array(
		"CACHE_FILTER" => "N",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"CODE_PROPERTY" => "UF_NEWS_LINK",
		"IBLOCK_ID_NEWS" => "1",
		"IBLOCK_ID_PRODUCT" => "2"
	)
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
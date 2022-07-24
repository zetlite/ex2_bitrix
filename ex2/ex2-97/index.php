<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("ex2-97");
?><?$APPLICATION->IncludeComponent(
	"simplecomp.ex2.97",
	"",
	Array(
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"CODE_PROPERTY" => "AUTHORS",
		"IBLOCK_ID_NEWS" => "1",
		"UF_CODE_PROPERTY" => "UF_AUTHOR_TYPE"
	)
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
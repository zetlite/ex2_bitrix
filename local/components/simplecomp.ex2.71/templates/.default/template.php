<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global \CMain $APPLICATION */
/** @global \CUser $USER */
/** @global \CDatabase $DB */
/** @var \CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var array $templateData */

/** @var \CBitrixComponent $component */

use \Bitrix\Main\Localization\Loc;
$filterUrl = $APPLICATION->GetCurPage() . "?F=Y";
$this->setFrameMode(true);
if ($arResult["RESULT"]) { ?>
    <div><?= time()?></div>
    <h2>
        <?= Loc::getMessage("FILTER_F") ?>
        <a href="<?= $filterUrl?>"><?= $filterUrl?></a>
    </h2>
    <h2><?= Loc::getMessage("TEMPLATE_TITLE") ?></h2>
    <ul>
        <? foreach ($arResult["RESULT"] as $firm) { ?>
            <li>
                <b><?= $firm["NAME"] ?></b>
                <ul>
                    <? foreach ($firm["ITEMS"] as $item) {
                        $id = $firm["ID"] . $item['ID'];
                        $this->AddEditAction($id, $item['EDIT_LINK'], CIBlock::GetArrayByID($item["IBLOCK_ID"], "ELEMENT_EDIT"));
                        $this->AddDeleteAction($id, $item['DELETE_LINK'], CIBlock::GetArrayByID($item["IBLOCK_ID"], "ELEMENT_DELETE"), ["CONFIRM" => Loc::getMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')]);

                        ?>
                        <li id="<?=$this->GetEditAreaId($id);?>">
                            <?= $item["NAME"] ?>
                            - <?= $item["PROPERTIES"]["PRICE"]["VALUE"] ?>
                            - <?= $item["PROPERTIES"]["MATERIAL"]["VALUE"] ?>
                            - <?= $item["PROPERTIES"]["ARTNUMBER"]["VALUE"] ?>
                            - <a href="<?= $item["DETAIL_PAGE_URL"] ?>"><?= $item["DETAIL_PAGE_URL"] ?></a>
                        </li>
                        <?
                    }
                    ?>
                </ul>
            </li>
        <? } ?>
    </ul>
    <br>
    <?=$arResult["NAV_STRING"]?>
    <?
}
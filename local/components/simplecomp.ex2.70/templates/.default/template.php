<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */

/** @var CBitrixComponent $component */

use \Bitrix\Main\Localization\Loc;

$this->setFrameMode(true);


if ($arResult["RESULT"]) { ?>
    <h2><?= Loc::getMessage("TEMPLATE_TITLE") ?></h2>
    <ul>
        <? foreach ($arResult["RESULT"] as $new) { ?>
            <li>
                <b><?= $new["NAME"] ?></b>
                - <?= $new["ACTIVE_FROM"] ?>
                (<?= implode(',', array_column($new["SECTIONS"], "NAME")) ?>)
                <ul>
                    <? foreach ($new["SECTIONS"] as $section) {
                        foreach ($section["ITEMS"] as $item) {
                            ?>
                            <li>
                                <?= $item["NAME"] ?>
                                 - <?= $item["PROPERTY_PRICE_VALUE"] ?>
                                 - <?= $item["PROPERTY_MATERIAL_VALUE"] ?>
                                 - <?= $item["PROPERTY_ARTNUMBER_VALUE"] ?>
                            </li>
                            <?
                        }
                    } ?>
                </ul>
            </li>
        <? } ?>
    </ul>
    <?
}


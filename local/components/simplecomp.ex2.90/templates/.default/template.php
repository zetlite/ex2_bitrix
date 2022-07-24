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
        <? foreach ($arResult["RESULT"] as $classificator) { ?>
            <li>
                <b><?= $classificator["NAME"] ?></b>
                <ul>
                    <? foreach ($classificator["ITEMS"] as $item) {
                        ?>
                        <li>
                            <?= $item["NAME"] ?>
                            - <?= $item["PROPERTIES"]["PRICE"]["VALUE"] ?>
                            - <?= $item["PROPERTIES"]["MATERIAL"]["VALUE"] ?>
                            - <?= $item["PROPERTIES"]["ARTNUMBER"]["VALUE"] ?>
                        </li>
                        <?

                    } ?>
                </ul>
            </li>
        <? } ?>
    </ul>

    <?=$arResult["NAV_STRING"]?>
    <?
}


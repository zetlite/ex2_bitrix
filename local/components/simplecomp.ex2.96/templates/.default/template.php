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

$this->setFrameMode(true);

if (!empty($arResult["RESULT"])) { ?>
    <h2><?= Loc::getMessage("TEMPLATE_TITLE") ?></h2>
    <ul>
        <? foreach ($arResult["RESULT"] as $item) { ?>
            <li>
                <?= $item["NAME"] ?>
                - <?= $item["PROPERTIES"]["PRICE"]["VALUE"] ?>
                - <?= $item["PROPERTIES"]["MATERIAL"]["VALUE"] ?>
                - <?= $item["PROPERTIES"]["ARTNUMBER"]["VALUE"] ?>
            </li>
        <? } ?>
    </ul>
    <h2><?= Loc::getMessage("TEMPLATE_TITLE2") ?></h2>
    <ul>
        <? foreach ($arResult["MAY_LIKE"] as $like) {?>
            <li>
                <?= $like["NAME"] ?>
                - <?= $like["PROPERTIES"]["PRICE"]["VALUE"] ?>
                - <?= $like["PROPERTIES"]["MATERIAL"]["VALUE"] ?>
                - <?= $like["PROPERTIES"]["ARTNUMBER"]["VALUE"] ?>
                <br>
                <?= Loc::getMessage("MAY_LIKE")?>
                <?foreach($like["PROPERTIES"][$arParams["CODE_PROPERTY"]]["VALUE"] as $user) {
                    echo $arResult["USERS"][$user] . ' ';
                }?>
            </li>
        <? } ?>
    </ul>
<? } ?>

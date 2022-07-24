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
        <? foreach ($arResult["RESULT"] as $user) {
            ?>
            <li>
                [<?= $user["ID"]?>]

                <?= $user["LOGIN"] ?>
                <ul>
                    <? foreach ($user["ITEMS"] as $new) { ?>
                        <li><?= $new["NAME"]?></li>
                    <? } ?>
                </ul>
            </li>
            <?
        } ?>
    </ul>
<? } ?>

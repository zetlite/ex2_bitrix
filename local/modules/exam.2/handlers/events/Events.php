<?php

namespace Ex2\Handlers;

class Events
{
    public function initEvents()
    {
        $bxEventManager = \Bitrix\Main\EventManager::getInstance();

        $bxEventManager->addEventHandler(
            "iblock",
            "OnBeforeIBlockElementUpdate",
            [
                "Ex2\Handlers\Iblock",
                "onBeforeIBlockElementUpdateHandler",
            ]
        );

        $bxEventManager->addEventHandler(
            "iblock",
            "OnBeforeIBlockElementAdd",
            [
                "Ex2\Handlers\Iblock",
                "onBeforeIBlockElementAddHandler",
            ]
        );

        $bxEventManager->addEventHandler(
            "main",
            "OnEpilog",
            [
                "Ex2\Handlers\Main",
                "onEpilogHandler",
            ]
        );

        $bxEventManager->addEventHandler(
            "main",
            "OnBeforeEventAdd",
            [
                "Ex2\Handlers\Main",
                "onBeforeEventAddHandler",
            ]
        );

        $bxEventManager->addEventHandler(
            "main",
            "OnBuildGlobalMenu",
            [
                "Ex2\Handlers\Main",
                "onBuildGlobalMenuHandler",
            ]
        );

        $bxEventManager->addEventHandler(
            "main",
            "OnBeforeProlog",
            [
                "Ex2\Handlers\Main",
                "onBeforePrologHandler",
            ]
        );

        $bxEventManager->addEventHandler(
            "search",
            "BeforeIndex",
            [
                "Ex2\Handlers\Search",
                "beforeIndexHandler",
            ]
        );
    }
}
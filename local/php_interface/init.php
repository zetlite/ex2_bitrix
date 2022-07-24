<?php

use Bitrix\Main\Loader;

if (Loader::includeModule('exam.2')) {
    $eventManager = new Ex2\Handlers\Events();
    $eventManager->initEvents();
}
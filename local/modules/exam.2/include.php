<?php
use Bitrix\Main\Loader;

Loader::registerAutoLoadClasses(
    'exam.2',
    [
        'Ex2\Handlers\Events' => "handlers/events/Events.php",
        'Ex2\Handlers\Iblock' => "handlers/iblock/Iblock.php",
        'Ex2\Handlers\Main' => "handlers/main/Main.php",
        'Ex2\Handlers\Search' => "handlers/search/Search.php",

        'Ex2\Constants' => "tools/Constants.php",
        'Ex2\Helper' => "tools/Helper.php",
        'Ex2\Element' => "lib/Element.php",
        'Ex2\Agent' => "lib/Agent.php",
    ]
);
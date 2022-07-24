<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Оценка производительности");
?>   <p>
        Страница:
        <a href="http://learn.iw-studio.ru/bitrix/admin/perfmon_hit_list.php?lang=ru&set_filter=Y&find_script_name=%2Fproducts%2Findex.php">
            /products/index.php
        </a>
        <br>
        Доля нагрузки: 24.17%
    </p>

    <p>
        Кэш компонента simplecomp:ex2-71<br>
        - Кеш «по умолчанию»: 132 КБ<br>
        - Кеш при помещении в него только данных, необходимых в некешируемой части: 13 КБ<br>
        - Разница: 119 КБ
    </p><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
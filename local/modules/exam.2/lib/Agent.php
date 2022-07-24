<?php

namespace Ex2;

use \Bitrix\Main\UserTable;
use \Bitrix\Main\UserGroupTable;

class Agent
{
    public static function checkNewUsers()
    {
        $dateNow = ConvertTimeStamp(time(), "FULL");
        $dateLast = \COption::GetOptionString("main", "last_date_agent_check_new_users");

        $filter = [];
        if ($dateLast) {
            $filter = ["DATE_REGISTER_1" => $dateLast];
        }

        $select = [
            "ID",
            "NAME",
            "LOGIN",
            "DATE_REGISTER",
        ];

        $result = UserTable::getList([
            'select' => $select,
            'filter' => $filter,
            'order' => [
                'DATE_REGISTER' => "ASC",
            ],
        ]);

        while ($arUser = $result->fetch()) {
            $users[] = $arUser;
        }

        $countRegisterUsers = count($users);

        if (empty($dateLast)) {
            $dateLast = $users[0]["DATE_REGISTER"];
        }

        $iDifference = strtotime($dateNow) - strtotime($dateLast);
        $iDays = round($iDifference / (3600 * 24));

        $result = UserGroupTable::getList([
            'filter' => ['GROUP_ID' => 1, 'USER.ACTIVE' => 'Y'],
            'select' => ['USER_ID', 'NAME' => 'USER.NAME', 'LAST_NAME' => 'USER.LAST_NAME', "EMAIL" => "USER.EMAIL"],
        ]);

        while ($adminUsers = $result->fetch()) {
            \CEvent::Send(
                "COUNT_REGISTERED_USERS",
                SITE_ID,
                [
                    "EMAIL_TO" => $adminUsers["EMAIL"],
                    "COUNT_USERS" => $countRegisterUsers, // Количество зарегистрированных пользователей за период времени
                    "COUNT_DAYS" => $iDays, // За какое количество дней произведен подсчет
                ]
            );
        }

         \COption::SetOptionString("main", "last_date_agent_checkUserCount", $dateNow);

        return "Ex2\Agent\checkNewUsers();";
    }
}
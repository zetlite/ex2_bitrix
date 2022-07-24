<?
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Localization\Loc;

class exam_2 extends CModule
{
    var $MODULE_ID = "exam.2";
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;

    public function __construct()
    {
        $arModuleVersion = [];

        include(__DIR__ . '/version.php');

        $this->MODULE_VERSION = $arModuleVersion["VERSION"];
        $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];

        $this->MODULE_NAME = Loc::getMessage("EX2_MODULE_NAME");
        $this->MODULE_DESCRIPTION = Loc::getMessage("EX2_MODULE_DESCRIPTION");
    }

    function DoInstall()
    {
        ModuleManager::registerModule($this->MODULE_ID);
    }

    function DoUninstall()
    {
        ModuleManager::registerModule($this->MODULE_ID);
    }
}

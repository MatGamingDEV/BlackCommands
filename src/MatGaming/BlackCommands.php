<?php

namespace MatGaming;


use MatGaming\Commands\TpAcceptCommand;
use MatGaming\Commands\TpaCommand;
use MatGaming\Utils\MessageManager;
use MatGaming\Utils\TeleportManager;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class BlackCommands extends PluginBase
{
    /**
     * @var BlackCommands
     */
    private static BlackCommands $instance;

    /**
     * @var Config
     */
    private Config $setting;

    /**
     * @var Config
     */
    private Config $lang;

    /**
     * @var MessageManager
     */
    private MessageManager $messageManager;

    /**
     * @var TeleportManager
     */
    private TeleportManager $teleportManager;

    public function onEnable()
    {
        self::$instance = $this;
        $this->messageManager = new MessageManager($this);
        $this->teleportManager = new TeleportManager($this);
        $this->registerCommands();
        $this->initConfig();
    }
    
    public static function getInstance() : BlackCommands
    {
        return self::$instance;
    }

    public function getMessageManager() : MessageManager
    {
        return $this->messageManager;
    }

    public function getTeleportManager(): TeleportManager
    {
        return $this->teleportManager;
    }

    private function registerCommands() : void
    {
        $commandMap = $this->getServer()->getCommandMap();
        $commandMap->registerAll("BlackCommands", [
            new TpaCommand($this),
            new TpAcceptCommand($this)
        ]);
    }

    private function initConfig() : void
    {
        $dataFolder = $this->getDataFolder();
        @mkdir($dataFolder."lang");
        $this->saveResource("config.yml");
        $this->saveResource("lang/lang_en.json");
        $this->setting = new Config($dataFolder."config.yml", Config::YAML);
        $this->lang = new Config($dataFolder."lang/lang-".$this->setting->get("lang").".json", Config::JSON);
    }

    public function getConfigSetting() : Config
    {
        return $this->setting;
    }

    public function getLangFile() : Config
    {
        return $this->lang;
    }
}
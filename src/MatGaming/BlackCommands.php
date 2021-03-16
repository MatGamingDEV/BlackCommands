<?php

namespace MatGaming;


use MatGaming\Commands\TPACommand;
use MatGaming\Utils\CommandsManager;
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
     * @var CommandsManager
     */
    private CommandsManager $commandsManager;

    public function onEnable()
    {
        self::$instance = $this;
        $this->commandsManager = new CommandsManager($this);
        $this->registerCommands();
        $this->initConfig();
    }
    
    public static function getInstance() : BlackCommands
    {
        return self::$instance;
    }

    public function getCommandsManager() : CommandsManager
    {
        return $this->commandsManager;
    }

    private function registerCommands() : void
    {
        $commandMap = $this->getServer()->getCommandMap();
        $commandMap->registerAll("BlackCommands", [
            new TPACommand($this)
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
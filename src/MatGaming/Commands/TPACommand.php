<?php


namespace MatGaming\Commands;


use MatGaming\BlackCommands;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;

class TPACommand extends PluginCommand
{
    public function __construct(BlackCommands $plugin)
    {
        parent::__construct("tpa", $plugin);
        $commandManager = $plugin->getCommandsManager();
        $this->setDescription($commandManager->getDescription("TPA_DESC"));
        $this->setUsage($commandManager->getUsage("TPA_USAGE"));
        $this->setPermissionMessage($commandManager->getPermissionMessage("TPA_NO_PERM"));
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        // Nope :)
    }

}
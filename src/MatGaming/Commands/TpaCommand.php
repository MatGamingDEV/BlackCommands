<?php


namespace MatGaming\Commands;


use MatGaming\BlackCommands;
use MatGaming\Utils\MessageManager;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;

class TpaCommand extends PluginCommand
{
    private MessageManager $messageManager;

    /**
     * @var BlackCommands
     */
    private BlackCommands $plugin;

    public function __construct(BlackCommands $plugin)
    {
        parent::__construct("tpa", $plugin);
        $messageManager = $plugin->getMessageManager();
        $this->plugin = $plugin;
        $this->messageManager = $messageManager;
        $this->setDescription($messageManager->getDescription("TPA_DESC"));
        $this->setUsage($messageManager->getUsage("TPA_USAGE"));
        $this->setPermissionMessage($messageManager->getPermissionMessage("NO_PERMISSION"));
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player){
            if (!isset($args[0])){
                $sender->sendMessage($this->messageManager->getMessage("TPA_USAGE"));
                return;
            }else{
                $teleportManager = $this->plugin->getTeleportManager();
                $receiver = $teleportManager->getPlayer($args[0]);
                if ($receiver instanceof Player){
                    $teleportManager->sendTpaRequest($sender, $receiver);
                }else{
                    $sender->sendMessage($this->messageManager->getMessage("NOT_FOUND_PLAYER", ["player" => $args[0]]));
                }
            }
        }
    }

}
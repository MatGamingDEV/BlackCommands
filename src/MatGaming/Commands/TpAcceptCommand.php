<?php


namespace MatGaming\Commands;


use MatGaming\BlackCommands;
use MatGaming\Utils\MessageManager;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;

class TpAcceptCommand extends PluginCommand
{
    private MessageManager $messageManager;

    /**
     * @var BlackCommands
     */
    private BlackCommands $plugin;

    public function __construct(BlackCommands $plugin)
    {
        parent::__construct("tpaccept", $plugin);
        $messageManager = $plugin->getMessageManager();
        $this->plugin = $plugin;
        $this->messageManager = $messageManager;
        $this->setDescription($messageManager->getDescription("TPACCEPT_DESC"));
        $this->setUsage($messageManager->getUsage("TPACCEPT_USAGE"));
        $this->setPermissionMessage($messageManager->getPermissionMessage("NO_PERMISSION"));
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        $teleportManager = $this->plugin->getTeleportManager();
        if ($sender instanceof Player){
            if (!$teleportManager->hasRequest($sender)){
                $sender->sendMessage($this->messageManager->getMessage("NO_REQUEST"));
                return;
            }else{
                $teleportManager->teleport($sender);
            }
        }
    }

}
<?php


namespace MatGaming\Utils;


use MatGaming\BlackCommands;
use pocketmine\Player;
use pocketmine\Server;

class TeleportManager
{
    /**
     * @var BlackCommands
     */
    private BlackCommands $plugin;

    public array $tpRequest = [];

    public array $god = [];

    public function __construct(BlackCommands $plugin)
    {
        $this->plugin = $plugin;
    }

    public function getPlayer(string $name) : Player
    {
        return Server::getInstance()->getPlayer($name);
    }

    public function sendTpaRequest(Player $sender, Player $receiver) : void
    {
        $this->tpRequest[$receiver->getName()] = [
            "sender" => $sender->getName(),
            "maxTime" => time() + $this->getMaxTimeReply(),
            "here" => false
        ];
        $messageManager = $this->plugin->getMessageManager();
        $sender->sendMessage($messageManager->getMessage("TPA_SEND", ["receiver" => $receiver->getName()]));
        $receiver->sendMessage($messageManager->getMessage("TPA_RECEIVE", ["sender" => $sender->getName()]));
    }

    public function sendTpHereRequest(Player $sender, Player $receiver) : void
    {
        $this->tpRequest[$receiver->getName()] = [
            "sender" => $sender->getName(),
            "maxTime" => time() + $this->getMaxTimeReply(),
            "here" => true
        ];
        $messageManager = $this->plugin->getMessageManager();
        $sender->sendMessage($messageManager->getMessage("TPHERE_SEND", ["receiver" => $receiver->getName()]));
        $receiver->sendMessage($messageManager->getMessage("TPHERE_RECEIVE", ["sender" => $sender->getName()]));
    }

    private function getMaxTimeReply() : int
    {
        return (int)$this->plugin->getConfigSetting()->get("Teleport")["max-time-reply"];
    }

    public function hasRequest(Player $player) : bool
    {
        if (!isset($this->tpRequest[$player->getName()]) or time() >= $this->tpRequest[$player->getName()]["maxTime"]){
            return false;
        }
        return true;
    }

    public function teleport(Player $player) : void
    {
        $messageManager = $this->plugin->getMessageManager();
        if ($this->tpRequest[$player->getName()]["Here"] == false){
            $sender = $this->getPlayer($this->tpRequest[$player->getName()]["sender"]);
            if ($sender instanceof Player){
                $player->teleport($sender->getPosition());
                $this->godTime($player, $sender);
                $player->sendMessage($messageManager->getMessage("TPA_TP", ["sender" => $sender->getName()]));
                $sender->sendMessage($messageManager->getMessage("TPA_ACCEPT", ["receiver" => $player->getName()]));
            }else{
                $player->sendMessage($messageManager->getMessage("PLAYER_DISCONNECTED"));
            }
        }else{
            $sender = $this->getPlayer($this->tpRequest[$player->getName()]["sender"]);
            if ($sender instanceof Player){
                $sender->teleport($player->getPosition());
                $this->godTime($player, $sender);
                $player->sendMessage($messageManager->getMessage("TPA_TP", ["sender" => $sender->getName()]));
                $sender->sendMessage($messageManager->getMessage("TPA_ACCEPT", ["receiver" => $player->getName()]));
            }else{
                $player->sendMessage($messageManager->getMessage("PLAYER_DISCONNECTED"));
            }
        }
    }

    public function godTime(Player $pl1, Player $pl2) : void
    {
        $setting = $this->plugin->getConfigSetting();
        if ($setting->get("teleport")["immunise-after-teleportation"] == true){
            $this->god[$pl1->getName()] = time() + (int)$setting->get("teleport")["god-time"];
            $this->god[$pl2->getName()] = time() + (int)$setting->get("teleport")["god-time"];
        }
    }

}
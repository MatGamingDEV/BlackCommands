<?php


namespace MatGaming\Utils;


use MatGaming\BlackCommands;

class MessageManager
{
    /**
     * @var BlackCommands
     */
    private BlackCommands $plugin;

    /**
     * @var array
     */
    private array $id;

    /**
     * @var string
     */
    private string $prefix;

    public function __construct(BlackCommands $plugin)
    {
        $this->plugin = $plugin;
        $this->id = json_decode((string)$plugin->getLangFile()->getAll(), true);
        $this->prefix = $plugin->getConfigSetting()->get("prefix");
    }

    public function getDescription(string $IdCommand) : string
    {
        return $this->id[$IdCommand] ?? "§4Description $IdCommand not found in the lang file";
    }

    public function getUsage(string $IdCommand) : string
    {
        $usage = $this->id[$IdCommand] ?? "§4Usage $IdCommand not found in the lang file";
        $usage = $this->prefix + $usage;
        return $usage;
    }

    public function getPermissionMessage(string $IdCommand) : string
    {
        $permMessage = $this->id[$IdCommand] ?? "§4Permission Message $IdCommand not found in the lang file";
        $permMessage = $this->prefix + $permMessage;
        return $permMessage;
    }

    public function getMessage(string $IdMessage, array $args = []) : string
    {
        $Message = $this->id[$IdMessage] ?? "§4Message $IdMessage not found in the lang file";
        foreach ($args as $arg => $value){
            $Message = str_replace("{".$arg."}", $value, $Message);
        }
        $Message = $this->prefix + $Message;
        return $Message;
    }

}
<?php

namespace AC;

use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerCommandPreProcessEvent;
use pocketmine\command\CommandExecutor;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\command\Command;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\command\ConsoleCommandSender;

class Main extends PluginBase implements Listener
{

public function onEnable() {
		

        @mkdir($this->getDataFolder() . "AccountLock/Players/");
		$this->getServer()->getPluginManager()->registerEvents($this,$this);
		
		}
		 public function onCommand(CommandSender $sender, Command $cmd, $label, array $args) {
        if(strtolower($cmd->getName()) === "lockacc") {
            if(isset($args[0]) && isset($args[1])){
                $name = $args[0];
                $reason = $args[1];
               /*will this work?*/ explode(" ",$reason);
                $target = $this->getServer()->getPlayer($name);
                if(!$target === null){
                $ign = $target->getName();
                         $this->PlayerFile = new Config($this->getDataFolder()."Players/".$ign.".yml", Config::YAML);
                         $this->PlayerFile->set("Ban","true");
                        $this->PlayerFile->set("Banner",$sender->getName()); 
                        $this->PlayerFile->set("Reason",$reason);
                         $this->PlayerFile->save();
                         $target->kick("§6Your Account Is Locked! Locked By §b".$this->PlayerFile->get("Banner")." §6For §b".$reason);
                Server::getInstance()->broadcastMessage("§f".$target->getName()." §9Now Has Thier Account Locked!");
                }else{
                         $this->PlayerFile = new Config($this->getDataFolder()."Players/".$target->getName().".yml", Config::YAML);
                         $this->PlayerFile->set("Ban","true");
                        $this->PlayerFile->set("Banner",$sender->getName()); 
                        $this->PlayerFile->set("Reason",$reason);
                         $this->PlayerFile->save();
                }
                }
                }
                if(strtolower($cmd->getName()) === "unlockacc") {
            if(isset($args[0])) {
                $name = $args[0];
                $target = $this->getServer()->getPlayer($name);
                $ign = $target->getName();
                         $this->PlayerFile = new Config($this->getDataFolder()."Players/".$ign.".yml", Config::YAML);
                         $this->PlayerFile->set("Ban","false");
                         $target->sendMessage("§aYour account is now unlocked!");
                         $this->PlayerFile->save();
                	
                }
                }
		 }
               
			public function onProcessCmd(PlayerCommandPreProcessEvent $ev){
			 if($this->PlayerFile->get("Ban") === "true"){
			 $ev->setCancelled();
			}
			}
			public function onJoin(PlayerJoinEvent $ev){
			$player=$ev->getPlayer();
			 $ign = $player->getName();
                         $this->PlayerFile = new Config($this->getDataFolder()."Players/".$ign.".yml", Config::YAML);
                       if($this->PlayerFile->get("Ban") === "true"){
                       $player->setNameTag("§c[ACCOUNT LOCKED]\n".$ign);
			}
			}
			public function onChat(PlayerChatEvent $ev){
			 			$player=$ev->getPlayer();
			 $ign = $player->getName();
                         $this->PlayerFile = new Config($this->getDataFolder()."Players/".$ign.".yml", Config::YAML);
                       if($this->PlayerFile->get("Ban") === "true"){
                                              $ev->setCancelled();
			}
			}
			public function onInteract(PlayerInteractEvent $ev){
			 			$player=$ev->getPlayer();
			 $ign = $player->getName();
                         $this->PlayerFile = new Config($this->getDataFolder()."Players/".$ign.".yml", Config::YAML);
                         
                       if($this->PlayerFile->get("Ban") === "true"){
                     $ev->setCancelled();
			}
			}

			 public function onPlayerLogin(PlayerPreLoginEvent $event){
        $ign = $event->getPlayer()->getName();
        $player = $event->getPlayer();
        $file = ($this->getDataFolder()."Players/".$ign.".yml");  
            if(!file_exists($file)){
                $this->PlayerFile = new Config($this->getDataFolder()."Players/".$ign.".yml", Config::YAML);
                $this->PlayerFile->set("Ban","false");
                $this->PlayerFile->save();
            }
        }
			}

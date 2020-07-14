<?php

namespace iQuackerzMC\GuideUI;

use pocketmine\plugin\PluginBase;
use pocketmine\Player; 
use pocketmine\Server;
use pocketmine\event\Listener;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Event;
use pocketmine\event\player\PlayerJoinEvent;
class Main extends PluginBase implements Listener {


	public function onEnable(){
		$this->getLogger()->info("Enabling GuideUI By iQuackerzMC");
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->saveDefaultConfig();
	}	
	public function onCommand(CommandSender $sender, Command $command, String $label, array $args) : bool {
	    if($sender instanceof Player){
		   if(strtolower($command->getName()) == "guide"){
              if(null !== $this->getConfig()->getNested('guide')){
              	$this->mainform($sender);
              }else{
              	$sender->sendMessage("Error code 1;");
              }
           }
		   return true;
		}else{
		   if(strtolower($command->getName()) == "guide"){
              $sender->sendMessage("Please run this command in game.");
		   }
		   return true;
		}
	}
	public function mainform($p){
		$api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $api->createSimpleForm(function (Player $p, int $data = null){
			$result = $data;
			if($result === null){
				return false;
			}
			 $this->moreinfo($p,$data);
			});			
			$form->setTitle("§l§cGuide §dUI");
			$form->setContent(
				"§7Select the required option to view\n".
				"§7detailed information about it:"
			);
			$num = 0;
			foreach($this->getConfig()->getNested('guide') as $value){
				$name = $value["name"];
			    if($num == 0){
			    	$form->addButton("$name");
			        $num = 1;
			    }else{
			    	$form->addButton("$name");
			        $num = 0;
			    }
				
			}
			$form->sendToPlayer($p);
			return $form;
	}
	public function moreinfo($p,$id){
		$api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $api->createSimpleForm(function (Player $p, int $data = null){
			$result = $data;
			if($result === null){
				return false;
			}
			switch ($data) {
						case "0":					
							break;				
						default:
							$this->mainform($p);
							break;
					}		
			});
			$form->setTitle("§l§cGuide §dUI");
			$txt = "Error code 2;";
			if(isset($this->getConfig()->getNested('guide')[$id]["info"])){
				$txt = $this->getConfig()->getNested('guide')[$id]["info"];
			}
			$form->setContent(	
				"$txt"
			);				
			$form->addButton("Submit");
			$form->sendToPlayer($p);
			return $form;
	}
	
}

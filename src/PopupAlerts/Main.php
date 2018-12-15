<?php

/*
 * PopupAlerts (v1.3) by EvolSoft
 * Developer: EvolSoft (Flavius12)
 * Website: http://www.evolsoft.tk
 * Date: 14/07/2015 02:44 PM (UTC)
 * Copyright & License: (C) 2015 EvolSoft
 * Licensed under MIT (https://github.com/EvolSoft/PopupAlerts/blob/master/LICENSE)
 */

namespace PopupAlerts;

use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;

//CustomAlerts API
use CustomAlerts\CustomAlerts;
use CustomAlerts\Events\CustomAlertsDeathEvent;
use CustomAlerts\Events\CustomAlertsJoinEvent;
use CustomAlerts\Events\CustomAlertsQuitEvent;
use CustomAlerts\Events\CustomAlertsWorldChangeEvent;

class Main extends PluginBase implements Listener{

	//About Plugin Const

	/** @var string PRODUCER Plugin producer */
	const PRODUCER = "EvolSoft";

	/** @var string VERSION Plugin version */
	const VERSION = "1.4";

	/** @var string MAIN_WEBSITE Plugin producer website */
	const MAIN_WEBSITE = "http://www.evolsoft.tk";

	//Other Const

	/** @var string PREFIX Plugin prefix */
	const PREFIX = "&1[&bPopup&aAlerts&1] ";

	/** @var array Config data */
	private $cfg;

	public function onEnable(){
		if($this->getServer()->getPluginManager()->getPlugin("CustomAlerts")){
			if(CustomAlerts::getAPI()->getAPIVersion() == "2.0"){
				@mkdir($this->getDataFolder());
				$this->saveDefaultConfig();
				$this->cfg = $this->getConfig()->getAll();
				$this->getServer()->getPluginManager()->registerEvents($this, $this);
			}else{
				$this->getLogger()->error(TextFormat::colorize(Main::PREFIX . "&cPlease update CustomAlerts to API 2.0!", "&"));
				$this->getServer()->getPluginManager()->disablePlugin($this);
			}
		}else{
			$this->getLogger()->error(TextFormat::colorize(Main::PREFIX . "&cYou need to install CustomAlerts (API 2.0)!", "&"));
			$this->getServer()->getPluginManager()->disablePlugin($this);
		}
	}

	public function onCAJoin(CustomAlertsJoinEvent $event){
		if($this->cfg["Join"]["show-popup"] == true){
			$msg = CustomAlerts::getAPI()->getJoinMessage($event->getPlayer());
			$this->getScheduler()->scheduleRepeatingTask(new MessageTask($this, $msg, $this->cfg["Join"]["duration"]), 10);
			if($this->cfg["Join"]["hide-default"] == true){
				$event->setMessage("");
			}
		}
	}

	public function onCAQuit(CustomAlertsQuitEvent $event){
		if($this->cfg["Quit"]["show-popup"] == true){
			$msg = CustomAlerts::getAPI()->getQuitMessage($event->getPlayer());
			$this->getScheduler()->scheduleRepeatingTask(new MessageTask($this, $msg, $this->cfg["Quit"]["duration"]), 10);
			if($this->cfg["Quit"]["hide-default"] == true){
				$event->setMessage("");
			}
		}
	}

	public function onCAWorldChange(CustomAlertsWorldChangeEvent $event){
		if(CustomAlerts::getAPI()->isWorldChangeMessageEnabled()){
			if($this->cfg["WorldChange"]["show-popup"] == true){
				$msg = CustomAlerts::getAPI()->getWorldChangeMessage($event->getPlayer(), $event->getOrigin(), $event->getTarget());
				$this->getScheduler()->scheduleRepeatingTask(new MessageTask($this, $msg, $this->cfg["WorldChange"]["duration"]), 10);
				if($this->cfg["WorldChange"]["hide-default"] == true){
					$event->setMessage("");
				}
			}
		}
	}

	public function onCADeath(CustomAlertsDeathEvent $event){
		if($this->cfg["Death"]["show-popup"] == true){
			$msg = CustomAlerts::getAPI()->getDeathMessage($event->getPlayer());
			$this->getScheduler()->scheduleRepeatingTask(new MessageTask($this, $msg, $this->cfg["Death"]["duration"]), 10);
			if($this->cfg["Death"]["hide-default"] == true){
				$event->setMessage("");
			}
		}
	}
}
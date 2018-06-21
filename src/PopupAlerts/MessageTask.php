<?php

/*
 * PopupAlerts (v1.3) by EvolSoft
 * Developer: EvolSoft (Flavius12)
 * Website: http://www.evolsoft.tk
 * Date: 21/05/2015 01:16 PM (UTC)
 * Copyright & License: (C) 2015 EvolSoft
 * Licensed under MIT (https://github.com/EvolSoft/PopupAlerts/blob/master/LICENSE)
 */

namespace PopupAlerts;

use pocketmine\scheduler\Task;

class MessageTask extends Task{

	private $message;
	private $duration;
	private $current;

	public function __construct(Main $plugin, $message, $duration){
		$this->plugin = $plugin;
		$this->message = $message;
		$this->duration = $duration;
		$this->current = 0;
	}

	public function onRun(int $tick){
		if($this->current <= $this->duration){
			foreach($this->plugin->getServer()->getOnlinePlayers() as $players){
				$players->sendPopup($this->plugin->translateColors("&", $this->message));
			}
		}else{
			$this->plugin->getScheduler()->cancelTask($this->getTaskId());
		}
		$this->current += 1;
	}
}